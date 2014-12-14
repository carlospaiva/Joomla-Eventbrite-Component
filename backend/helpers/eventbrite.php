<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 12/14/14
 */

defined('_JEXEC') or die;

class EventbriteHelper extends JHtmlSidebar
{
    public static $extension = 'com_eventbrite';

    public static function addSubmenu($vName)
    {
        // add cattags
        JHtmlSidebar::addEntry(JText::_('COM_EVENTBRITE_EVENTS'), 'index.php?option=com_eventbrite&view=eventbrites', $vName == 'eventbrites');
        // add categories
        JHtmlSidebar::addEntry(JText::_('categories'), 'index.php?option=com_categories&extension=com_eventbrite', $vName == 'categories');
    }
}