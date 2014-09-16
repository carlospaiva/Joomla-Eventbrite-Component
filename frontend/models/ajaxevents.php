<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/15/14
 */

defined('_JEXEC') or die;

/**
 * Methods supporting a list of article records.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @since       1.6
 */
class EventbriteModelAjaxevents extends JModelItem
{
    /*
    * Eventbrite base URL
    */
    public $eventbriteBaseURL = 'https://www.eventbriteapi.com';

    public function getItem()
    {
        $eventRegistry = new JRegistry();

        $eventRegistry->loadString($this->getEventBriteIds());

        $validEvent = true;
        $eventRegistryIterator = 0;

        $eventList = array();

        // this will be a loop
        while($validEvent)
        {
            // check the key exists
            if (! $eventRegistry[$eventRegistryIterator])
            {
                $validEvent = false;
                break;
            }

            // get id from the array
            $id = $eventRegistry[$eventRegistryIterator];

            // goes to eventbrite for event details
            $eventDetails = json_decode($this->getEvent($id));

            // get our remaining tickets for this event
            $ticketsRemaining   = $this->getTicketsRemaining($eventDetails->ticket_classes, $eventDetails->capacity);
            $ticketPriceRange = $this->getTicketPriceRange($eventDetails->ticket_classes);

            // build a new object so we can json_encode
            $event = new stdClass();

            $event->name                = $eventDetails->name->text;
            $event->link                = $eventDetails->url;
            $event->tickets_remaining   = $ticketsRemaining;
            $event->capacity            = $eventDetails->capacity;
            $event->price_range         = $ticketPriceRange;

            // append object to the array
            $eventList[] = $event;

            // increment iterator
            $eventRegistryIterator++;
        }

        return json_encode($eventList);
    }

    public function getEventBriteIds()
    {
        $app = JFactory::getApplication();

        $id = $app->input->get('eid');

        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('eventbrite_ids')
            ->from('#__eventbrites')
            ->where('id='.$id);

        $db->setQuery($query);

        $result = $db->loadResult();

        return $result;
    }


    public function getEvent($eid)
    {
        $params = JComponentHelper::getParams('com_eventbrite');

        $personalToken = $params->get('personal_oauth');

        $getEvents = new JHttp();

        $headers = array('Authorization' => 'Bearer ' . $personalToken);

        $result = $getEvents->get($this->eventbriteBaseURL . '/v3/events/' . $eid ,  $headers);

        if ($result->code != 200)
        {
            // there was an error
            return false;
        }

        return $result->body;
    }

    public function getTicketsRemaining($ticket_classes, $capacity)
    {
        // we must have ticket classes and an overal capacity
        if (!$ticket_classes || !$capacity)
        {
            return false;
        }

        // init the quantity sold to 0
        $quantity_sold  = 0;


        foreach($ticket_classes as $ticket)
        {
            // add all sold ticket quantities together
            $quantity_sold  += $ticket->quantity_sold;
        }

        // calculate final available tickets
        $tickets_available = $capacity - $quantity_sold;

        // we should never be less than 0
        if ($tickets_available < 0)
        {
            return false;
        }

        // if the event is sold out lets display that instead of 0
        if ($tickets_available === 0)
        {
            $tickets_available = 'SOLD OUT';
        }

        return $tickets_available;
    }

    public function getTicketPriceRange($ticket_classes)
    {
        $prices = array();


        foreach ($ticket_classes as $ticket)
        {
           $prices[] = $ticket->cost->display;
        }

        // sort prices lowest to highest
        sort($prices);

        $lowestHighest = new stdClass();

        $lowestHighest->lowest  = $prices[0]; // first item of array
        $lowestHighest->highest = $prices[count($prices) - 1]; // last item of array

        return $lowestHighest;
    }
}