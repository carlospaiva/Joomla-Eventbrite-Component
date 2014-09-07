<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/7/14
 */

// no direct access
defined('_JEXEC') or die;

// Access check.
/*
if (!JFactory::getUser()->authorise('core.manage', 'com_eventbrite'))
{
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}*/

$controller	= JControllerLegacy::getInstance('Eventbrite');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();