<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="mtop40">
    <div class="company-logo text-center">
            <?php get_dark_company_logo('', 'navbar-brand logo'); ?>       
			</div>
    <div class="col-md-4 col-md-offset-4">
        <div class="panel_s">
            <div class="panel-body">
			<h1 class="tw-font-semibold text-center mt-0 pt-0"><?php echo _l('customer_forgot_password_heading'); ?></h1>
                <?php echo form_open($this->uri->uri_string(), ['id' => 'forgot-password-form']); ?>
                <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                <?php if ($this->session->flashdata('message-danger')) { ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('message-danger'); ?>
                </div>
                <?php } ?>
                <?php echo render_input('email', 'customer_forgot_password_email', '', 'email'); ?>
                <div class="form-group">
                    <button type="submit"
                        class="btn btn-primary btn-block"><?php echo _l('customer_forgot_password_submit'); ?></button>
                </div>
				<a href="<?php echo site_url('authentication/login'); ?>">
                    Back to Sign In
                </a>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
