<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Project' Assigned Users -->

<div class="panel_s">
	<div class="panel-body">
		<div class="tasks-table panel-table-full">
			<!-- Create a table with the class 'dt-table' for displaying staff information -->
			<table class="table dt-table">
				<thead>
					<tr>
						<!-- Column headers for staff name, email, role, and last login -->
						<th><?php echo _l('staff_dt_name'); ?></th>
						<th><?php echo _l('staff_dt_email'); ?></th>
						<th><?php echo _l('role'); ?></th>
						<th><?php echo _l('last_login'); ?></th>
					</tr>
				</thead>
				<tbody>
					<!-- Loop through each member of the staff and display their details -->
					<?php foreach ($members as $c_admin) { ?>
					<tr>
						<td>
							<!-- Display staff profile link with profile image and full name -->
							<a href="<?php echo admin_url('profile/' . $c_admin['staff_id']); ?>">
								<?php
								// Display the staff profile image and name
								echo staff_profile_image($c_admin['staff_id'], [
									'staff-profile-image-small',
									'mright5',
								]);
								// Display the full name of the staff member
								echo e(get_staff_full_name($c_admin['staff_id']));
								?>
							</a>
						</td>
						<td data-order="<?php echo e($c_admin['email']); ?>">
							<!-- Display the staff email -->
							<?php echo e($c_admin['email']); ?>
						</td>
						<td>
							<?php
							// Check if the staff is an admin or display their role
							if (e($c_admin['admin']) == 1) {
								echo 'Admin'; // If admin, display "Admin"
							} else {
								// Otherwise, display the staff role name
								echo e(get_staff_role_name($c_admin['role']));
							}
							?>
						</td>
						<td>
							<?php
							// Check if last login is available and display it in a human-readable format
							if (isset($c_admin['last_login']) && $c_admin['last_login'] != null) {
								echo '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . e($c_admin['last_login']) . '">';
								// Display time ago function for last login
								echo time_ago($c_admin['last_login']);
								echo '</span>';
							} else {
								echo 'Never'; // If no login, display 'Never'
							}
							?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>