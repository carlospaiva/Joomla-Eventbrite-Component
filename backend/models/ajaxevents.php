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

    private $organizer_id;

    protected $_item;

    /*
     * Build the item list
     */
    public function getItems()
    {
        // get the params from the component
        $params = JComponentHelper::getParams('com_eventbrite');

        // our organizer id
        $this->organizer_id = $params->get('organizer_id');

        $events = $this->getEventsbyOrganizer();

        // no events so return false
        if(!$events)
        {
            return false;
        }

        $response = json_decode($events->body);

        $response = $this->getPaginatedEvents($response);

        $eventList = $this->buildEventObjects($response);

        return (json_encode($eventList));
    }

    /*
     * call to eventbrite api and search for events
     * query is search by organizer id
     */

    public function getEventsbyOrganizer($page = 1)
    {
        $params         = JComponentHelper::getParams('com_eventbrite');
        $personalToken  = $params->get('personal_oauth');

        if (! $this->organizer_id)
        {
            return false;
        }

        // we must have a token to
        if (! $personalToken)
        {
            return false;
        }

        // get input object
        $input          = JFactory::getApplication()->input;
        $search_query   = $input->getString('search', '');
        $organizerId    = 'organizer.id=' . $this->organizer_id;
        $userId         = $params->get('user_id');

        // build search string
        //$searchString   = $organizerId;

        // if there's a search query add it to the params
        if ($search_query)
        {
            // append search param
            // $searchString .= '&q=' . $search_query;
        }

        // add pagination
        $searchString = '&page=' . $page;

        $getEvents  = new JHttp();
        $headers    = array('Authorization' => 'Bearer ' . $personalToken, 'status' => 'live');
        $result     = $getEvents->get($this->eventbriteBaseURL . '/v3/users/' . $userId . '/owned_events/?status=live' . $searchString, $headers);

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
            $eventDetails->selected = in_array($event->id, $this->isIdSaved());

            $eventList[] = $eventDetails;
        }

        return $eventList;
    }

    public function getPaginatedEvents($response)
    {
        if ($response->pagination->page_count <= 1)
        {
            return $response;
        }
        // we had more than one page so let's get the rest of them.

        // hang on to our total number of pages.
        $page_count = $response->pagination->page_count;

        // and get all our current events
        $combinedEventsList = $response->events;

        // we start at page 2 because we don't even get here unless we 2+ pages
        for ($page_iterator = 2; $page_iterator <= $page_count; $page_iterator++)
        {
            $nextPage = $this->getEventsbyOrganizer($page_iterator);

            $nextPage = json_decode($nextPage->body);

            if ($nextPage->events)
            {
                $combinedEventsList = array_merge($combinedEventsList, $nextPage->events);
            }
        }

        // update the original response body
        $response->events = $combinedEventsList;

        return $response;

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