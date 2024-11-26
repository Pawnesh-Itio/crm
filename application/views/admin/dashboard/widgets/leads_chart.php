<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget<?php if (!is_staff_member()) {
    echo ' hide';
} ?>" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo e(_l('s_chart', _l('leads'))); ?>">
    <?php if (is_staff_member()) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel_s">
                <div class="panel-body padding-10">
                    <div class="widget-dragger"></div>
                    <p
                        class="tw-font-medium tw-flex tw-items-center tw-mb-0 tw-space-x-1.5 rtl:tw-space-x-reverse tw-p-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="tw-w-6 tw-h-6 tw-text-neutral-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>

                        <span class="tw-text-neutral-700">
                            <?php echo _l('home_lead_overview'); ?>
                        </span>
                    </p>

                    <hr class="-tw-mx-3 tw-mt-3 tw-mb-6">

                    <div class="relative" style="height:250px">


                        <?php
                            $table_data  = [];
                            $_table_data = [
                            '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="leads"><label></label></div>',
                            [
                            'name'     => _l('the_number_sign'),
                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-number'],
                            ],
                            [
                            'name'     => _l('leads_dt_name'),
                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-name'],
                            ],
                            ];
                            if (is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1') {
                                $_table_data[] = [
                                'name'     => _l('gdpr_consent') . ' (' . _l('gdpr_short') . ')',
                                'th_attrs' => ['id' => 'th-consent', 'class' => 'not-export'],
                            ];
                            }
                            $_table_data[] = [
                            'name'     => _l('lead_company'),
                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-company'],
                            ];
                            $_table_data[] = [
                            'name'     => _l('leads_dt_email'),
                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-email'],
                            ];
                            $_table_data[] = [
                            'name'     => _l('leads_dt_phonenumber'),
                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-phone'],
                            ];
                            $_table_data[] = [
                            'name'     => _l('leads_dt_lead_value'),
                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-lead-value'],
                            ];
                            $_table_data[] = [
                            'name'     => _l('tags'),
                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-tags'],
                            ];
                            $_table_data[] = [
                            'name'     => _l('leads_dt_assigned'),
                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-assigned'],
                            ];
                            $_table_data[] = [
                            'name'     => _l('leads_dt_status'),
                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-status'],
                            ];
                            $_table_data[] = [
                            'name'     => _l('leads_source'),
                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-source'],
                            ];
                            $_table_data[] = [
                            'name'     => _l('leads_dt_last_contact'),
                            'th_attrs' => ['class' => 'toggleable', 'id' => 'th-last-contact'],
                            ];
                            $_table_data[] = [
                            'name'     => _l('leads_dt_datecreated'),
                            'th_attrs' => ['class' => 'date-created toggleable', 'id' => 'th-date-created'],
                            ];
                            foreach ($_table_data as $_t) {
                                array_push($table_data, $_t);
                            }
                            $custom_fields = get_custom_fields('leads', ['show_on_table' => 1]);
                           
                            foreach ($custom_fields as $field) {
                                array_push($table_data, [
                                'name'     => $field['name'],
                                'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
                            ]);
                            }
                            $table_data = hooks()->apply_filters('leads_table_columns', $table_data);
                            
                            ?>
                            <div class="panel-table">
                                <?php
                            render_datatable(
                                $table_data,
                                'leads',
                                ['customizable-table number-index-2'],
                                [
                                'id'                         => 'leads',
                                'data-last-order-identifier' => 'leads',
                                'data-default-order'         => get_table_last_order('leads'),
                            ]
                            );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>