<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/7/14
 */

defined('_JEXEC') or die;

/**
 * View to edit an article.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_content
 * @since       1.6
 */
class EventbriteViewEventbrite extends JViewLegacy
{
    protected $form;

    protected $item;

    protected $state;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $this->form		= $this->get('Form');
        $this->item		= $this->get('Item');
        $this->state	= $this->get('State');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        $user		= JFactory::getUser();
        $userId		= $user->get('id');
        $isNew		= ($this->item->id == 0);

        JToolbarHelper::title(JText::_('COM_EVENTBRITE_ITEM_EDIT'));

        // For new records, check the create permission.
        if ($isNew && (count($user->getAuthorisedCategories('com_eventbrite', 'core.create')) > 0))
        {
            JToolbarHelper::apply('eventbrite.apply');
            JToolbarHelper::save('eventbrite.save');
            JToolbarHelper::save2new('eventbrite.save2new');
            JToolbarHelper::cancel('eventbrite.cancel');
        }
        else
        {
            JToolbarHelper::apply('eventbrite.apply');
            JToolbarHelper::save('eventbrite.save');
            JToolbarHelper::cancel('eventbrite.cancel', 'JTOOLBAR_CLOSE');
        }
    }
}