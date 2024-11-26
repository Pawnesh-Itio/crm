<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="container-fluid side-cont">
    <!-- Sidebar -->
    <div class="sidenav">
        <!-- Sidebar links (collapsible) -->
        <div class="collapse navbar-collapse" id="theme-navbar-collapse">
        <div class="navbar-header">   
                        <?php get_dark_company_logo('', 'navbar-brand logo'); ?>
        </div>
            <ul class="nav navbar-nav my-nav-ul">
                <?php hooks()->do_action('customers_navigation_start'); ?>
                <?php if (is_client_logged_in()) { ?>
                    <li class="dropdown customers-nav-item-profile">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            <img src="<?php echo e(contact_profile_image_url($contact->id, 'thumb')); ?>"
                                 data-toggle="tooltip"
                                 data-title="<?php echo e($contact->firstname . ' ' . $contact->lastname); ?>"
                                 data-placement="bottom" class="client-profile-image-small">
                        </a>
                        <ul class="dropdown-menu animated fadeIn">
                            <li class="customers-nav-item-edit-profile ">
                                <a href="<?php echo site_url('clients/profile'); ?>">
                                    <?php echo _l('clients_nav_profile'); ?>
                                </a>
                            </li>
                            <?php if ($contact->is_primary == 1) { ?>
                                <?php if (can_loggged_in_user_manage_contacts()) { ?>
                                    <li class="customers-nav-item-edit-profile side-nav-item">
                                        <a href="<?php echo site_url('contacts'); ?>">
                                            <?php echo _l('clients_nav_contacts'); ?>
                                        </a>
                                    </li>
                                <?php } ?>
                                <li class="customers-nav-item-company-info side-nav-item">
                                    <a href="<?php echo site_url('clients/company'); ?>">
                                        <?php echo _l('client_company_info'); ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (can_logged_in_contact_update_credit_card()) { ?>
                                <li class="customers-nav-item-stripe-card side-nav-item">
                                    <a href="<?php echo site_url('clients/credit_card'); ?>">
                                        <?php echo _l('credit_card'); ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (is_gdpr() && get_option('show_gdpr_in_customers_menu') == '1') { ?>
                                <li class="customers-nav-item-announcements side-nav-item">
                                    <a href="<?php echo site_url('clients/gdpr'); ?>">
                                        <?php echo _l('gdpr_short'); ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="customers-nav-item-announcements side-nav-item">
                                <a href="<?php echo site_url('clients/announcements'); ?>">
                                    <?php echo _l('announcements'); ?>
                                    <?php if ($total_undismissed_announcements != 0) { ?>
                                        <span class="badge"><?php echo e($total_undismissed_announcements); ?></span>
                                    <?php } ?>
                                </a>
                            </li>
                            <?php if (!is_language_disabled()) { ?>
                                <li class="dropdown-submenu pull-left customers-nav-item-languages">
                                    <a href="#" tabindex="-1">
                                        <?php echo _l('language'); ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-left">
                                        <li class="side-nav-item <?php if (get_contact_language() == '') {
                                            echo 'active ';
                                        } ?>">
                                            <a href="<?php echo site_url('clients/change_language'); ?>">
                                                <?php echo _l('system_default_string'); ?>
                                            </a>
                                        </li>
                                        <?php foreach ($this->app->get_available_languages() as $user_lang) { ?>
                                            <li <?php if (get_contact_language() == $user_lang) {
                                                echo 'class="active side-nav-item"';
                                            }else{ 'class="side-nav-item"'; } ?>>
                                                <a href="<?php echo site_url('clients/change_language/' . $user_lang); ?>">
                                                    <?php echo e(ucfirst($user_lang)); ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php } ?>
                            <li class="customers-nav-item-logout">
                                <a href="<?php echo site_url('authentication/logout'); ?>">
                                    <?php echo _l('clients_nav_logout'); ?>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <li class="customers-nav-item-projects side-nav-item">
                    <a href="<?= base_url() ?>">
                        Dashboard
                    </a>
                </li>
                <?php hooks()->do_action('customers_navigation_after_profile'); ?>
                <?php foreach ($menu as $item_id => $item) { 
                    if($item['name'] != 'Contracts' && $item['name'] != 'Estimates' && $item['name'] != 'Proposals'  ){
                ?>
                    <li class="customers-nav-item-<?php echo e($item_id); ?><?php echo $item['href'] === current_full_url() ? ' active' : ''; ?> side-nav-item"
                        <?php echo _attributes_to_string(isset($item['li_attributes']) ? $item['li_attributes'] : []); ?>>
                        <a href="<?php echo e($item['href']); ?>"
                           <?php echo _attributes_to_string(isset($item['href_attributes']) ? $item['href_attributes'] : []); ?>>
                            <?php
                            if (!empty($item['icon'])) {
                                echo '<i class="' . $item['icon'] . '"></i> ';
                            }
                            echo e($item['name']);
                            ?>
                        </a>
                    </li>
                <?php } } ?>
                <?php hooks()->do_action('customers_navigation_end'); ?>
            </ul>
        </div>
    </div>
</div>
</style>