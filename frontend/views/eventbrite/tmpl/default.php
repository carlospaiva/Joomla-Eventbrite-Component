<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/14/14
 */

defined('_JEXEC') or die;

?>

<h3><?php echo $this->item->title; ?></h3>

<?php echo $this->item->description; ?>

<h3><?php echo JText::_('COM_EVENTBRITE_ITEM_EDIT_TICKETS_HEADER'); ?></h3>
<table width="100%" id="event-list" class="table table-striped table-hover">
    <tr>
        <th><?php echo JText::_('COM_EVENTBRITE_ITEM_EDIT_TABLE_HEADER_EVENT_NAME'); ?></th>
        <th><?php echo JText::_('COM_EVENTBRITE_ITEM_EDIT_TABLE_HEADER_TICKETS_AVAILABLE'); ?></th>
        <th><?php echo JText::_('COM_EVENTBRITE_ITEM_EDIT_TABLE_HEADER_PRICE_RANGE'); ?></th>
    </tr>
</table>
<img src="<?php echo JUri::root(); ?>/media/com_eventbrite/images/loader.gif" width="50px" class="loader" />
<p class="loader"><?php echo JText::_('COM_EVENTBRITE_ITEM_EDIT_LOADER_TEXT'); ?></p>

<input type="hidden" value="<?php echo $this->item->id; ?>" name="id" id="eid" />

<a href="<?php echo JRoute::_('index.php?option=com_eventbrite&view=eventbrites'); ?>" class="btn btn-primary">
    <?php echo JText::_('COM_EVENTBRITE_BACK_TO_LIST'); ?>
</a>