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

    protected $_item;

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
        $params         = JComponentHelper::getParams('com_eventbrite');
        $personalToken  = $params->get('personal_oauth');

        // get input object
        $input          = JFactory::getApplication()->input;
        $search_query   = $input->getString('search', '');
        $organizerId    = 'organizer.id=' . $organizerid;

        // build search string
        $searchString   = $organizerId;

        // if there's a search query add it to the params
        if ($search_query)
        {
            $searchString = '&q=' . $search_query;
        }

        $getEvents  = new JHttp();
        $headers    = array('Authorization' => 'Bearer ' . $personalToken);
        $result     = $getEvents->get($this->eventbriteBaseURL . '/v3/events/search?' . $searchString, $headers);

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
            $eventDetails           = new stdClass;
            $eventDetails->name     = $event->name->text;
            $eventDetails->id       = $event->id;
            $eventDetails->venue    = $event->venue->name;
            $eventDetails->url      = $event->url;
            $eventDetails->capacity = $event->capacity;
            $eventDetails->selected = in_array($event->id, $this->isIdSaved($event->id));

            $eventList[] = $eventDetails;
        }

        return $eventList;
    }

    protected function _getItem()
    {
        $app    = JFactory::getApplication();
        $id     = $app->input->getInt('eid');
        $db     = $this->getDbo();
        $query  = $db->getQuery(true);
        $query  ->select('a.eventbrite_ids')
                ->from('#__eventbrites as a')
                ->where('id='.$db->quote($id));

        $db->setQuery($query);

        $this->_item = $db->loadObject();
        return;
    }

    public function isIdSaved($id)
    {
        if (! $this->_item)
        {
            $this->_getItem();
        }

        $idList = json_decode($this->_item->eventbrite_ids);

        return $idList;
    }
}