<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/14/14
 */

defined('_JEXEC') or die;

$registry = $this->item->eventbrite_ids;

?>

<h3><?php echo $this->item->title; ?></h3>

<?php echo $this->item->description; ?>

<h3>Tickets</h3>
<table width="100%" id="event-list">
    <tr>
        <th>Event Name</th>
        <th>Tickets Available</th>
        <th>Price Range</th>
    </tr>
</table>
<img src="<?php echo JUri::root(); ?>/media/com_eventbrite/images/loader.gif" width="50px" class="loader" />
<p class="loader">Getting all available tickets for this event...hold tight!</p>