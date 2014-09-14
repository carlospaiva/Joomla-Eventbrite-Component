<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/14/14
 */

class EventbriteModelAjaxevents extends JModelList
{
    /*
    * Eventbrite base URL
    */
    public $eventbriteBaseURL = 'https://www.eventbriteapi.com';

    /*
     * Build the item list
     */
    public function getItems()
    {
        // get the params from the component
        $params = JComponentHelper::getParams('com_eventbrite');

        // our organizer id
        $organizerid = $params->get('organizer_id');

        $events = $this->getEventsbyOrganizer($organizerid);

        // no events so return false
        if(!$events)
        {
            return false;
        }

        $response = json_decode($events->body);

        $eventList = $this->buildEventObjects($response);

        return (json_encode($eventList));
    }

    /*
     * call to eventbrite api and search for events
     * query is search by organizer id
     */

    public function getEventsbyOrganizer($organizerid)
    {
        $params = JComponentHelper::getParams('com_eventbrite');

        $personalToken = $params->get('personal_oauth');

        $organizerId = 'organizer.id=' . $organizerid;

        $getEvents = new JHttp();

        $headers = array('Authorization' => 'Bearer ' . $personalToken);

        $result = $getEvents->get($this->eventbriteBaseURL . '/v3/events/search?' . $organizerId, $headers);

        if ($result->code != 200)
        {
            // there was an error
            return false;
        }

        return $result;
    }

    /*
     * Cleans up the response from eventbrite
     * Dumps the description field
     */

    public function buildEventObjects($response)
    {
        $eventList = array();

        // for some reason there aren't any events
        if (!count($response->events))
        {
            return false;
        }

        foreach($response->events as $event)
        {
            $eventTicketList = array();

            if (count($event->ticket_classes))
            {
                foreach ($event->ticket_classes as $eventTicket)
                {
                    $ticket = new Stdclass;
                    $ticket->id = $eventTicket->id;
                    $ticket->name = $eventTicket->name;
                    $eventTicketList[] = $ticket;
                }
            }

            $eventDetails = new stdClass;
            $eventDetails->name = $event->name->text;
            $eventDetails->id = $event->id;
            $eventDetails->tickets = $eventTicketList;

            $eventList[] = $eventDetails;
        }

        return $eventList;
    }
}