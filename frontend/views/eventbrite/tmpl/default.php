<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/14/14
 */

defined('_JEXEC') or die;

$registry = $this->item->eventbrite_ids;

var_dump($registry->toArray);

?>

<h3><?php echo $this->item->title; ?></h3>

<?php echo $this->item->description; ?>

<?php foreach($registry as $registry_item):; ?>
    <?php var_dump($registry_item); ?>
<?php endforeach; ?>