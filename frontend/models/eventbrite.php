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
class EventbriteModelEventbrite extends JModelItem
{
    /**
     * An item.
     *
     * @var    array
     */
    protected $_item = null;

    /**
     * Model context string.
     *
     * @var    string
     * @since  12.2
     */
    protected $_context = 'com_eventbrite.eventbrite';


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

    public function getItem($pk = null)
    {
        $input = JFactory::getApplication()->input;
        $pk = (!empty($pk)) ? $pk : (int) $input->getInt('id');

        if (isset($this->_item[$pk]))
        {
            return $this->_item;
        }

        try
        {
            // get the item
            $db     = $this->getDbo();
            $query  = $db->getQuery(true)
                    ->select('a.title, a.alias, a.description, a.eventbrite_ids, a.id')
                    ->from('#__eventbrites as a')
                    ->where('a.id=' . $db->quote($pk))
                    ->where('a.published=' . 1);

            $db->setQuery($query);

            $item = $db->loadObject();

            // convert db registry to string
            $eventbrite_ids = new JRegistry;

            $eventbrite_ids->loadString($item->eventbrite_ids);

            $item->eventbrite_ids = $eventbrite_ids;

            $this->_item[$pk] = $item;
        }
        catch (Exception $e)
        {
            if ($e->getCode() == 404)
            {
                // Need to go thru the error handler to allow Redirect to work.
                JError::raiseError(404, $e->getMessage());
            }
            else
            {
                $this->setError($e);
                $this->_item[$pk] = false;
            }
        }

        return $this->_item[$pk];
    }

}