<?php defined('BASEPATH') or exit('No direct script access allowed');

// Loop through each milestone in the $milestones array
foreach ($milestones as $milestone) {
    // Initialize an array for filtering tasks related to this milestone
    $milestonesTasksWhere = [];
    
    // If completed tasks should be excluded, add a filter condition
    if ($milestones_exclude_completed_tasks) {
        $milestonesTasksWhere['status !='] = Tasks_model::STATUS_COMPLETE;
    }

    // Variable to hold color picker HTML for milestone tasks
    $cpicker = '';

    // Check if the current user has permission to edit milestones and if the milestone id is not 0
    if (staff_can('edit_milestones', 'projects') && $milestone['id'] != 0) {
        // Generate color picker HTML based on system favorite colors
        foreach (get_system_favourite_colors() as $color) {
            $color_selected_class = 'cpicker-small';
            $cpicker .= "<div class='kanban-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ';border:1px solid ' . $color . "'></div>";
        }
    }

    // Initialize the variable for milestone color
    $milestone_color = '';

    // Set the milestone color if it's provided and not null
    if (!empty($milestone['color']) && !is_null($milestone['color'])) {
        $milestone_color = ' style="background:' . $milestone['color'] . ';border:1px solid ' . $milestone['color'] . '"';
    }

    // Calculate the total number of pages required to display all tasks for this milestone
    $total_pages = ceil($this->projects_model->do_milestones_kanban_query($milestone['id'], $project_id, 1, $milestonesTasksWhere, true) / get_option('tasks_kanban_limit'));

    // Get all tasks related to this milestone
    $tasks       = $this->projects_model->do_milestones_kanban_query($milestone['id'], $project_id, 1, $milestonesTasksWhere);
    $total_tasks = count($tasks);

    // If the milestone has no tasks and its id is 0, skip this iteration
    if ($milestone['id'] == 0 && count($tasks) == 0) {
        continue;
    }
    ?>

	<!-- Kanban column for the milestone -->
	<ul class="kan-ban-col milestone-column<?php if (staff_cant('edit_milestones', 'projects') || $milestone['id'] == 0) {
        echo ' milestone-not-sortable'; // Disable sorting if user can't edit milestones or milestone id is 0
    }; ?>" data-col-status-id="<?php echo e($milestone['id']); ?>" data-total-pages="<?php echo e($total_pages); ?>">
    <li class="kan-ban-col-wrapper">
		<?php /*?><h4><?php echo e($milestone['name']); ?></h4><?php */?><!-- Display milestone name -->
        <div class="border-right panel_s">
            <div class="panel-heading <?php if ($milestone_color != '') {
        echo 'color-not-auto-adjusted color-white '; // Apply custom color if set
    } ?><?php if ($milestone['id'] != 0) {
        echo 'task-phase'; // Apply "task-phase" class if milestone ID is not 0
    } else {
        echo 'bg-info'; // Apply "bg-info" class if milestone ID is 0
    } ?>" <?php echo $milestone_color; ?>>

			 <!-- Option to edit the milestone details (only if user has permission and milestone id is not 0) -->
                <?php if ($milestone['id'] != 0 && staff_can('edit_milestones', 'projects')) { ?>
                <!--<i class="fa fa-reorder pointer"></i>&nbsp; -->
                <?php } ?>

				<!-- Link to open milestone edit modal -->
				<?php if ($milestone['id'] != 0 && staff_can('edit_milestones', 'projects')) { ?>
                <a href="#" data-hide-from-customer="<?php echo e($milestone['hide_from_customer']); ?>"
                    data-description-visible-to-customer="<?php echo e($milestone['description_visible_to_customer']); ?>"
                    data-description="<?php echo $milestone['description'] ? htmlspecialchars(clear_textarea_breaks($milestone['description'])) : ''; ?>"
                    data-name="<?php echo e($milestone['name']); ?>"
                    data-start_date="<?php echo $milestone['id'] != 0 ? _d($milestone['start_date']) : ''; ?>"
                    data-due_date="<?php echo $milestone['id'] != 0 ? _d($milestone['due_date']) : ''; ?>"
                    data-order="<?php echo e($milestone['milestone_order']); ?>"
                    onclick="edit_milestone(this,<?php echo e($milestone['id']); ?>); return false;" class="edit-milestone-phase <?php if ($milestone['color'] != '') {
        echo 'color-white';
    } ?>">
                    <?php } ?>
                    <span class="bold heading"><h3><?php echo e($milestone['name']); ?></h3></span>
                    <?php /*?><span class="tw-text-sm">
                        <?php echo  $milestone['id'] != 0 ? (' | ' . _d($milestone['start_date']) . ' - ' . _d($milestone['due_date'])) : ''; ?>
                    </span><?php */?>
                    <?php if ($milestone['id'] != 0 && staff_can('edit_milestones', 'projects')) { ?>
                </a>
                <?php } ?>

				<!-- Milestone options dropdown (new task, change color) -->
                <?php if ($milestone['id'] != 0 && (staff_can('create', 'tasks') || staff_can('edit_milestones', 'projects'))) { ?>
                <a href="#" onclick="return false;" class="pull-right text-dark" data-placement="bottom"
                    data-toggle="popover" data-content="
      <div class='text-center'><?php if (staff_can('create', 'tasks')) {
        ?><button type='button' return false;' class='btn btn-success btn-block mtop10 new-task-to-milestone'>
         <?php echo _l('new_task'); ?>
       </button>
     <?php
    } ?>
		</div>	
	</div>
   <?php if (staff_can('edit_milestones', 'projects')) { ?>
   <?php if ($cpicker != '') {
        echo '<hr />';
    }; ?>
   <div class='kan-ban-settings cpicker-wrapper'>
     <?php echo e($cpicker); ?>
   </div>
   <a href='#' class='tw-block reset_milestone_color <?php if ($milestone_color == '') {
        echo 'hide';
    } ?>' data-color=''>
     <?php echo _l('reset_to_default_color'); ?>
   </a>
   <?php } ?>" data-html="true" data-trigger="focus">
                    <i class="fa fa-angle-down"></i>
                </a>
                <?php } ?>
				
                <!-- Display milestone total logged time -->
				<?php if (staff_can('create', 'tasks')) { ?>
                <?php echo '<p class="tw-text-sm tw-mb-0'.($milestone['id'] !== 0 ? ' tw-ml-5' : '').'">' /*. e(_l('milestone_total_logged_time') . ': ' . seconds_to_time_format($milestone['total_logged_time'])) .*/. '&nbsp;</p>'; } ?>
            </div>
			
			<!-- Display tasks associated with this milestone -->
            <div class="kan-ban-content-wrapper" style="min-height:400px !important;">
                <div class="kan-ban-content">
                    <ul class="status project-milestone milestone-tasks-wrapper sortable relative"
                        data-task-status-id="<?php echo e($milestone['id']); ?>">
                        <?php
						// Loop through each task and load its kanban card view
						foreach ($tasks as $task) {
							$this->load->view('admin/projects/_milestone_kanban_card', ['task' => $task, 'milestone' => $milestone['id']]);
						} ?>
						
						<!-- Button to create a new task -->
                        <li class="text-center not-sortable"><button type='button' class='btn btn-success btn-block mtop10 new-task-to-short' return false; ><?php echo _l('new_task'); ?></button></li>

                        <!-- Option to load more tasks if there are more tasks available -->
						<?php if ($total_tasks > 5) { ?>
                        <li class="text-center mtop10 not-sortable kanban-load-more"
                            data-load-status="<?php echo e($milestone['id']); ?>">
                            <a href="#" class="btn btn-default btn-block<?php if ($total_pages <= 1) {
        echo ' disabled';
    } ?>" data-page="1" onclick="kanban_load_more(<?php echo e($milestone['id']); ?>,this,'projects/milestones_kanban_load_more',320,360); return false;"
                                ;>
                                <?php echo _l('load_more'); ?>
                            </a>
                        </li>
                        <?php } ?>
						
                        <!-- Display message if no tasks found for this milestone -->
						<li class="text-center not-sortable mtop30 kanban-empty<?php if ($total_tasks > 0) {
							echo ' hide';
						} ?>">
                            <h4>
                                <i class="fa-solid fa-circle-notch" aria-hidden="true"></i><br /><br />
                                <?php echo _l('no_tasks_found'); ?>
                            </h4>
                        </li>
                    </ul>
                </div>
            </div>
    </li>
</ul>
<?php } ?>