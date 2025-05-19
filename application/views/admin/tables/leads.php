<?php

defined('BASEPATH') or exit('No direct script access allowed');

$this->ci->load->model('gdpr_model');
$this->ci->load->model('leads_model');
$this->ci->load->model('staff_model');
$statuses = $this->ci->leads_model->get_status();
$tagses = $this->ci->leads_model->get_tags_list();
$dealstatuses = $this->ci->leads_model->get_deal_status();




$rules = [
    App_table_filter::new('name', 'TextRule')->label(_l('leads_dt_name')),
    App_table_filter::new('phonenumber', 'TextRule')->label(_l('leads_dt_phonenumber')),
    App_table_filter::new('country', 'SelectRule')->label(_l('lead_country'))->options(function ($ci) {
        return collect(get_all_countries())->map(fn ($country) => [
            'value' => $country['country_id'],
            'label' => $country['short_name'],
        ]);
    }),
	App_table_filter::new('website', 'TextRule')->label(_l('lead_website')),
	App_table_filter::new('BusinessNature', 'TextRule')->label(_l('Industries')),
    App_table_filter::new('city', 'TextRule')->label(_l('lead_city')),
    App_table_filter::new('state', 'TextRule')->label(_l('lead_state')),
    App_table_filter::new('zip', 'TextRule')->label(_l('lead_zip')),
    App_table_filter::new('is_public', 'BooleanRule')->label(_l('lead_public')),
    App_table_filter::new('lost', 'BooleanRule')->label(_l('lead_lost')),
    App_table_filter::new('junk', 'BooleanRule')->label(_l('lead_junk')),
    App_table_filter::new('lastcontact', 'DateRule')->label(_l('leads_dt_last_contact')),
    App_table_filter::new('dateadded', 'DateRule')->label(_l('date_created')),
    App_table_filter::new('dateassigned', 'DateRule')->label(_l('customer_admin_date_assigned')),
    App_table_filter::new('lead_value', 'NumberRule')->label(_l('lead_add_edit_lead_value')),
    App_table_filter::new('status', 'MultiSelectRule')->label(_l('lead_status'))->options(function () use ($statuses) {
        return collect($statuses)->map(fn ($status) => [
            'value' => $status['id'],
            'label' => $status['name'],
            'subtext' => $status['isdefault'] == 1 ? _l('leads_converted_to_client') : null,
        ]);
    }),
	
	App_table_filter::new('deal_status', 'MultiSelectRule')->label(_l('Deal Status'))->options(function () use ($dealstatuses) {
        return collect($dealstatuses)->map(fn ($deal_status) => [
            'value' => $deal_status['id'],
            'label' => $deal_status['name'],
        ]);
    }),
	
	App_table_filter::new('tags', 'MultiSelectRule')->label(_l('Tags'))->options(function () use ($tagses) {
        return collect($tagses)->map(fn ($tag) => [
            'value' => $tag['id'],
            'label' => $tag['name'],
        ]);
    }),
    App_table_filter::new('source', 'MultiSelectRule')->label(_l('lead_source'))->options(function ($ci) {
        return collect($ci->leads_model->get_source())->map(fn ($source) => [
            'value' => $source['id'],
            'label' => $source['name'],
        ]);
    }),
];

$rules[] = App_table_filter::new('assigned', 'SelectRule')->label(_l('leads_dt_assigned'))
    ->withEmptyOperators()
    ->emptyOperatorValue(0)
    ->isVisible(fn () => staff_can('view', 'leads'))
    ->options(function ($ci) {
        $staff = $ci->staff_model->get('', ['active' => 1]);

        return collect($staff)->map(function ($staff) {
            return [
                'value' => $staff['staffid'],
                'label' => $staff['firstname'] . ' ' . $staff['lastname']
            ];
        })->all();
    });


if (isset($consent_purposes)) {
    $rules[] = App_table_filter::new('gdpr_content', 'SelectRule')
        ->label(_l('gdpr_consent'))
        ->options(function () use ($consent_purposes) {
            return collect($consent_purposes)->map(fn ($purpose) => [
                'value' => $purpose['id'],
                'label' => $purpose['name']
            ]);
        })->raw(function ($value, $operator, $sql_operator) {
            return db_prefix() . 'leads.id ' . $sql_operator . ' (SELECT lead_id FROM ' . db_prefix() . 'consents WHERE purpose_id=' . $value . ' and action="opt-in" AND date IN (SELECT MAX(date) FROM ' . db_prefix() . 'consents WHERE purpose_id=' . $value . ' AND lead_id=' . db_prefix() . 'leads.id))';
        });
}




