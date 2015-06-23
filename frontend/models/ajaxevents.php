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

    protected $_item;

    protected $eventIdList;
    /*
    * Eventbrite base URL
    */
    public $eventbriteBaseURL = 'https://www.eventbriteapi.com';


    /*
     * Build the item list out
     */
    public function getItem()
    {

        $this->eventIdList = json_decode($this->getEventBriteIds());

        $eventList = array();

        // this will be a loop
        foreach($this->eventIdList as $event)
        {

            // get id from the array
            $id = $event;

            // goes to eventbrite for event details
            $eventDetails = json_decode($this->getEvent($id));

            $ticketClass = json_decode($this->getEventTickets($id));

            // get our remaining tickets for this event
            $ticketsRemaining   = $this->getTicketsRemaining($ticketClass->ticket_classes, $eventDetails->capacity);

            // get highest lowest price range
            $ticketPriceRange = $this->getTicketPriceRange($ticketClass->ticket_classes);

            // build a new object so we can json_encode
            $event = new stdClass();

            $event->name                = 'Starts at ' . $eventDetails->venue->name;
            $event->link                = $eventDetails->url;
            $event->tickets_remaining   = $ticketsRemaining;
            $event->capacity            = $eventDetails->capacity;
            $event->price_range         = $ticketPriceRange;
            $event->order               = $this->getItemOrder($id);

            // append object to the array
            $eventList[] = $event;
        }

        usort($eventList, array('EventbriteModelAjaxevents', 'compareListItemOrder'));

        // return array of all events as a json string
        return json_encode($eventList);
    }

    public function getItemOrder($item_id)
    {

        $itemorder = json_decode($this->_item->eventbrite_ids_order);

        $count = 0;
        foreach ($this->eventIdList as $id)
        {
            if ($id == $item_id)
            {
                return $itemorder[$count];
            }
            $count++;
        }

        return 0;

    }


    /*
     * Compare list item order
     */

    public function compareListItemOrder($a, $b)
    {
        return $a->order > $b->order;

    }


    /*
     * Get the eventbrite id list from the _item property
     */

    public function getEventBriteIds()
    {
        // check that the item is set
        if (!$this->_item)
        {
            // if not set call the query to set it
            $this->_getItem();
        }

        $eventbriteIds = $this->_item->eventbrite_ids;

        // return event ids as string - use JRegistry to parse
        return $eventbriteIds;
    }

    /*
     * Get the item with a query
     * Set the item property
     * Use this to prevent multiple queries for different columns
     */

    protected function _getItem()
    {
        $app = JFactory::getApplication();

        $id = $app->input->get('eid');

        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('a.eventbrite_ids, a.eventbrite_ids_order')
            ->from('#__eventbrites as a')
            ->where('id='.$id);

        $db->setQuery($query);

        // set the _item property
        $this->_item = $db->loadObject();
    }

    /*
     * Call to the eventbrite API and get the event details
     */

    public function getEvent($eid)
    {
        $params = JComponentHelper::getParams('com_eventbrite');

        $personalToken = $params->get('personal_oauth');

        $getEvents = new JHttp();

        $headers = array('Authorization' => 'Bearer ' . $personalToken);

        $result = $getEvents->get($this->eventbriteBaseURL . '/v3/events/' . $eid . '?expand=venue' ,  $headers);

        // return json encoded body
        return $result->body;
    }

    public function getEventTickets($eid)
    {
        $params = JComponentHelper::getParams('com_eventbrite');

        $personalToken = $params->get('personal_oauth');

        $getEvents = new JHttp();

        $headers = array('Authorization' => 'Bearer ' . $personalToken);

        $result = $getEvents->get($this->eventbriteBaseURL . '/v3/events/' . $eid . '/ticket_classes' ,  $headers);

        // return json encoded body
        return $result->body;
    }

    /*
     * Parse through the event class array
     * Calculate the available ticket count with tickets sold - capacity
     */

    public function getTicketsRemaining($ticket_classes, $capacity)
    {
        // we must have ticket classes and an overal capacity
        if (! is_array($ticket_classes) || !$capacity)
        {
            return '';
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
            $tickets_available = 'UNAVAILABLE';
        }

        return $tickets_available;
    }

    /*
     * Get the highest and lowest ticket proce
     * @return an object with the highest lowest as properties
     */

    public function getTicketPriceRange($ticket_classes)
    {
        if (!count($ticket_classes)) {
            return '';
        }

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

    /*
     * Will always return an array. Might be empty if there
     * aren't any saved events
     */

    public function isIdSaved()
    {
        // set up an empty array
        $idList = array();

        if (! $this->_item)
        {
            $this->_getItem();
        }

        $savedIds = json_decode($this->_item->eventbrite_ids);

        if ($savedIds)
        {
            // update array
            $idList = $savedIds;
        }

        return $idList;
    }
}