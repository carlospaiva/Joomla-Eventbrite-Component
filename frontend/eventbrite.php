<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/7/14
 */

// no direct access
defined('_JEXEC') or die;

// Access check.

$controller	= JControllerLegacy::getInstance('Eventbrite');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();