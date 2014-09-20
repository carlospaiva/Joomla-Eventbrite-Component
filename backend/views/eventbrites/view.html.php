<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/7/14
 */

defined('_JEXEC') or die;

/**
 * View class for a list of articles.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_content
 * @since       1.6
 */
class EventbriteViewEventbrites extends JViewLegacy
{
    protected $items;

    protected $pagination;

    protected $state;

    /**
     * Display the view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    public function display($tpl = null)
    {
        $this->items         = $this->get('Items');
        $this->pagination    = $this->get('Pagination');
        $this->state         = $this->get('State');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode("\n", $errors));

            return false;
        }

        $this->addToolbar();
        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        // Get the toolbar object instance
        $bar = JToolBar::getInstance('toolbar');

        JToolbarHelper::title(JText::_('COM_EVENTBRITE_EVENT_LIST'), 'stack article');


        // all our buttons
        JToolbarHelper::addNew('eventbrite.add');
        JToolbarHelper::editList('eventbrite.edit');
        JToolbarHelper::publish('eventbrite.publish', 'JTOOLBAR_PUBLISH', true);
        JToolbarHelper::unpublish('eventbrite.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolbarHelper::deleteList('', 'eventbrite.delete', 'JTOOLBAR_EMPTY_TRASH');
        JToolbarHelper::trash('eventbrite.trash');

        // pref pane
        JToolbarHelper::preferences('com_eventbrite');
    }

}