$pagexxx=@$_SESSION['leads_page_type'];


return App_table::find('leads') 
    ->outputUsing(function ($params) use ($statuses,$pagexxx) {
        extract($params);
        $lockAfterConvert      = get_option('lead_lock_after_convert_to_customer');
        $has_permission_delete = staff_can('delete',  'leads');
        $custom_fields         = get_table_custom_fields('leads');
        $consentLeads          = get_option('gdpr_enable_consent_for_leads');

        $aColumns = [
            '1',
            db_prefix() . 'leads.id as id',
            db_prefix() . 'leads.name as name',
        ];
        if (is_gdpr() && $consentLeads == '1') {
            $aColumns[] = '1';
        }
        $aColumns = array_merge($aColumns, [
            'company',
            db_prefix() . 'leads.email as email',
            db_prefix() . 'leads.hash as hash',
            db_prefix() . 'leads.phonenumber as phonenumber',
			db_prefix() . 'leads.website as website',
			db_prefix() . 'leads.BusinessNature as BusinessNature',
            'lead_value',
            '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'leads.id and rel_type="lead" ORDER by tag_order ASC LIMIT 1) as tags',
            'firstname as assigned_firstname',
            db_prefix() . 'leads_status.name as status_name',
			//db_prefix() . 'tags.name as tags',
            db_prefix() . 'leads_sources.name as source_name',
            'lastcontact',
            'dateadded',
        ]);
		

        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'leads';

        $join = [
            'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'leads.assigned',
            'LEFT JOIN ' . db_prefix() . 'leads_status ON ' . db_prefix() . 'leads_status.id = ' . db_prefix() . 'leads.status',
			//'LEFT JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'tags.id = ' . db_prefix() . 'leads.tags',
            'JOIN ' . db_prefix() . 'leads_sources ON ' . db_prefix() . 'leads_sources.id = ' . db_prefix() . 'leads.source',
        ];

        foreach ($custom_fields as $key => $field) {
            $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
            array_push($customFieldsColumns, $selectAs);
            array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
            array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'leads.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
        }

        $where  = [];

        if ($filtersWhere = $this->getWhereFromRules()) {
            $where[] = $filtersWhere;
        }

		array_push($where);
 
        if (staff_cant('view', 'leads')) {
		
		if(get_staff_rolex()==4){  // 4 for assign role UW approver
		array_push($where, ' AND (deal_status = 3)'); // display only list for UW Deals
		
		}else{
		
            array_push($where, 'AND (assigned =' . get_staff_user_id() . ' OR addedfrom = ' . get_staff_user_id() . ' OR is_public = 1)');
			}
        }elseif(get_staff_rolex()==4){ // 4 for assign role UW approver
		array_push($where, ' AND (deal_status = 3)'); // display only list for UW Deals
		
		}

        $aColumns = hooks()->apply_filters('leads_table_sql_columns', $aColumns);

        // Fix for big queries. Some hosting have max_join_limit
        if (count($custom_fields) > 4) {
            @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
        }

        $additionalColumns = hooks()->apply_filters('leads_table_additional_columns_sql', [
            'junk',
            'lost',
            'color',
            'status',
            'assigned',
			'absorber',
			'deal_status',
            'lastname as assigned_lastname',
            db_prefix() . 'leads.addedfrom as addedfrom',
            '(SELECT count(leadid) FROM ' . db_prefix() . 'clients WHERE ' . db_prefix() . 'clients.leadid=' . db_prefix() . 'leads.id) as is_converted',
            'zip',
        ]);
		
		
		

        if($pagexxx=='deals'){
		array_push($where, ' AND is_deal=1 ');
		}else{
		array_push($where, ' AND is_deal=0 ');
		}
		
		
        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalColumns);

        $output  = $result['output'];
        $rResult = $result['rResult'];

		$i=1;
		//print_r($result);//exit;
        foreach ($rResult as $aRow) {
            $row = [];

            $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';

            $hrefAttr = 'href="' . admin_url('leads/index/' . $aRow['id']) . '" onclick="init_lead(' . $aRow['id'] . ');return false;"';
            //$row[]    = '<a ' . $hrefAttr . '>' . $aRow['id'] . '</a>';
			$row[]    = '<a ' . $hrefAttr . '>' . $i++ . '</a>';

            $nameRow = '<a ' . $hrefAttr . '>' . e($aRow['name']) . '</a>';

            $nameRow .= '<div class="row-options">';
            $nameRow .= '<a ' . $hrefAttr . '>' . _l('view') . '</a>';

            $locked = false;

            if ($aRow['is_converted'] > 0) {
                $locked = ((!is_admin() && $lockAfterConvert == 1) ? true : false);
            }

            if (!$locked) {
                $nameRow .= ' | <a href="' . admin_url('leads/index/' . $aRow['id'] . '?edit=true') . '" onclick="init_lead(' . $aRow['id'] . ', true);return false;">' . _l('edit') . '</a>';
            }

            if ($aRow['addedfrom'] == get_staff_user_id() || $has_permission_delete) {
                $nameRow .= ' | <a href="' . admin_url('leads/delete/' . $aRow['id']) . '" class="_delete text-danger">' . _l('delete') . '</a>';
            }
            $nameRow .= '</div>';


            $row[] = $nameRow;

            if (is_gdpr() && $consentLeads == '1') {
                $consentHTML = '<p class="bold"><a href="#" onclick="view_lead_consent(' . $aRow['id'] . '); return false;">' . _l('view_consent') . '</a></p>';
                $consents    = $this->ci->gdpr_model->get_consent_purposes($aRow['id'], 'lead');

                foreach ($consents as $consent) {
                    $consentHTML .= '<p style="margin-bottom:0px;">' . e($consent['name']) . (!empty($consent['consent_given']) ? '<i class="fa fa-check text-success pull-right"></i>' : '<i class="fa fa-remove text-danger pull-right"></i>') . '</p>';
                }
                $row[] = $consentHTML;
            }
            $row[] = e($aRow['company']);

            $row[] = ($aRow['email'] != '' ? '<a href="mailto:' . e($aRow['email']) . '">' . e($aRow['email']) . '</a>' : '');

           /* $row[] = ($aRow['phonenumber'] != '' ? '<a href="tel:' . e($aRow['phonenumber']) . '">' . e($aRow['phonenumber']) . '</a>' : '');*/
			$row[] = ($aRow['website'] != '' ? '<a  href="' . maybe_add_http(e($aRow['website'])) . '" target="_blank" title="Move to website" style="word-break: break-all;">' . e($aRow['website']) . '</a>' : '');
			e($aRow['website']);
			$row[] = e($aRow['BusinessNature']);

            $base_currency = get_base_currency();
//            $row[]         = e(($aRow['lead_value'] != 0 ? app_format_money($aRow['lead_value'], $base_currency->id) : ''));

            $row[] .= render_tags($aRow['tags']);

            $assignedOutput = '';
            if ($aRow['assigned'] != 0) {
                $full_name = e($aRow['assigned_firstname'] . ' ' . $aRow['assigned_lastname']);

                $assignedOutput = '<a data-toggle="tooltip" data-title="' . $full_name . '" href="' . admin_url('profile/' . $aRow['assigned']) . '">' . staff_profile_image($aRow['assigned'], [
                    'staff-profile-image-small',
                ]) . '</a>';

                // For Assigning
                $currentLoggedInUser = get_staff_user_id();
                if(is_admin($currentLoggedInUser) || staff_can('view', 'leads')){
                $assignedOutput .= '<span class="text-success" style="padding-left:15px;"><a onclick="leadAssign('.$aRow['id'].','.$aRow['assigned'].')" data-toggle="modal" data-target="#leadAssignModel"><i class="fa fa-plus" aria-hidden="true" style="font-size: 20px;"></i></a></span>';
                }
            }else{
                 // For Assigning
                 $currentLoggedInUser = get_staff_user_id();
                 if(is_admin($currentLoggedInUser)|| staff_can('view', 'leads')){
                    $assignedOutput = '<span class="text-success " style=""><a onclick="leadAssign('.$aRow['id'].')" data-toggle="modal" data-target="#leadAssignModel"><i class="fa fa-plus" aria-hidden="true"style="font-size: 20px;position:relative;left:25px;top:10px"></i></a></span>';
                 }
            }

            $row[] = $assignedOutput;
			
			

            if ($aRow['status_name'] == null) {
                if ($aRow['lost'] == 1) {
                    $outputStatus = '<span class="label label-danger">' . _l('lead_lost') . '</span>';
                } elseif ($aRow['junk'] == 1) {
                    $outputStatus = '<span class="label label-warning">' . _l('lead_junk') . '</span>';
                }
            } else {
			
			    if(e($aRow['status_name'])=="Contact"){
                $outputStatus = '<a '.$aRow['id'].' target="_blank" title="Click to view Contact" href="clients/client/'.get_client_id_by_lead_id($aRow['id']).'"><span class="lead-status-' . $aRow['status'] . ' label' . (empty($aRow['color']) ? ' label-default' : '') . '" style="color:' . $aRow['color'] . ';border:1px solid ' . adjust_hex_brightness($aRow['color'], 0.4) . ';background: ' . adjust_hex_brightness($aRow['color'], 0.04) . ';">' . e($aRow['status_name']);
				}else{
				$outputStatus = '<a '.$aRow['id'].'><span class="lead-status-' . $aRow['status'] . ' label' . (empty($aRow['color']) ? ' label-default' : '') . '" style="color:' . $aRow['color'] . ';border:1px solid ' . adjust_hex_brightness($aRow['color'], 0.4) . ';background: ' . adjust_hex_brightness($aRow['color'], 0.04) . ';">' . e($aRow['status_name']);
				}


                $outputStatus .= '</span></a>';
            }

            $row[] = $outputStatus;
			if($pagexxx=='deals'){
			 $row[] = $this->ci->leads_model->get_deal_status_title(e($aRow['deal_status']));
			 }
			
			//$row[] = e($aRow['absorber']);
			/////////////////////////
			$absorberOutput = '';
            if ($aRow['absorber'] != 0) {
                $full_name = get_staff_full_name($aRow['absorber']);

                $absorberOutput = '<a '.$aRow['absorber'].' data-toggle="tooltip" data-title="' . $full_name . '" href="' . admin_url('profile/' . $aRow['absorber']) . '">' . staff_profile_image($aRow['absorber'], [
                    'staff-profile-image-small',
                ]) . '</a>';

                // For Assigning
                $currentLoggedInUser = get_staff_user_id();
                if(is_admin($currentLoggedInUser) || staff_can('view', 'leads')){
                $absorberOutput .= '<span class="text-success" style="padding-left:15px;"><a onclick="leadAssign('.$aRow['id'].','.$aRow['absorber'].')" data-toggle="modal" data-target="#leadAbsorberModel"><i class="fa fa-plus" aria-hidden="true" style="font-size: 20px;"></i></a></span>';
                }
            }else{
                 // For Assigning
                 $currentLoggedInUser = get_staff_user_id();
                 if(is_admin($currentLoggedInUser)|| staff_can('view', 'leads')){
                    $absorberOutput = '<span class="text-success " style=""><a onclick="leadAssign('.$aRow['id'].')" data-toggle="modal" data-target="#leadAbsorberModel"><i class="fa fa-plus" aria-hidden="true"style="font-size: 20px;position:relative;left:25px;top:10px"></i></a></span>';
                 }
            }

            $row[] = $absorberOutput;
			/////////////////////////////

            $row[] = e($aRow['source_name']);

            $row[] = ($aRow['lastcontact'] == '0000-00-00 00:00:00' || !is_date($aRow['lastcontact']) ? '' : '<span data-toggle="tooltip" data-title="' . e(_dt($aRow['lastcontact'])) . '" class="text-has-action is-date">' . e(time_ago($aRow['lastcontact'])) . '</span>');

            $row[] = '<span data-toggle="tooltip" data-title="' . e(_dt($aRow['dateadded'])) . '" class="text-has-action is-date">' . e(time_ago($aRow['dateadded'])) . '</span>';
            $row[] = '<a class="btn" data-toggle="modal" data-target="#myModal" onclick="getMessages(\'' . e($aRow['name']) . '\',\'' . e($aRow['phonenumber']) . '\')">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 48 48">
                    <path fill="#fff" d="M4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98c-0.001,0,0,0,0,0h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303z"></path>
                    <path fill="#fff" d="M4.868,43.803c-0.132,0-0.26-0.052-0.355-0.148c-0.125-0.127-0.174-0.312-0.127-0.483l2.639-9.636c-1.636-2.906-2.499-6.206-2.497-9.556C4.532,13.238,13.273,4.5,24.014,4.5c5.21,0.002,10.105,2.031,13.784,5.713c3.679,3.683,5.704,8.577,5.702,13.781c-0.004,10.741-8.746,19.48-19.486,19.48c-3.189-0.001-6.344-0.788-9.144-2.277l-9.875,2.589C4.953,43.798,4.911,43.803,4.868,43.803z"></path>
                    <path fill="#cfd8dc" d="M24.014,5c5.079,0.002,9.845,1.979,13.43,5.566c3.584,3.588,5.558,8.356,5.556,13.428c-0.004,10.465-8.522,18.98-18.986,18.98h-0.008c-3.177-0.001-6.3-0.798-9.073-2.311L4.868,43.303l2.694-9.835C5.9,30.59,5.026,27.324,5.027,23.979C5.032,13.514,13.548,5,24.014,5 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974C24.014,42.974,24.014,42.974,24.014,42.974 M24.014,4C24.014,4,24.014,4,24.014,4C12.998,4,4.032,12.962,4.027,23.979c-0.001,3.367,0.849,6.685,2.461,9.622l-2.585,9.439c-0.094,0.345,0.002,0.713,0.254,0.967c0.19,0.192,0.447,0.297,0.711,0.297c0.085,0,0.17-0.011,0.254-0.033l9.687-2.54c2.828,1.468,5.998,2.243,9.197,2.244c11.024,0,19.99-8.963,19.995-19.98c0.002-5.339-2.075-10.359-5.848-14.135C34.378,6.083,29.357,4.002,24.014,4L24.014,4z"></path>
                    <path fill="#40c351" d="M35.176,12.832c-2.98-2.982-6.941-4.625-11.157-4.626c-8.704,0-15.783,7.076-15.787,15.774c-0.001,2.981,0.833,5.883,2.413,8.396l0.376,0.597l-1.595,5.821l5.973-1.566l0.577,0.342c2.422,1.438,5.2,2.198,8.032,2.199h0.006c8.698,0,15.777-7.077,15.78-15.776C39.795,19.778,38.156,15.814,35.176,12.832z"></path>
                    <path fill="#fff" fill-rule="evenodd" d="M19.268,16.045c-0.355-0.79-0.729-0.806-1.068-0.82c-0.277-0.012-0.593-0.011-0.909-0.011c-0.316,0-0.83,0.119-1.265,0.594c-0.435,0.475-1.661,1.622-1.661,3.956c0,2.334,1.7,4.59,1.937,4.906c0.237,0.316,3.282,5.259,8.104,7.161c4.007,1.58,4.823,1.266,5.693,1.187c0.87-0.079,2.807-1.147,3.202-2.255c0.395-1.108,0.395-2.057,0.277-2.255c-0.119-0.198-0.435-0.316-0.909-0.554s-2.807-1.385-3.242-1.543c-0.435-0.158-0.751-0.237-1.068,0.238c-0.316,0.474-1.225,1.543-1.502,1.859c-0.277,0.317-0.554,0.357-1.028,0.119c-0.474-0.238-2.002-0.738-3.815-2.354c-1.41-1.257-2.362-2.81-2.639-3.285c-0.277-0.474-0.03-0.731,0.208-0.968c0.213-0.213,0.474-0.554,0.712-0.831c0.237-0.277,0.316-0.475,0.474-0.791c0.158-0.317,0.079-0.594-0.04-0.831C20.612,19.329,19.69,16.983,19.268,16.045z" clip-rule="evenodd"></path>
                </svg>
            </a>';


            // Custom fields add values
            foreach ($customFieldsColumns as $customFieldColumn) {
                $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
            }

            $row['DT_RowId'] = 'lead_' . $aRow['id'];

            if ($aRow['assigned'] == get_staff_user_id()) {
                //$row['DT_RowClass'] = 'info';
            }
			if(!(isset($aRow['hash'])&&$aRow['hash']))
			{
				$row['DT_RowClass'] = 'info';
			}

            if (isset($row['DT_RowClass'])) {
                $row['DT_RowClass'] .= ' has-row-options';
            } else {
                $row['DT_RowClass'] = 'has-row-options';
            }

            $row = hooks()->apply_filters('leads_table_row_data', $row, $aRow);

            $output['aaData'][] = $row;
        }
        return $output;
    })->setRules($rules);
