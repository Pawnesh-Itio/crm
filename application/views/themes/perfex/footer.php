<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
@media (max-width: 1024px) {
    .side-cont {
        display: none !important;
    }
	.navbar {
        background-color: #1e293b;
        display: block;
    }
}
</style>
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <span class="copyright-footer"><?php echo date('Y'); ?>
                    <?php echo e(_l('clients_copyright', get_option('companyname'))); ?>
                </span>
                <?php if (is_gdpr() && get_option('gdpr_show_terms_and_conditions_in_footer') == '1') { ?>
                - <a href="<?php echo terms_url(); ?>" class="terms-and-conditions-footer">
                    <?php echo _l('terms_and_conditions'); ?>
                </a>
                <?php } ?>
                <?php if (is_gdpr() && is_client_logged_in() && get_option('show_gdpr_link_in_footer') == '1') { ?>
                - <a href="<?php echo site_url('clients/gdpr'); ?>" class="gdpr-footer">
                    <?php echo _l('gdpr_short'); ?>
                </a>
                <?php } ?>
            </div>
        </div>
    </div>
</footer>
<?php
$page_name = basename($_SERVER['REQUEST_URI']);
if($page_name=="login" || $page_name=="forgot_password"){ 
?>
<script> 
$('#vsidebar').hide();
$('#varea').removeClass("col-sm-12 col-md-9 col-lg-10");
$('#varea').addClass("col-sm-12 col-md-12 col-lg-12");
//alert("Hide");
</script>

<?php
}

?>