<?php
/**
 * @Author  Chad Windnagle
 * @Project eventbrite
 * Date: 9/7/14
 */

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$input = $app->input;

?>

<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'eventbrite.cancel' || document.formvalidator.isValid(document.id('item-form')))
        {
            <?php  echo $this->form->getField('description')->save(); ?>
            Joomla.submitform(task, document.getElementById('item-form'));
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_eventbrite&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
    <?php  echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

    <div class="form-horizontal">
		<?php  echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_EVENTBRITE_ITEM', true)); ?>
                <div class="row-fluid">
                    <div class="span9">
                        <fieldset class="adminform">
                            <?php echo $this->form->getInput('description'); ?>
                        </fieldset>
                    </div>
                    <div class="span3">
                        <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
                    </div>
                </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>