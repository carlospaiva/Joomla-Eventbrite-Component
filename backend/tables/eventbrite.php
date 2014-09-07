<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/7/14
 */

class EventbriteTableEventbrite extends JTable
{
    /**
     * @param   JDatabaseDriver  A database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__eventbrites', 'id', $db);
    }
}