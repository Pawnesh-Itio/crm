<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Project Tasks -->

<div class="panel_s">
    <div class="panel-body">
        <div class="tasks-table panel-table-full">
		<? //echo '<pre>';print_r($members);?>
         
		 <table class="table dt-table">
			<thead>
				<tr>
					<th><?php echo _l('staff_dt_name'); ?></th>
					<th><?php echo _l('staff_dt_email'); ?></th>
					<th><?php echo _l('role'); ?></th>
					<th><?php echo _l('last_login'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($members as $c_admin) { ?>
				<tr>
					<td><a href="<?php echo admin_url('profile/' . $c_admin['staff_id']); ?>">
					<?php echo staff_profile_image($c_admin['staff_id'], [
				   'staff-profile-image-small',
				   'mright5',
				   ]);
				   echo e(get_staff_full_name($c_admin['staff_id'])); ?></a>
					</td>
					<td data-order="<?php echo e($c_admin['email']); ?>"><?php echo e($c_admin['email']); ?></td>
					<td><?php
						if(e($c_admin['admin'])==1) echo 'Admin';
						else echo e(get_staff_role_name($c_admin['role']));
					?>
					</td>
					<td>
					<?php
					if (isset($c_admin['last_login'])&&$c_admin['last_login'] != null) {
						echo '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . e($c_admin['last_login']) . '">' . time_ago($c_admin['last_login']) . '</span>';
						} else {
							echo 'Never';
						}
					?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
        </div>
    </div>
</div>