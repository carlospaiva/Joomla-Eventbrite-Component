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

        $eventIdList = $this->getEventBriteIds();

        // var_dump($eventIdList);

        $eventIdList = array('13146015085');

        // this will be a loop
        foreach($eventIdList as $id)
        {
            $eventDetails = json_decode($this->getEvent($id));
            $ticketsRemaining = $this->getTicketsRemaining($eventDetails->ticket_classes);
            // var_dump($ticketsRemaining);
        }

        $event = new stdClass();

        $event->name = $eventDetails->name->text;
        $event->link = $eventDetails->url;
        $event->tickets_remaining = $ticketsRemaining;

        $eventList = array($event);


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

    public function getTicketsRemaining($ticket_classes)
    {
        // we must have ticket classes to use this function
        if (!$ticket_classes)
        {
            return false;
        }

        $quantity_total = 0;
        $quantity_sold  = 0;


        foreach($ticket_classes as $ticket)
        {
            $quantity_total += $ticket->quantity_total;
            $quantity_sold  += $ticket->quantity_sold;
        }

        $tickets_available = $quantity_total - $quantity_sold;

        // we should never be less than 0
        if ($tickets_available < 0)
        {
            return false;
        }

        return $tickets_available;
    }



}