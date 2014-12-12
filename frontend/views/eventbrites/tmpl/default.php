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

<h2><?php echo $title; ?></h2>

<?php echo $params->get('pretext'); ?>

<?php if(count($this->items)): ?>
    <table class="table table-striped table-hover" id="events">
        <thead>
            <tr>
                <th>
                    Upcoming Winery Events
                </th>
                <th>Tickets & Details</th>
            </tr>
        </thead>
        <?php foreach($this->items as $item): ?>
            <tr>
                <td>
                    <h5>
                        <a href="<?php echo JRoute::_('index.php?option=com_eventbrite&view=eventbrite&id=' . $item->id); ?>">
                            <?php echo $item->title; ?>
                        </a>
                    </h5>
                </td>
                <td>
                    <a class="btn btn-info" href="<?php echo JRoute::_('index.php?option=com_eventbrite&view=eventbrite&id=' . $item->id); ?>">
                        View Details & Tickets
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <div class="alert alert-info">
        <p>No events found!</p>
    </div>
<?php endif; ?>

<?php echo $params->get('posttext'); ?>