<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/7/14
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
class EventbriteModelEventbrites extends JModelList
{
    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @since   1.6
     * @see     JController
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     *
     * @since   1.6
     */
    protected function getListQuery()
    {
        $db     = $this->getDbo();
        $query  = $db->getQuery(true);
        $user   = JFactory::getUser();
        $app    = JFactory::getApplication();

        $query->select('a.id, a.title, a.alias, a.description, a.published, a.event_date, a.cat_id');
        $query->select('b.id as category_id, b.title as category_name');
        $query->leftJoin('#__categories as b ON a.cat_id=b.id');
        $query->from('#__eventbrites as a');
        $query->order('a.event_date ASC');

        return $query;
    }
}