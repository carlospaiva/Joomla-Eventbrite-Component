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

        parent::display($tpl);
    }
}