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
class EventbriteModelEventbrite extends JModelAdmin
{
    /**
     * @var        string    The prefix to use with controller messages.
     * @since   1.6
     */
    protected $text_prefix = 'COM_EVENTBRITE';

    /**
     * The type alias for this content type (for example, 'com_content.article').
     *
     * @var      string
     * @since    3.2
     */
    public $typeAlias = 'com_eventbrite.eventbrite';



    /**
     * Returns a Table object, always creating it.
     *
     * @param   type      The table type to instantiate
     * @param   string    A prefix for the table class name. Optional.
     * @param   array     Configuration array for model. Optional.
     *
     * @return  JTable    A database object
     */
    public function getTable($type = 'Eventbrite', $prefix = 'EventbriteTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get a single record.
     *
     * @param   integer    The id of the primary key.
     *
     * @return  mixed  Object on success, false on failure.
     */
    public function getItem($pk = null)
    {
        $item = parent::getItem($pk);

        return $item;
    }

    /**
     * Method to get the record form.
     *
     * @param   array      $data        Data for the form.
     * @param   boolean    $loadData    True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed  A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {

        $form = $this->loadForm('com_eventbrite.eventbrite', 'eventbrite', array('control' => 'jform', 'load_data' => $loadData));



        if (empty($form))
        {
            return false;
        }

        $jinput = JFactory::getApplication()->input;

        $id = $jinput->get('id', 0);

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     * @since   1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();

        $data = $app->getUserState('com_eventbrite.edit.eventbrite.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }

        $this->preprocessData('com_eventbrite.eventbrite', $data);

        return $data;
    }

    /**
     * Prepare and sanitise the table data prior to saving.
     *
     * @param   JTable    A JTable object.
     *
     * @return  void
     * @since   1.6
     */
    protected function prepareTable($table)
    {
        // Set the publish date to now
        $db = $this->getDbo();

        // Increment the content version number.
        $table->version++;
    }

}