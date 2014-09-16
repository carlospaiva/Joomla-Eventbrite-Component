<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/15/14
 */

defined('_JEXEC') or die;

/**
 * View to edit an article.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_content
 * @since       1.6
 */
class EventbriteViewAjaxevents extends JViewLegacy
{
    protected $item;

    function display($tpl = null)
    {
        $this->item = $this->get('Item');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        parent::display($tpl);
    }
}