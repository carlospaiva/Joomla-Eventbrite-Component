<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/14/14
 */

defined('_JEXEC') or die;

?>

<ul>
    <?php foreach($this->items as $item): ?>
        <li>
            <a href="<?php echo JRoute::_('index.php?option=com_eventbrite&view=eventbrite&id=' . $item->id); ?>">
                <?php echo $item->title; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
