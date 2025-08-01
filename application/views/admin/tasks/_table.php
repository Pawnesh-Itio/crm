<?php

defined('BASEPATH') or exit('No direct script access allowed');

$table_data = [
    _l('the_number_sign'),
    _l('TaskID'),
    _l('tasks_dt_name'),
    _l('tasks_dt_datestart'),
    [
        'name'     => _l('task_duedate'),
        'th_attrs' => ['class' => 'duedate'],
    ],
    _l('task_assigned'),
    _l('task_status'),
    // _l('tags'),
    // _l('tasks_list_priority'),
];

array_unshift($table_data, [
    'name'     => '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="tasks"><label></label></div>',
    'th_attrs' => ['class' => (isset($bulk_actions) ? '' : 'not_visible')],
]);

$custom_fields = get_custom_fields('tasks', [
    'show_on_table' => 1,
]);

foreach ($custom_fields as $field) {
    array_push($table_data, [
     'name'     => $field['name'],
     'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
 ]);
}

$table_data = hooks()->apply_filters('tasks_table_columns', $table_data);

render_datatable($table_data, 'tasks', ['number-index-' . isset($bulk_actions) ? 2 : 1], [
        'data-last-order-identifier' => 'tasks',
        'data-default-order'         => get_table_last_order('tasks'),
        'id'=>$table_id ?? 'tasks'
]);