<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php include_once(APPPATH . 'views/admin/includes/modals/post_likes.php'); ?>
<?php include_once(APPPATH . 'views/admin/includes/modals/post_comment_likes.php'); ?>
<div id="event"></div>
<div id="newsfeed" class="animated fadeIn hide" <?php if($this->session->flashdata('newsfeed_auto')){echo 'data-newsfeed-auto';} ?>>
</div>
<!-- Task modal view -->
<div class="modal fade task-modal-single" id="task-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog <?php echo get_option('task_modal_class'); ?>">
    <div class="modal-content data">

    </div>
  </div>
</div>

<!--Add/edit task modal-->
<div id="_task"></div>

<!-- Lead Data Add/Edit-->
<div class="modal fade lead-modal" id="lead-modal" tabindex="-1" role="dialog" data-id="V" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog <?php echo get_option('lead_modal_class'); ?>">
    <div class="modal-content data">

    </div>
  </div>
</div>

<div id="timers-logout-template-warning" class="hide">
  <h3 class="bold"><?php echo _l('timers_started_confirm_logout'); ?></h3>
  <hr />
  <a href="<?php echo admin_url('authentication/logout'); ?>" class="btn btn-danger">
    <?php echo _l('confirm_logout'); ?>
  </a>
</div>

<!--Lead convert to customer modal-->
<div id="lead_convert_to_customer"></div>

<!--Lead reminder modal-->
<div id="lead_reminder_modal"></div>
<style>
#leads_filter{ display:none !important;}
</style>

<?php 
if (!is_client_logged_in() && !is_admin() && is_staff_logged_in()) {
//echo "staff"; echo get_staff_user_id(); echo get_staff_full_name(get_staff_user_id());

?>
<style>
#header {background: #142ca9 !important;}
.sidebar {background: #142ca9 !important;}
.navbar-nav>li>a {color: #fffff4  !important;}
.header-notifications .tw-text-neutral-900 { color: #fffff4  !important;}	
.nav .tw-bg-primary-600 { background-color: rgb(9 93 251)  !important;}
.sidebar li a {font-weight:600 !important;}
.tw-group:hover .group-hover\:\!tw-bg-primary-700 { background-color: rgb(36 51 78)  !important;}


</style>
<?php
}else{
	
//echo "Not staff";
}

 ?>