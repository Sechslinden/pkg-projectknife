<?php
/**
 * @package      pkg_projectknife
 * @subpackage   com_pktasks
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2015-2017 Tobias Kuhn. All rights reserved.
 * @license      GNU General Public License version 2 or later.
 */

defined('_JEXEC') or die;


// Load Projectknife plugins
$dispatcher = JEventDispatcher::getInstance();
JPluginHelper::importPlugin('projectknife');

$model       = $this->getModel();
$params      = JComponentHelper::getParams('com_pktasks');
$auto_access = (int) $params->get('auto_access', 1);

// Prepare project options
$keep_project = new stdClass();
$keep_project->value = '';
$keep_project->text  = '- ' . JText::_('COM_PKPROJECTS_COPY_KEEP_PROJECT') . ' -';

$projects = array_merge(array($keep_project), $this->project_options);

// Prepare milestone options
$keep_milestone = new stdClass();
$keep_milestone->value = '';
$keep_milestone->text  = '- ' . JText::_('COM_PKMILESTONES_COPY_KEEP_MILESTONE') . ' -';

$milestones = array($keep_milestone);

// Prepare access level options
$keep_access = new stdClass();
$keep_access->value = '';
$keep_access->text  = '- ' . JText::_('PKGLOBAL_KEEP_ACCESS') . ' -';

$levels = array_merge(array($keep_access), $this->access_options);

// Prepare include options
$including = (array) $dispatcher->trigger('onProjectknifeCopyOptions', array('com_pktasks.tasks'));
?>
<div class="modal hide fade" id="copyDialog">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&#215;</button>
		<h3><?php echo JText::_('PKGLOBAL_COPY_OPTIONS'); ?></h3>
	</div>
	<div class="modal-body modal-batch">
        <div class="row-fluid form-horizontal">
            <div class="span6">
                <div class="control-group">
                    <div class="control-label">
                        <?php echo JText::_('COM_PKPROJECTS_PROJECT'); ?>
                    </div>
    				<div class="controls">
    					<?php
                        echo JHtml::_(
                            'select.genericlist',
                            $projects,
                            'copy[project_id]',
                            'onchange="'
                            . 'PKform.ajaxUpdateOptions('
                            . '\'#copymilestone_id\',true,'
                            . '\'index.php?option=com_pktasks&task=tasks.getMilestoneOptions&project_id=\' + jQuery(this).val()'
                            . ');"'
                        ); ?>
    				</div>
    			</div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo JText::_('COM_PKMILESTONES_MILESTONE'); ?>
                    </div>
    				<div class="controls">
    					<?php echo JHtml::_('select.genericlist', $milestones, 'copy[milestone_id]'); ?>
    				</div>
    			</div>
                <?php if(!$auto_access) : ?>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo JText::_('JGRID_HEADING_ACCESS'); ?>
                    </div>
    				<div class="controls">
                        <?php echo JHtml::_('select.genericlist', $levels, 'copy[access]'); ?>
    				</div>
    			</div>
                <?php endif; ?>
            </div>
            <div class="span6">
                <div class="control-group">
                    <?php
                    $txt_include = JText::_('PKGLOBAL_COPY_INCLUDING');
                    $txt_yes     = JText::_('JYES');
                    $txt_no      = JText::_('JNO');

                    $i = 0;

                    foreach ($including AS $component)
                    {
                        if (!is_array($component)) {
                            continue;
                        }

                        foreach ($component AS $opt)
                        {
                            if (!is_object($opt)) {
                                continue;
                            }
                            ?>
                            <div class="control-label">
                                <?php echo $txt_include . ': ' . $opt->text; ?>
                            </div>
                            <div class="controls">
                                <fieldset id="copy_inc_<?php echo $i; ?>_"class="radio btn-group btn-group-yesno">
                                    <input id="copy_inc_<?php echo $i; ?>_1" type="radio" checked="checked" value="1" name="copy[include][<?php echo $opt->value; ?>]"/>
                                    <label for="copy_inc_<?php echo $i; ?>_1"><?php echo $txt_yes; ?></label>
                                    <input id="copy_inc_<?php echo $i; ?>_0" type="radio" value="0" name="copy[include][<?php echo $opt->value; ?>]"/>
                                    <label for="copy_inc_<?php echo $i; ?>_0"><?php echo $txt_no; ?></label>
                                </fieldset>
                            </div>
                            <?php
                            $i++;
                        }
                    }
                    ?>
    			</div>
            </div>
		</div>
	</div>
    <div class="modal-footer">
		<button class="btn" type="button" data-dismiss="modal">
			<?php echo JText::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('tasks.copy');">
			<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>