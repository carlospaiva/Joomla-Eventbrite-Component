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
                        <div class="clearfix"></div>
                        <h3>Ticket List</h3>

                        <div class="control-group">
                            <input class="search-events" type="text" class="input-small" placeholder="Enter search" />
                            <input type="button" class="btn btn-small" value="Go" id="submit-search" />
                            <img src="<?php echo JUri::root(); ?>/media/com_eventbrite/images/loader.gif" width="50px" class="loader" />
                        </div>
                        <table id="event-list" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Venue Name</th>
                                    <th>EventID</th>
                                    <th><a href="javascript:void(0)" id="select">Select</a> / <a href="javascript:void(0);" id="deselect">Deselect</a></th>
                                    <th>Link</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>


                    </div>
                    <div class="span3">
                        <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>

                        <?php // echo $this->form->getInput('eventbrite_ids'); ?>
                    </div>
                </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        <input type="hidden" name="eid" id="eid" value="<?php echo $this->item->id; ?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>