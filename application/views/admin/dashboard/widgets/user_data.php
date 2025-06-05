<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('user_widget'); ?>">
    <div class="panel_s user-data">
        <div class="panel-body home-activity">
            <div class="widget-dragger"></div>
            <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                <div class="scroller scroller-left arrow-left"><i class="fa fa-angle-left"></i></div>
                <div class="scroller scroller-right arrow-right"><i class="fa fa-angle-right"></i></div>
                <div class="horizontal-tabs" id="global">
                    <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
					<?php if (is_admin()) { ?>
                        <li role="presentation" class="active">
                            <a href="#home_tab_activity" aria-controls="home_tab_activity" role="tab" data-toggle="tab">
                                <i class="fa fa-window-maximize menu-icon"></i>
                                <?php echo _l('home_latest_activity'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li role="presentation" >
                            <a href="#home_tab_tasks" aria-controls="home_tab_tasks" role="tab" data-toggle="tab">
                                <i class="fa fa-tasks menu-icon"></i> <?php echo _l('home_my_tasks'); ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#home_notes" 
                                aria-controls="home_notes" role="tab" data-toggle="tab">
                                <i class="fa-solid fa-file-pen menu-icon"></i> Notes
								 <?php if ($notes != 0) {
                            echo '<span class="badge">' . count($notes) . '</span>';
                        } ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#home_my_reminders"
                                onclick="initDataTable('.table-my-reminders', admin_url + 'misc/my_reminders', undefined, undefined,undefined,[2,'asc']);"
                                aria-controls="home_my_reminders" role="tab" data-toggle="tab">
                                <i class="fa-regular fa-clock menu-icon"></i> <?php echo _l('my_reminders'); ?>
                                <?php
                        $total_reminders = total_rows(
    db_prefix() . 'reminders',
    [
                           'isnotified' => 0,
                           'staff'      => get_staff_user_id(),
                        ]
);
                        if ($total_reminders > 0) {
                            echo '<span class="badge">' . $total_reminders . '</span>';
                        }
                        ?>
                            </a>
                        </li>
                        <?php if ((get_option('access_tickets_to_none_staff_members') == 1 && !is_staff_member()) || is_staff_member()) { ?>
                        <li role="presentation">
                            <a href="#home_tab_tickets" onclick="init_table_tickets(true);"
                                aria-controls="home_tab_tickets" role="tab" data-toggle="tab">
                                <i class="fa-regular fa-life-ring menu-icon"></i> <?php echo _l('home_tickets'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <?php if (is_staff_member()) { ?>
                        <li role="presentation">
                            <a href="#home_todolist"
                                aria-controls="home_todolist" role="tab" data-toggle="tab">
                                <i class="fa-solid fa-rectangle-list menu-icon"></i> To Do List
                                <?php if ($deal_task != 0) {
                            echo '<span class="badge">' . count($deal_task) . '</span>';
                        } ?>
                            </a>
                        </li>
                        <?php } ?>
                        
                        <?php hooks()->do_action('after_user_data_widget_tabs'); ?>
                    </ul>
                </div>
            </div>
            <div class="tab-content tw-mt-5">
                <div role="tabpanel" class="tab-pane" id="home_tab_tasks">
                    <a href="<?php echo admin_url('tasks/list_tasks'); ?>"
                        class="mbot20 inline-block full-width"><?php echo _l('home_widget_view_all'); ?></a>
                    <div class="clearfix"></div>
                    <div class="_hidden_inputs _filters _tasks_filters">
                        <?php echo form_hidden('my_tasks', true); ?>
                    </div>
                    <?php $this->load->view('admin/tasks/_table'); ?>
                </div>
                <?php if ((get_option('access_tickets_to_none_staff_members') == 1 && !is_staff_member()) || is_staff_member()) { ?>
                <div role="tabpanel" class="tab-pane" id="home_tab_tickets">
                    <a href="<?php echo admin_url('tickets'); ?>"
                        class="mbot20 inline-block full-width"><?php echo _l('home_widget_view_all'); ?></a>
                    <div class="clearfix"></div>
                    <div class="_filters _hidden_inputs hidden tickets_filters">
                        <?php
                           // On home only show on hold, open and in progress
                           echo form_hidden('ticket_status_1', true);
                           echo form_hidden('ticket_status_2', true);
                           echo form_hidden('ticket_status_4', true);
                           ?>
                    </div>
                    <?php echo AdminTicketsTableStructure(); ?>
                </div>
                <?php } ?>
                <div role="tabpanel" class="tab-pane" id="home_notes">
                   <?php
					$len	= count($notes);
					$i		= 0;
					foreach ($notes as $note) { ?>
					<div class="media lead-note">
						<a href="<?php echo admin_url('profile/' . $note['addedfrom']); ?>" target="_blank">
							<?php echo staff_profile_image($note['addedfrom'], ['staff-profile-image-small', 'pull-left mright10']); ?>
						</a>
						<div class="media-body">
							
	
							<a href="<?php echo admin_url('profile/' . $note['addedfrom']); ?>" target="_blank">
								<h5 class="media-heading tw-font-semibold tw-mb-0">
								<?php if (!empty($note['date_contacted'])) { ?>
									<span data-toggle="tooltip"
										data-title="<?php echo e(_dt($note['date_contacted'])); ?>">
										<i class="fa fa-phone-square text-success" aria-hidden="true"></i>
									</span>
									<?php } ?>
									<?php echo e(get_staff_full_name($note['addedfrom'])); ?>
									</h5>
									<span class="tw-text-sm tw-text-neutral-500">
										<?php echo e(_l('lead_note_date_added', _dt($note['dateadded']))); ?>
									</span>
							</a>

							<div data-note-description="<?php echo e($note['id']); ?>" class="text-muted mtop10"><?php echo process_text_content_for_display($note['description']); ?></div>
							<div data-note-edit-textarea="<?php echo e($note['id']); ?>" class="hide mtop15">
								<?php echo render_textarea('note', '', $note['description']); ?>
								<div class="text-right">
									<button type="button" class="btn btn-default"
										onclick="toggle_edit_note(<?php echo e($note['id']); ?>);return false;"><?php echo _l('cancel'); ?></button>
									<button type="button" class="btn btn-primary"
										onclick="edit_note(<?php echo e($note['id']); ?>);"><?php echo _l('update_note'); ?></button>
								</div>
							</div>
						</div>
						<?php if ($i >= 0 && $i != $len - 1) {
							echo '<hr />';
						}
						?>
					</div>
					<?php $i++; } ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="home_my_reminders">
                    <a href="<?php echo admin_url('misc/reminders'); ?>" class="mbot20 inline-block full-width">
                        <?php echo _l('home_widget_view_all'); ?>
                    </a>
                    <?php render_datatable([
                        _l('reminder_related'),
                        _l('reminder_description'),
                        _l('reminder_date'),
                        ], 'my-reminders'); ?>
                </div>
                <?php if (is_staff_member()) { ?>
                <div role="tabpanel" class="tab-pane" id="home_todolist">
                    <?php if (is_admin()) { ?>
                    <div class="activity-feed" style="max-height: 400px; overflow-y: auto;">
					<?php
					$len	= count($deal_task);
					$i		= 0;
					foreach ($deal_task as $task) { ?>
					<?php $tasktype=$this->leads_model->get_task_type($task['task_type']); ?>
<?php 
$task_bg="";					
$assignDateTime = new DateTime($task['date']);
$currentDateTime = new DateTime();
if ($currentDateTime > $assignDateTime) {
    $task_bg="#ef4444";
} 
?>
					<div class="media8 lead-note feed-item " >
						<a href="<?php echo admin_url('profile/' . $task['staff']); ?>" target="_blank">
							<?php echo staff_profile_image($task['staff'], ['staff-profile-image-small', 'pull-left mright10']); ?>
						</a>
						<div class="media-body " >
							
	
							
<h5 class="media-heading tw-font-semibold tw-mb-0">

<?php if ($task['task_status']==0) { ?>								
<div class="btn-group pull-right mleft5">
<i class="fa-solid fa-circle-check text-warning fa-2x mright10 change_task_status" data-tid="<?php echo $task['id'];?>"></i>				
</div>
<?php }else{ ?>
<div class="btn-group pull-right mleft5">
<i class="fa-solid fa-circle-check text-success fa-2x mright10" ></i>				
</div>
<?php } ?>
								<?php if (!empty($task['date_contacted'])) { ?>
									<span data-toggle="tooltip"
										data-title="<?php echo e(_dt($task['date'])); ?>">
										<i class="fa fa-phone-square text-success" aria-hidden="true"></i>
									</span>
									<?php } ?>
									<?php echo e(get_staff_full_name($task['staff'])); ?> <?php if (isset($tasktype[0]['name']) && $tasktype[0]['color']) { ?> - <span style="color:<?php echo $tasktype[0]['color'];?>; font-weight:bolder;">Type : <?php echo $tasktype[0]['name'];?> <?php } ?> <?php if (isset($task['task_title']) && $task['task_title']) { echo " - (".$task['task_title'].")"; } ?></span>
									</h5>
<p><?php echo $task['description']; ?></p>
<span class="tw-text-sm tw-text-neutral-500" style="color:<?php echo $task_bg;?>"> To Do Time : <?php echo $task['date']; ?> To Do Added on : <?php echo $task['dateadded']; ?> </span>
									
									
							

							
							
						</div>
						<?php if ($i >= 0 && $i != $len - 1) {
							//echo '<hr />';
						}
						?>
					</div>
					<?php $i++; } ?>
				</div>
                    <?php } ?>
                    
                </div>
                <?php } ?>
                <?php if (is_admin()) { ?>
                <div role="tabpanel" class="tab-pane active" id="home_tab_activity">
                    <a href="<?php echo admin_url('utilities/activity_log'); ?>"
                        class="mbot20 inline-block full-width"><?php echo _l('home_widget_view_all'); ?></a>
                    <div class="clearfix"></div>
                    <div class="activity-feed">
                        <?php foreach ($activity_log as $log) { ?>
                        <div class="feed-item">
                            <div class="date">
                                <span class="text-has-action" data-toggle="tooltip"
                                    data-title="<?php echo e(_dt($log['date'])); ?>">
                                    <?php echo e(time_ago($log['date'])); ?>
                                </span>
                            </div>
                            <div class="text">
                                <?php echo e($log['staffid']); ?><br />
                                <?php echo e($log['description']); ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                <?php hooks()->do_action('after_user_data_widge_tabs_content'); ?>
            </div>
        </div>
    </div>
</div>