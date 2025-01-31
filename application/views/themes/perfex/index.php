<?php defined('BASEPATH') or exit('No direct script access allowed');
echo theme_head_view();
?>
<?= get_template_part($navigationEnabled ? 'navigation' : ''); ?>
<style>
    .my-nav-ul{
    display: flex;
    flex-direction: column;
    padding-top:80px
    }
    .navbar{
        display:none;
    }
@media (min-width: 1024px) {
    .table-responsive{
        width: 100% !important;
        margin-bottom: 15px !important;
        overflow-y: hidden !important;
    }
    .side-cont {
        position: fixed;  /* Fixed position to stick the sidebar to the left */
        top: 0;
        bottom: 0;
        left: 0;
        width: 250px; /* Adjust the width as needed */
        background-color: #1e293b; /* Example background color */
        overflow-y: auto; /* Scrollable content if it overflows vertically */
    }
    .navbar-nav .active > a {
        background-color: #007bff; /* Active link color */
        border-radius: 5px;
        color: white; /* Text color when active */
    }
    .side-nav-item:hover {
        border-radius: 5px;
        background-color: #007bff !important; /* Active link color */
        color: white !important;  /* Text color when active */
    }
}
@media (max-width: 800px) {
    .navbar {
        background-color: #1e293b; /* Example background color */
        display: block;  /* Display the navigation on small screens */
    }
    .side-cont{
        display: none;
    }
    .table-responsive{
        width: 100% !important;
        margin-bottom: 15px !important;
        overflow-y: hidden !important;
    }
}
</style>
<div id="wrapper">
    <div id="content">
        <div class="container">
            <div class="row">
                <?php get_template_part('alerts'); ?>
            </div>
        </div>
        <?php if (isset($knowledge_base_search)) { ?>
        <?php get_template_part('knowledge_base/search'); ?>
        <?php } ?>
        <div class="container-fluid">
            <?php hooks()->do_action('customers_content_container_start'); ?>
            <div class="row">
                <div id="vsidebar" class="col-md-3 col-lg-2">
                    <!-- Sidebar -->
                    <?= get_template_part($navigationEnabled ? 'sidebar' : ''); ?>
                </div>
                <div id="varea" class=" col-sm-12 col-md-9 col-lg-10">
                    <?php
                    if (is_client_logged_in() && $subMenuEnabled && !isset($knowledge_base_search)) { ?>
                    <ul class="submenu customer-top-submenu">
                        <?php hooks()->do_action('before_customers_area_sub_menu_start'); ?>
                        <li class="customers-top-submenu-files">
                            <a href="<?php echo site_url('clients/files'); ?>" class="tw-inline-flex tw-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                                </svg>
                                <span>
                                    <?php echo _l('customer_profile_files'); ?>
                                </span>
                            </a>
                        </li>
                        <li class="customers-top-submenu-calendar">
                            <a href="<?php echo site_url('clients/calendar'); ?>"
                                class="tw-inline-flex tw-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                                </svg>
                                <span>
                                    <?php echo _l('calendar'); ?>
                                </span>
                            </a>
                        </li>
                        <?php hooks()->do_action('after_customers_area_sub_menu_end'); ?>
                    </ul>
                    <div class="clearfix"></div>
                    <?php } ?>
                    <?php echo theme_template_view(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
    echo theme_footer_view();

    /* Always have app_customers_footer() just before the closing </body>  */
    app_customers_footer();
    /**
    * Check for any alerts stored in session
    */
    app_js_alerts();
?>
</body>

</html>