<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/14/14
 */

defined('_JEXEC') or die;

$params = JComponentHelper::getParams('com_eventbrite');

$title = JFactory::getApplication()->getMenu()->getActive()->title;

?>

<h3 class="catItemTitle"><?php echo $title; ?></h3>

<?php echo $params->get('pretext'); ?>

<?php if(count($this->items)): ?>
    <ul>
        <?php foreach($this->items as $item): ?>
            <li>
                <a href="<?php echo JRoute::_('index.php?option=com_eventbrite&view=eventbrite&id=' . $item->id); ?>">
                    <?php echo $item->title; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="alert alert-info">
        <p>No events found!</p>
    </div>
<?php endif; ?>

<?php echo $params->get('posttext'); ?>