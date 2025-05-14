<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="<?php if ($openEdit == true) {
    echo 'open-edit ';
} ?>lead-wrapper" <?php if (isset($lead) && ($lead->junk == 1 || $lead->lost == 1)) {
    echo 'lead-is-junk-or-lost';
} ?>>

    <?php if (isset($lead)) { ?>
    <!-- Conversation Dropdown By TechWizard -->
    <div class="btn-group pull-right mleft5">
        <?php /*?><a href="<?php echo admin_url('webmail/compose?id='.$lead->email); ?>" class="btn btn-default lead-top-btn">E-mail</a><?php */?>
		<a data-toggle="modal" data-href="<?php echo admin_url('webmail/webmail_leads?stype=TEXT&skey='.$lead->email); ?>"  data-name="<?= $lead->name ?>" data-email= "<?= $lead->email ?>" onclick="getWebEmail(this)" class="btn btn-info lead-top-btn">E-mail</a>
    </div>
    <div class="btn-group pull-right mleft5">
        <a class="btn btn-info dropdown-toggle lead-top-btn" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo _l('chatBtn'); ?>				
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenu1">
            <!-- TechWizard Whatsapp Link -->
            <?php if(!empty($lead->phonenumber)){ ?>
                <li>
                    <a data-toggle="modal" data-target="#myModal" data-name="<?= $lead->name ?>" data-number= "<?= $lead->phonenumber ?>" onclick="getMessages(this)">
                        <i class="fa-brands fa-whatsapp"></i>    
                        <?php echo _l('lead_conversion_whatsapp'); ?>
                    </a>
                </li>
            <?php } ?>
            <!-- TechWizard Telegram Link -->
             <?php if(e($lead->source) ==  4){
			 	
				$telegram_token = get_option('telegram_token');
				?>
                <li>
					<a data-toggle="modal" data-target="#myModalTel" onclick="getTelegramChat('<?php echo $lead->name;?>', '<?php echo $lead->client_id;?>', '<?php echo $telegram_token;?>')">
                        <i class="fa-brands fa-telegram"></i>  
                        <?php echo _l('lead_conversion_telegram'); ?>
                    </a>
                </li>
             <?php } ?>
             <!-- TechWizard LiveChat -->
             <?php if(e($lead->source) ==  5){ ?>
				<li><a data-toggle="modal" data-target="#myModal_web" onclick="getWebChat('<?php echo $lead->name;?>', '<?php echo $lead->client_id;?>')">
                        <i class="far fa-comment-dots"></i>    
                        <?php echo _l('lead_conversion_live_chat'); ?>
                    </a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="btn-group pull-right mleft5" id="lead-more-btn">
        <a href="#" class="btn btn-info dropdown-toggle lead-top-btn" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <?php echo _l('more'); ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-left" id="lead-more-dropdown">
            <?php if ($lead->junk == 0) {
    if ($lead->lost == 0 && (total_rows(db_prefix() . 'clients', ['leadid' => $lead->id]) == 0)) { ?>
            <li>
                <a href="#" onclick="lead_mark_as_lost(<?php echo e($lead->id); ?>); return false;">
                    <i class="fa fa-mars"></i>
                    <?php echo _l('lead_mark_as_lost'); ?>
                </a>
            </li>
            <?php } elseif ($lead->lost == 1) { ?>
            <li>
                <a href="#" onclick="lead_unmark_as_lost(<?php echo e($lead->id); ?>); return false;">
                    <i class="fa fa-smile-o"></i>
                    <?php echo _l('lead_unmark_as_lost'); ?>
                </a>
            </li>
            <?php } ?>
            <?php
} ?>
            <!-- mark as junk -->
            <?php if ($lead->lost == 0) {
        if ($lead->junk == 0 && (total_rows(db_prefix() . 'clients', ['leadid' => $lead->id]) == 0)) { ?>
            <li>
                <a href="#" onclick="lead_mark_as_junk(<?php echo e($lead->id); ?>); return false;">
                    <i class="fa fa fa-times"></i>
                    <?php echo _l('lead_mark_as_junk'); ?>
                </a>
            </li>
            <?php } elseif ($lead->junk == 1) { ?>
            <li>
                <a href="#" onclick="lead_unmark_as_junk(<?php echo e($lead->id); ?>); return false;">
                    <i class="fa fa-smile-o"></i>
                    <?php echo _l('lead_unmark_as_junk'); ?>
                </a>
            </li>
            <?php } ?>
            <?php } ?>
            <?php if ((staff_can('delete',  'leads') && $lead_locked == false) || is_admin()) { ?>
            <li>
                <a href="<?php echo admin_url('leads/delete/' . $lead->id); ?>" class="text-danger delete-text _delete"
                    data-toggle="tooltip" title="">
                    <i class="fa fa-remove"></i>
                    <?php echo _l('lead_edit_delete_tooltip'); ?>
                </a>
            </li>
            <?php } ?>
        </ul>

    </div>

    <div class="pull-right mleft5">
        <a data-toggle="tooltip" class="btn btn-info lead-print-btn lead-top-btn lead-view"
           onclick="print_lead_information(); return false;" data-placement="top" title="<?php echo _l('print'); ?>"
           href="#">
            <i class="fa fa-print"></i>
        </a>
    </div>
    <?php if(isset($lead->deal_status)&&$lead->deal_status <= 3){ ?>
    <div class="mleft5 pull-right<?php echo $lead_locked == true ? ' hide': ''; ?>">
        <a href="#" lead-edit data-toggle="tooltip" data-title="<?php echo _l('edit'); ?>"
            class="btn btn-info lead-top-btn">

            <i class="fa-regular fa-pen-to-square"></i>
        </a>
    </div>
	<?php } ?>
	 <?php if(isset($lead->is_deal)&&$lead->is_deal==0&&$lead->status==1){ ?>
	<div class="mleft5 pull-right">
        <a href="#" class="btn btn-success pull-right lead-top-btn" data-toggle="modal" data-target="#dealModal" ><i class="fa-solid fa-handshake"></i> Convert to Deal <?php //echo $lead->is_deal?></a>
    </div>
	<?php }else{ ?>
	<?php /*?><div class="mleft5 pull-right">
<a href="#" class="btn btn-warning pull-right lead-top-btn" onclick="alert('Already converted to deal')" ><i class="fa-solid fa-handshake"></i> Deal</a><?php */?><?php } ?></div>
    <?php
           $client                                 = false;
           $convert_to_client_tooltip_email_exists = '';
           if (total_rows(db_prefix() . 'contacts', ['email' => $lead->email]) > 0 && total_rows(db_prefix() . 'clients', ['leadid' => $lead->id]) == 0) {
               $convert_to_client_tooltip_email_exists = _l('lead_email_already_exists');
               $text                                   = _l('lead_convert_to_client');
           } elseif (total_rows(db_prefix() . 'clients', ['leadid' => $lead->id])) {
               $client = true;
           } else {
               $text = _l('lead_convert_to_client');
           }
      ?>

    <?php if ($lead_locked == false) { ?>
    <div class="lead-edit<?php if (isset($lead)) {
          echo ' hide';
      } ?>">
        <button type="button" class="btn btn-primary pull-right lead-top-btn lead-save-btn"
            onclick="document.getElementById('lead-form-submit').click();">
            <?php echo _l('submit'); ?>
        </button>
    </div>
    <?php } ?>
    <?php if ($client && (staff_can('view',  'customers') || is_customer_admin(get_client_id_by_lead_id($lead->id)))) { ?>
    <a data-toggle="tooltip" class="btn btn-success pull-right lead-top-btn lead-view" data-placement="top"
        title="<?php echo _l('lead_converted_edit_client_profile'); ?>"
        href="<?php echo admin_url('clients/client/' . get_client_id_by_lead_id($lead->id)); ?>">
        <i class="fa-regular fa-user"></i>
    </a>
    <?php } ?>
    <?php if (total_rows(db_prefix() . 'clients', ['leadid' => $lead->id]) == 0) { ?>
    <?php /*?><a href="#" data-toggle="tooltip" data-title="<?php echo e($convert_to_client_tooltip_email_exists); ?>"
        class="btn btn-success pull-right lead-convert-to-customer lead-top-btn lead-view"
        onclick="convert_lead_to_customer(<?php echo e($lead->id); ?>); return false;">
        <i class="fa-regular fa-user"></i>
        <?php echo e($text); ?>
    </a><?php */?>
    <?php } ?>
    <?php } ?>

    <div class="clearfix no-margin"></div>

    <?php if (isset($lead)) { ?>

    <div class="row mbot15" style="margin-top:12px;">
        <hr class="no-margin" />
    </div>

    <div class="alert alert-warning hide mtop20" role="alert" id="lead_proposal_warning">
        <?php echo _l('proposal_warning_email_change', [_l('lead_lowercase'), _l('lead_lowercase'), _l('lead_lowercase')]); ?>
        <hr />
        <a href="#" onclick="update_all_proposal_emails_linked_to_lead(<?php echo e($lead->id); ?>); return false;">
            <?php echo _l('update_proposal_email_yes'); ?>
        </a>
        <br />
        <a href="#" onclick="init_lead_modal_data(<?php echo e($lead->id); ?>); return false;">
            <?php echo _l('update_proposal_email_no'); ?>
        </a>
    </div>
    <?php } ?>

    <div class="row">
	<div class=" <?php if(isset($lead->id)&&$lead->id){ ?> col-md-6 <?php }else{ ?> col-md-12 <?php } ?> col-xs-12 lead-information-col">
<?php echo form_open((isset($lead) ? admin_url('leads/lead/' . $lead->id) : admin_url('leads/lead')), ['id' => 'lead_form']); ?> 
        <div class="lead-view<?php if (!isset($lead)) {echo ' hide';} ?>" id="leadViewWrapper">
		    
            <div class="col-md-6 col-xs-12 lead-information-col">
                <div class="lead-info-heading">
                    <h4>
                        <?php echo _l('lead_info'); ?>
                    </h4>
                </div>
                <dl>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                        <?php echo _l('lead_add_edit_name'); ?></dt>
                    <dd class="tw-text-neutral-900 tw-mt-1 lead-name">
                        <?php echo(isset($lead) && $lead->name != '' ? e($lead->name) : '-') ?></dd>
<?php /*?>                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_title'); ?>
                    </dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->title != '' ? e($lead->title) : '-') ?>
                    </dd>
<?php */?>          
					<dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                        <?php echo _l('lead_add_edit_email'); ?></dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->email != '' ? '<a href="mailto:' . e($lead->email) . '">' . e($lead->email) . '</a>' : '-') ?>
                    </dd>
					
					<dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                        <?php echo _l('lead_add_edit_phonenumber'); ?></dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->phonenumber != '' ? '<a href="tel:' . e($lead->country_code).e($lead->phonenumber). '">' . e($lead->country_code).' - '.e($lead->phonenumber) . '</a>' : '-') ?>
                    </dd>
					
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('Business URL'); ?>
                    </dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->website != '' ? '<a href="' . e(maybe_add_http($lead->website)) . '" target="_blank">' . e($lead->website) . '</a>' : '-') ?>
                    </dd>
                    
<?php /*?>                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_value'); ?>
                    </dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->lead_value != 0 ? e(app_format_money($lead->lead_value, $base_currency->id)) : '-') ?>
                    </dd>
<?php */?>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('Business Name'); ?>
                    </dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->company != '' ? e($lead->company) : '-') ?></dd>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_address'); ?>
                    </dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                    <?php echo(isset($lead) && $lead->address != '' ? process_text_content_for_display($lead->address) : '-') ?></dd>
                   
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_country'); ?>
                    </dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->country != 0 ? e(get_country($lead->country)->short_name) : '-') ?>
                    </dd>
					
					<dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('Business Nature'); ?>
                    </dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->BusinessNature != '' ? e($lead->BusinessNature) : '-') ?></dd>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('Incorporation Country'); ?>
                    </dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->IncorporationCountry != '' ? e(get_country($lead->IncorporationCountry)->short_name) : '-') ?>
                    </dd>
                    <?php /*?><dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_zip'); ?></dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->zip != '' ? e($lead->zip) : '-') ?></dd>
                </dl><?php */?>
				</dl>
				
            </div>
			
			
			
            <div class="col-md-6 col-xs-12 lead-information-col">
                <div class="lead-info-heading">
                    <h4>
                        <?php echo _l('lead_general_info'); ?>
                    </h4>
                </div>
                <dl>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500 no-mtop">
                        <?php echo _l('lead_add_edit_status'); ?>
                    </dt>
                    <dd class="tw-text-neutral-900 tw-mt-2 mbot15">
                        <?php
                            if (isset($lead)) {
                                echo $lead->status_name != '' ? ('<span class="lead-status-' . e($lead->status) . ' label' . (empty($lead->color) ? ' label-default': '') . '" style="color:' . e($lead->color) . ';border:1px solid ' . adjust_hex_brightness($lead->color, 0.4) . ';background: ' . adjust_hex_brightness($lead->color, 0.04) . ';">' . e($lead->status_name) . '</span>') : '-';
                            } else {
                                echo '-';
                            }
                        ?>
                    </dd>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                        <?php echo _l('lead_add_edit_source'); ?></dt>
                    <dd class="tw-text-neutral-900 tw-mt-1 mbot15">
                        <?php echo(isset($lead) && $lead->source_name != '' ? e($lead->source_name) : '-') ?></dd>
                    <?php if (!is_language_disabled()) { ?>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                        <?php echo _l('localization_default_language'); ?>
                    </dt>
                    <dd class="tw-text-neutral-900 tw-mt-1 mbot15">
                        <?php echo(isset($lead) && $lead->default_language != '' ? e(ucfirst($lead->default_language)) : _l('system_default_string')) ?>
                    </dd>
                    <?php } ?>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                        <?php echo _l('lead_add_edit_assigned'); ?></dt>
                    <dd class="tw-text-neutral-900 tw-mt-1 mbot15">
                        <?php echo(isset($lead) && $lead->assigned != 0 ? e(get_staff_full_name($lead->assigned)) : '-') ?>
                    </dd>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('tags'); ?></dt>
                    <dd class="tw-text-neutral-900 tw-mt-1 mbot10">
                        <?php
                  if (isset($lead)) {
                      $tags = get_tags_in($lead->id, 'lead');
                      if (count($tags) > 0) {
                          echo render_tags($tags);
                          echo '<div class="clearfix"></div>';
                      } else {
                          echo '-';
                      }
                  }
                  ?>
                    </dd>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                        <?php echo _l('leads_dt_datecreated'); ?></dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->dateadded != '' ? '<span class="text-has-action" data-toggle="tooltip" data-title="' . e(_dt($lead->dateadded)) . '">' . e(time_ago($lead->dateadded)) . '</span>' : '-') ?>
                    </dd>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                        <?php echo _l('leads_dt_last_contact'); ?></dt>
                    <dd class="tw-text-neutral-900 tw-mt-1">
                        <?php echo(isset($lead) && $lead->lastcontact != '' ? '<span class="text-has-action" data-toggle="tooltip" data-title="' . e(_dt($lead->lastcontact)) . '">' . e(time_ago($lead->lastcontact)) . '</span>' : '-') ?>
                    </dd>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500"><?php echo _l('lead_public'); ?>
                    </dt>
                    <dd class="tw-text-neutral-900 tw-mt-1 mbot15">
                        <?php if (isset($lead)) {
                      if ($lead->is_public == 1) {
                          echo _l('lead_is_public_yes');
                      } else {
                          echo _l('lead_is_public_no');
                      }
                  } else {
                      echo '-';
                  }
                        ?>
                    </dd>
                    <?php if (isset($lead) && $lead->from_form_id != 0) { ?>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500">
                        <?php echo _l('web_to_lead_form'); ?></dt>
                    <dd class="tw-text-neutral-900 tw-mt-1 mbot15"><?php echo e($lead->form_data->name); ?></dd>
                    <?php } ?>
                </dl>
            </div>
			
			<div class="clearfix"></div>
			<div class="col-md-12 col-xs-12">
               
<div class="alert alert-warning" onclick="togglediv('#myDiv4')"><?php echo _l('Business Description'); ?> <span class="pull-right mt-2 lead-view"><i class="fa-solid fa-angle-down"></i></span></div>
<div id="myDiv4" class="tw-border-neutral-200" style="display:none;">
<div class="panel-body tw-mb-4">
<div class="form-group">

<?php echo process_text_content_for_display((isset($lead) && $lead->description != '' ? $lead->description : '-')); ?>


	
</div>
</div>
</div>
</div>

			
			<div class="clearfix"></div>
			<div class="col-md-12 col-xs-12">
               
<div class="alert alert-warning" onclick="togglediv('#myDiv')"><?php echo _l('Deal Details'); ?> <span class="pull-right mt-2 lead-view"><i class="fa-solid fa-angle-down"></i></span></div>
<div id="myDiv" class="tw-border-neutral-200" style="display:none;">
<div class="panel-body tw-mb-4">
<div class="form-group">
<div>
<table border="1" cellpadding="5" cellspacing="5" width="100%"><tbody>
<tr><td class="tw-font-bold" width="50%">Target Country</td><td width="50%"> :: <?php echo(isset($lead) && $lead->target_countries != 0 ? e(get_country($lead->target_countries)->short_name) : '-') ?></td></tr>
<tr><td class="tw-font-bold" width="50%">Products / Services</td><td width="50%"> :: <?php echo(isset($lead) && $lead->products_services != '' ? e($lead->products_services) : '-') ?></td></tr>
<tr><td class="tw-font-bold" width="50%">Descriptor</td><td width="50%"> :: <?php echo(isset($lead) && $lead->descriptor != '' ? e($lead->descriptor) : '-') ?></td></tr>
<tr><td class="tw-font-bold" width="50%">Processing History</td><td width="50%"> :: <?php echo(isset($lead) && $lead->processing_history != '' ? e($lead->processing_history) : '-') ?></td></tr>
<tr><td class="tw-font-bold" width="50%">Accept Cards</td><td width="50%"> :: <?php echo(isset($lead) && $lead->accept_cards != '' ? e($lead->accept_cards) : '-') ?> (<?php echo(isset($lead) && $lead->card_tdr != '' ? e($lead->card_tdr) : '-') ?>)</td></tr>
<tr><td class="tw-font-bold" width="50%">Estimated Total Sales</td><td width="50%"> :: <?php echo(isset($lead) && $lead->estimated_total_sales != '' ? e($lead->estimated_total_sales) : '-') ?></td></tr>
<tr><td class="tw-font-bold" width="50%">Number of Transactions</td><td width="50%"> :: <?php echo(isset($lead) && $lead->number_of_transactions != '' ? e($lead->number_of_transactions) : '-') ?></td></tr>
<tr><td class="tw-font-bold" width="50%">Minimum Ticket Amount</td><td width="50%"> :: <?php echo(isset($lead) && $lead->minimum_ticket_amount != '' ? e($lead->minimum_ticket_amount) : '-') ?></td></tr>
<tr><td class="tw-font-bold" width="50%">Maximum Ticket Amount</td><td width="50%"> :: <?php echo(isset($lead) && $lead->maximum_ticket_amount != '' ? e($lead->maximum_ticket_amount) : '-') ?></td></tr>
</tbody></table>
</div>


<div class="lead-info-heading tw-mt-5">SPOC Info : </div>
<div><?php echo(isset($lead) && $lead->spoc_info != '' ? $this->leads_model->jsonToTable($lead->spoc_info) : '-') ?></div>
<div class="lead-info-heading tw-mt-5">Customer Service : </div>
<div><?php echo(isset($lead) && $lead->customer_info != '' ? $this->leads_model->jsonToTable($lead->customer_info) : '-') ?></div>
<div class="lead-info-heading tw-mt-5">Website Info : </div>
<div><?php echo(isset($lead) && $lead->website_info != '' ? $this->leads_model->jsonToTable($lead->website_info) : '-') ?></div>
<div class="lead-info-heading tw-mt-5">Old History : </div>
<div><?php echo(isset($lead) && $lead->old_history != '' ? $this->leads_model->jsonToTable($lead->old_history) : '-') ?></div>
	
</div>
</div>
</div>
</div>

<?php

$this->db->select('*,');
$this->db->limit(1);
$this->db->where('deal_id', $lead->id);
$this->db->order_by('id', 'DESC');

$docs=$this->db->get('deal_document')->row();
if ($this->db->affected_rows() > 0) {

?>

			<div class="clearfix"></div>
			<div class="col-md-12 col-xs-12">
               
<div class="alert alert-warning" onclick="togglediv('#myDiv2')"><?php echo _l('Deal Document'); ?> <span class="pull-right mt-2 lead-view"><i class="fa-solid fa-angle-down"></i></span></div>
<div id="myDiv2" class="tw-border-neutral-200" style="display:none;">
<div class="panel-body tw-mb-4">
<div class="form-group">


<div class="lead-info-heading tw-mt-5">Ownership Info 1 : </div>
<div><?php echo(isset($docs) && $docs->ownership_info1 != '' ? $this->leads_model->jsonToTable($docs->ownership_info1) : '-') ?></div>
<div class="lead-info-heading tw-mt-5">Ownership Info 2 : </div>
<div><?php echo(isset($docs) && $docs->ownership_info2 != '' ? $this->leads_model->jsonToTable($docs->ownership_info2) : '-') ?></div>

<div class="lead-info-heading tw-mt-5">Bank Info : </div>
<div><?php echo(isset($docs) && $docs->bank_info != '' ? $this->leads_model->jsonToTable($docs->bank_info) : '-') ?></div>

<div class="lead-info-heading tw-mt-5">Business And Operations Description : </div>
<div><?php echo(isset($docs) && $docs->business_and_operations_description != '' ? $docs->business_and_operations_description : '-') ?></div>

<div class="lead-info-heading tw-mt-5">Fulfillment And Delivery Explanation : </div>
<div><?php echo(isset($docs) && $docs->fulfillment_and_delivery_explanation != '' ? $docs->fulfillment_and_delivery_explanation : '-') ?></div>

<div class="lead-info-heading tw-mt-5">Marketing Activities Overview : </div>
<div><?php echo(isset($docs) && $docs->marketing_activities_overview != '' ? $docs->marketing_activities_overview : '-') ?></div>

<div class="lead-info-heading tw-mt-5">Added On : </div>
<div><?php echo(isset($docs) && $docs->dateadded != '' ? $docs->dateadded : '-') ?></div>
	
</div>
</div>
</div>
</div>
<?php
 }
?>
<?php

$this->db->select('*,');
$this->db->limit(1);
$this->db->where('deal_id', $lead->id);
$this->db->order_by('id', 'DESC');

$quote=$this->db->get('deal_quotation')->row();
if ($this->db->affected_rows() > 0) {
?>

<div class="clearfix"></div>
<div class="col-md-12 col-xs-12">
               
<div class="alert alert-warning" onclick="togglediv('#myDiv3')"><?php echo _l('Deal Quotation'); ?> <span class="pull-right mt-2 lead-view"><i class="fa-solid fa-angle-down"></i></span></div>
<div id="myDiv3" class="tw-border-neutral-200" style="display:none;">
<div class="panel-body tw-mb-4">
<div class="form-group">
<?php


echo "<table border='1' cellpadding='5' cellspacing='5' width='100%'><tbody>";

foreach ($quote as $key => $value) {
    echo "<tr><td width='50%'><strong>".ucwords(str_replace("_"," ",$key))."</strong></td><td width='50%'> :: {$value}</td></tr>";
}

echo "<tbody></table>";
?>


	
</div>
</div>
</div>
</div>
<?php
}
?>
<?php
if ((isset($lead) && !empty($lead->custom_field))) {
?>
			<div class="clearfix"></div>
			<div class="col-md-12 col-xs-12">
               
<div class="alert alert-warning" onclick="togglediv('#myDiv5')"><?php echo _l('Custom Fields - Staff'); ?> <span class="pull-right mt-2 lead-view"><i class="fa-solid fa-angle-down"></i></span></div>
<div id="myDiv5" class="tw-border-neutral-200" style="display:none;">
<div class="panel-body tw-mb-4">
<div class="form-group">
<?php
// Convert to associative array
$custom_field_array = json_decode($lead->custom_field, true);
foreach ($custom_field_array as $key => $value) {
?>
<div><?php echo $key; ?> : <?php echo $value; ?></div>
<?php } ?>
</div>
</div>
</div>
</div>			
<?php } ?>			
			
<div class="clearfix"></div>
<div class="col-md-12 col-xs-12 lead-information-col">
<?php if (total_rows(db_prefix() . 'customfields', ['fieldto' => 'leads', 'active' => 1]) > 0 && isset($lead)) { ?>
                <div class="lead-info-heading">
                    <h4>
                        <?php echo _l('custom_fields'); ?>
                    </h4>
                </div>
                <dl>
                    <?php
            $custom_fields = get_custom_fields('leads');
            foreach ($custom_fields as $field) {
                $value = get_custom_field_value($lead->id, $field['id'], 'leads'); ?>
                    <dt class="lead-field-heading tw-font-medium tw-text-neutral-500 no-mtop">
                        <?php echo e($field['name']); ?></dt>
                    <dd class="tw-text-neutral-900 tw-mt-1 tw-break-words"><?php echo($value != '' ? $value : '-') ?>
                    </dd>
                    <?php
            } ?>
                    <?php } ?>
                </dl>
            </div>
            
        </div>
		
		
		
        <div class="clearfix"></div>
        <div class="lead-edit<?php if (isset($lead)) {
                echo ' hide';
            } ?>">
            <div class="col-md-4">
                <?php
            $selected = '';
            if (isset($lead)) {
                $selected = $lead->status;
            } elseif (isset($status_id)) {
                $selected = $status_id;
            }
            echo render_leads_status_select($statuses, $selected, 'lead_add_edit_status');
          ?>
            </div>
            <div class="col-md-4">
                <?php
                    $selected = (isset($lead) ? $lead->source : get_option('leads_default_source'));
                    echo render_leads_source_select($sources, $selected, 'lead_add_edit_source');
                ?>
            </div>
            <div class="col-md-4">
                <?php
               $assigned_attrs = [];
               $selected       = (isset($lead) ? $lead->assigned : get_staff_user_id());
               if (isset($lead)
                  && $lead->assigned == get_staff_user_id()
                  && $lead->addedfrom != get_staff_user_id()
                  && !is_admin($lead->assigned)
                  && staff_cant('view', 'leads')
               ) {
                   $assigned_attrs['disabled'] = true;
               }
               if(!isset($lead) && !is_admin($selected) ){
                 $assigned_attrs['disabled'] = true;
               }
               echo render_select('assigned', $members, ['staffid', ['firstname', 'lastname']], 'lead_add_edit_assigned', $selected, $assigned_attrs); ?>
            </div>
            <div class="clearfix"></div>
            <hr class="mtop5 mbot10" />
            <div class="col-md-12">
                <div class="form-group no-mbot" id="inputTagsWrapper">
                    <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i>
                        <?php echo _l('tags'); ?></label>
                    <input type="text" class="tagsinput" id="tags" name="tags"
                        value="<?php echo(isset($lead) ? prep_tags_input(get_tags_in($lead->id, 'lead')) : ''); ?>"
                        data-role="tagsinput">
                </div>
            </div>
            <div class="clearfix"></div>
            <hr class="no-mtop mbot15" />
            <div class="col-md-6">
                <?php $value = (isset($lead) ? $lead->name : ''); ?>
                <?php echo render_input('name', 'lead_add_edit_name', $value); ?>
                <?php $value = (isset($lead) ? $lead->title : ''); ?>
                <?php //echo render_input('title', 'lead_title', $value); ?>
                <?php $value = (isset($lead) ? $lead->email : ''); ?>
                <?php echo render_input('email', 'lead_add_edit_email', $value); ?>
                
				<div class="col-md-4 tw-px-0">
				<?php
				
                $value = (isset($lead) ? $lead->country_code : ''); ?>
                <?php echo render_input('country_code', 'ISD Code', $value); ?>
				</div>
				
				<div class="col-md-8 tw-px-0">
				<?php $value = (isset($lead) ? $lead->phonenumber : ''); ?>
                <?php echo render_input('phonenumber', 'lead_add_edit_phonenumber', $value); ?>
				</div>
				
                <?php $value = (isset($lead) ? $lead->company : ''); ?>
                <?php echo render_input('company', 'Business Name', $value); //lead_company to Business Name ?>  
				
				 <?php $value = (isset($lead) ? $lead->BusinessNature : ''); ?>
                <?php echo render_input('BusinessNature', 'Business Nature', $value); //lead_company to Business Name ?>  
            </div>
            <div class="col-md-6">
			   <?php if ((isset($lead) && empty($lead->website)) || !isset($lead)) {
                   $value = (isset($lead) ? $lead->website : '');
                   echo render_input('website', 'Business URL', $value);//lead_website to Business URL 
               } else { ?>
                <div class="form-group">
                    <label for="website"><?php echo _l('Business URL'); ?></label> 
                    <div class="input-group">
                        <input type="text" name="website" id="website" value="<?php echo e($lead->website); ?>"
                            class="form-control">
                        <div class="input-group-addon">
                            <span>
                                <a href="<?php echo e(maybe_add_http($lead->website)); ?>" target="_blank" tabindex="-1">
                                    <i class="fa fa-globe"></i>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                <?php }?>
                <?php $value = (isset($lead) ? $lead->address : ''); ?>
                <?php echo render_textarea('address', 'lead_address', $value, ['rows' => 1, 'style' => 'height:36px;font-size:100%;']); ?>

                <?php
               $countries                = get_all_countries();
               $customer_default_country = get_option('customer_default_country');
               $selected                 = (isset($lead) ? $lead->country : $customer_default_country);
               echo render_select('country', $countries, [ 'country_id', [ 'short_name']], 'lead_country', $selected, ['data-none-selected-text' => _l('dropdown_non_selected_tex')]);
               ?>
			   
			    <?php
               $customer_default_country = get_option('customer_default_country');
               $selected                 = (isset($lead) ? $lead->IncorporationCountry : $customer_default_country);
               echo render_select('IncorporationCountry', $countries, [ 'country_id', [ 'short_name']], 'Incorporation Country', $selected, ['data-none-selected-text' => _l('dropdown_non_selected_tex')]);
               ?>
			   
                <?php //$value = (isset($lead) ? $lead->zip : ''); ?>
                <?php //echo render_input('zip', 'lead_zip', $value); ?>
                <?php if (!is_language_disabled()) { ?>
                <div class="form-group">
                    <label for="default_language"
                        class="control-label"><?php echo _l('localization_default_language'); ?></label>
                    <select name="default_language" data-live-search="true" id="default_language"
                        class="form-control selectpicker"
                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <option value=""><?php echo _l('system_default_string'); ?></option>
                        <?php foreach ($this->app->get_available_languages() as $availableLanguage) {
                   $selected = '';
                   if (isset($lead)) {
                       if ($lead->default_language == $availableLanguage) {
                           $selected = 'selected';
                       }
                   } ?>
                        <option value="<?php echo e($availableLanguage); ?>" <?php echo e($selected); ?>>
                            <?php echo e(ucfirst($availableLanguage)); ?></option>
                        <?php
               } ?>
                    </select>
                </div>
                <?php } ?>
            </div>
			
            <div class="col-md-12">
                <?php $value = (isset($lead) ? $lead->description : ''); ?>
                <?php echo render_textarea('description', 'lead_description', $value); ?>
				
				<?php $value = (isset($lead) ? $lead->subject : ''); ?>
                <?php echo render_input('subject', 'Lead Subject', $value);  ?>
				
				
                <div class="row">
                    <div class="col-md-12">
                        <?php if (!isset($lead)) { ?>
                        <div class="lead-select-date-contacted hide">
                            <?php echo render_datetime_input('custom_contact_date', 'lead_add_edit_datecontacted', '', ['data-date-end-date' => date('Y-m-d')]); ?>
                        </div>
                        <?php } else { ?>
                        <?php echo render_datetime_input('lastcontact', 'leads_dt_last_contact', _dt($lead->lastcontact), ['data-date-end-date' => date('Y-m-d')]); ?>
                        <?php } ?>
                        <?php /*?><div class="checkbox-inline checkbox checkbox-primary<?php if (isset($lead)) {
                   echo ' hide';
               } ?><?php if (isset($lead) && (is_lead_creator($lead->id) || staff_can('edit',  'leads'))) {
                   echo ' lead-edit';
               } ?>">
                            <input type="checkbox" name="is_public" <?php if (isset($lead)) {
                   if ($lead->is_public == 1) {
                       echo 'checked';
                   }
               }; ?> id="lead_public">
                            <label for="lead_public"><?php echo _l('lead_public'); ?></label>
                        </div><?php */?>
                        <?php 
						/*if (!isset($lead)) { ?>
                        <div class="checkbox-inline checkbox checkbox-primary">
                            <input type="checkbox" name="contacted_today" id="contacted_today" checked>
                            <label for="contacted_today"><?php echo _l('lead_add_edit_contacted_today'); ?></label>
                        </div>
                        <?php }
						*/ ?>
                    </div>
                </div>
            </div>
			<div class="clearfix"></div>
			<div class="col-md-12">
			<div class=""><h4>Custom Fields</h4></div>
            
        
			<div id="custom-fields">
      
	  <?php
	  if ((isset($lead) && !empty($lead->custom_field))) {
      // Convert to associative array
      $custom_field_array = json_decode($lead->custom_field, true);
	  
	  foreach ($custom_field_array as $key => $value) {
	  ?>
	  <div class="field-group form-group row">
<div class="col-sm-3">
<input type="text" class="form-control" name="custom_field_name[]" placeholder="<?php echo $key;?>" value="<?php echo $key;?>" required>
</div>
<div class="col-sm-7">
<input type="text" class="form-control" name="custom_field_value[]" placeholder="<?php echo $key;?>" value="<?php echo $value;?>" required>
</div>
<div class="col-sm-2">
<a href="#" class="remove text-danger" title="Remove"><i class="fa fa fa-times"></i></a>
</div>
</div>
	  <?php
	  }
	  }
	  ?>
	  <?php /*?><div class="field-group form-group row">
	  <div class="col-sm-3">
        <input type="text" class="form-control" name="custom_field_name[]" placeholder="Field Name" required>
		</div>
	  <div class="col-sm-7">
        <input type="text" class="form-control" name="custom_field_value[]" placeholder="Field Value" required>
		</div>
	  <div class="col-sm-2">
		<a href="#" class="remove text-danger" title="Remove"><i class="fa fa fa-times"></i></a>
		</div>
		</div><?php */?>
     
    </div>
    <button type="button" id="add-field">Add Field</button>
	
    <br><br>
			
			
			</div>
            <div class="col-md-12 mtop15">
                <?php $rel_id = (isset($lead) ? $lead->id : false); ?>
                <?php echo render_custom_fields('leads', $rel_id); ?>
            </div>
            <div class="clearfix"></div>
        </div>
	<?php if (isset($lead)) { ?>
    <div class="lead-latest-activity tw-mb-3 lead-view">
        <div class="lead-info-heading">
            <h4><?php echo _l('lead_latest_activity'); ?></h4>
        </div>
        <div id="lead-latest-activity" class="pleft5"></div>
    </div>
    <?php } ?>
	
	
	
	
    <?php if ($lead_locked == false) { ?>
    <div class="lead-edit<?php echo isset($lead) ? ' hide' : ''; ?>">
        <hr class="-tw-mx-4 tw-border-neutral-200" />
        <button type="submit" class="btn btn-primary pull-right lead-save-btn" id="lead-form-submit">
            <?php echo _l('submit'); ?>
        </button>
        <button type=" button" class="btn btn-default pull-right mright5" data-dismiss="modal">
            <?php echo _l('close'); ?>
        </button>
    </div>
    <?php } ?>
    <div class="clearfix"></div>
    <?php echo form_close(); ?>
	</div>
	 <?php if(isset($lead->id)&&$lead->id){ ?>
	<div class="col-md-6 col-xs-12 lead-information-col box-shadow">
                <div class="top-lead-menu">
				<?php if (isset($lead)) { ?>
				<div class="horizontal-scrollable-tabs preview-tabs-top panel-full-width-tabs mbot20">
					<div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
					<div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
					<?php /*?>============Tab Section=========<?php */?>
					<div class="horizontal-tabs">
						<ul style="background: #a0bec6;" class="nav-tabs-horizontal nav nav-tabs<?php if (!isset($lead)) {
							echo ' lead-new';
						} ?>" role="tablist">
						<li role="presentation" class="active">
								<a href="#lead_activity" aria-controls="lead_activity" role="tab" data-toggle="tab">
									<?php echo _l('lead_add_edit_activity'); ?>
								</a>
							</li>
							
							<?php if (isset($lead)) { ?>
							<?php if (count($mail_activity) > 0 || isset($show_email_activity) && $show_email_activity) { ?>
							<li role="presentation">
								<a href="#tab_email_activity" aria-controls="tab_email_activity" role="tab"
									data-toggle="tab">
									<?php echo hooks()->apply_filters('lead_email_activity_subject', _l('lead_email_activity')); ?>
								</a>
							</li>
							<?php } ?>
							<?php /*?><li role="presentation">
								<a href="#tab_proposals_leads"
									onclick="initDataTable('.table-proposals-lead', admin_url + 'proposals/proposal_relations/' + <?php echo e($lead->id); ?> + '/lead','undefined', 'undefined','undefined',[6,'desc']);"
									aria-controls="tab_proposals_leads" role="tab" data-toggle="tab">
									<?php echo _l('proposals');

									if ($total_proposals > 0) {
										echo ' <span class="badge">' . $total_proposals . '</span>';
									}
									?>
								</a>
							</li>
							<li role="presentation">
								<a href="#tab_tasks_leads"
									onclick="init_rel_tasks_table(<?php echo e($lead->id); ?>,'lead','.table-rel-tasks-leads');"
									aria-controls="tab_tasks_leads" role="tab" data-toggle="tab">
									<?php echo _l('tasks');
									if ($total_tasks > 0) {
										echo ' <span class="badge">' . $total_tasks . '</span>';
									}
									?>
								</a>
							</li><?php */?>
							<li role="presentation">
								<a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab">
									<?php echo _l('lead_attachments');
									if ($total_attachments > 0) {
										echo ' <span class="badge">' . $total_attachments . '</span>';
									}
									?>
								</a>
							</li>
							<li role="presentation">
								<a href="#lead_reminders"
									onclick="initDataTable('.table-reminders-leads', admin_url + 'misc/get_reminders/' + <?php echo e($lead->id); ?> + '/' + 'lead', undefined, undefined,undefined,[1, 'asc']);"
									aria-controls="lead_reminders" role="tab" data-toggle="tab">
									<?php echo _l('leads_reminders_tab');
									if ($total_reminders > 0) {
										echo ' <span class="badge">' . $total_reminders . '</span>';
									}
									?>
								</a>
							</li>
							<li role="presentation">
								<a href="#lead_notes" aria-controls="lead_notes" role="tab" data-toggle="tab">
									<?php echo _l('lead_add_edit_notes');
									if ($total_notes > 0) {
										echo ' <span class="badge">' . $total_notes . '</span>';
									}
									?>
								</a>
							</li>
							<?php //if(isset($lead->is_deal)&&$lead->is_deal==1){ ?>
						<li role="presentation">
								<a href="#tab_lead_task" aria-controls="tab_lead_task" role="tab"
									data-toggle="tab">
									<?php echo _l('Task'); 
									if (count($deal_task) > 0) {
										echo ' <span class="badge">' . count($deal_task) . '</span>';
									}
									?>
								</a>
							</li>
						<?php //} ?>
							
							<?php if (is_gdpr() && (get_option('gdpr_enable_lead_public_form') == '1' || get_option('gdpr_enable_consent_for_leads') == '1')) { ?>
							<li role="presentation">
								<a href="#gdpr" aria-controls="gdpr" role="tab" data-toggle="tab">
									<?php echo _l('gdpr_short'); ?>
								</a>
							</li>
							<?php } ?>
							<?php } ?>
							<?php
							if(e($lead->source)==4)
							{
							?>
							<li role="presentation">
								<a href="leads/telegram/<?php echo ($lead->client_id);?>"><?php echo _l('lead_conversion');?></a>
								<?php /*?>
								<a href="#tab_leads_conversions" onclick="" aria-controls="tab_leads_conversions" role="tab" data-toggle="tab"><?php echo _l('lead_conversion');?></a>
							<?php */?>
							</li>
							<?php
							}
							elseif(e($lead->source)==5)
							{
							?>
							<li role="presentation">
								<a href="leads/webchat/<?php echo ($lead->client_id);?>"><?php echo _l('lead_conversion');?></a>
							</li>
							<?php
							}
							?>
							<?php hooks()->do_action('after_lead_lead_tabs', $lead ?? null); ?>
						</ul>
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="tab-content">
				<!-- from leads modal -->
				<?php //if(isset($lead->is_deal)&&$lead->is_deal==1){ ?>
				<div role="tabpanel" class="tab-pane " id="tab_lead_task">
<?php echo form_open(admin_url('leads/add_deal_task/' . $lead->id), ['id' => 'lead-notes']); ?>
<?php 
$this->db->select('id,name,');
$this->db->order_by('statusorder', 'asc');
$data['taskstatus']   = $this->db->get(db_prefix() . 'task_status')->result_array(); 
?>
<div class="form-group">
<label for="date" class="control-label"> 
<small class="req text-danger">* </small>Task Title</label>

<select name="task_type" id="task_type" class="form-control" required>
<option value="">Select Task Title</option>
<?php  foreach ($data['taskstatus'] as $item) { ?>
<option value="<?=$item['id'];?>"><?=$item['name'];?></option>
<?php  } ?>
</select>

</div>

<div class="form-group" id="other_task_title" style="display: none;"><label for="task_title" class="control-label"> <small class="req text-danger">* </small>Other Task Title</label><div><input type="text" id="task_title" name="task_title" class="form-control"  autocomplete="off"></div></div>

<div class="form-group" app-field-wrapper="date"><label for="date" class="control-label"> <small class="req text-danger">* </small>Date to be notified</label><div class="input-group date"><input type="text" id="date" name="date" class="form-control datetimepicker" data-date-min-date="<?php echo date('Y-m-d');?>" data-step="30" value="" autocomplete="off" required><div class="input-group-addon">
    <i class="fa-regular fa-calendar calendar-icon"></i>
</div></div></div>

<div class="form-group" app-field-wrapper="description">
<label for="description" class="control-label"> 
<small class="req text-danger">* </small>Description</label>
<textarea id="description" name="description" class="form-control" rows="4" required></textarea>
</div>

<button type="submit" class="btn btn-primary pull-right"><?php echo _l('Add Task'); ?></button>
<?php echo form_close(); ?>
					<div class="clearfix"></div>
					
<div class="clearfix"></div>
					<hr />
					<div class="activity-feed" style="max-height: 400px; overflow-y: auto;">
					<?php
					$len	= count($deal_task);
					$i		= 0;
					foreach ($deal_task as $task) { ?>
					<?php $tasktype=$this->leads_model->get_task_type($task['task_type']); ?>
<?php 
$task_bg="";					
$assignDateTime = new DateTime($task['date']);
$currentDateTime = new DateTime();
if ($currentDateTime > $assignDateTime) {
    $task_bg="#ef4444";
} 
?>
					<div class="media8 lead-note feed-item " >
						<a href="<?php echo admin_url('profile/' . $task['staff']); ?>" target="_blank">
							<?php echo staff_profile_image($task['staff'], ['staff-profile-image-small', 'pull-left mright10']); ?>
						</a>
						<div class="media-body " >
							
	
							
<h5 class="media-heading tw-font-semibold tw-mb-0">

<?php if ($task['task_status']==0) { ?>								
<div class="btn-group pull-right mleft5">
<i class="fa-solid fa-circle-check text-warning fa-2x mright10 change_task_status" data-tid="<?php echo $task['id'];?>"></i>				
</div>
<?php }else{ ?>
<div class="btn-group pull-right mleft5">
<i class="fa-solid fa-circle-check text-success fa-2x mright10" ></i>				
</div>
<?php } ?>
								<?php if (!empty($task['date_contacted'])) { ?>
									<span data-toggle="tooltip"
										data-title="<?php echo e(_dt($task['date'])); ?>">
										<i class="fa fa-phone-square text-success" aria-hidden="true"></i>
									</span>
									<?php } ?>
									<?php echo e(get_staff_full_name($task['staff'])); ?> <?php if (isset($tasktype[0]['name']) && $tasktype[0]['color']) { ?> - <span style="color:<?php echo $tasktype[0]['color'];?>; font-weight:bolder;">Type : <?php echo $tasktype[0]['name'];?> <?php } ?> <?php if (isset($task['task_title']) && $task['task_title']) { echo " - (".$task['task_title'].")"; } ?></span>
									</h5>
<p><?php echo $task['description']; ?></p>
<span class="tw-text-sm tw-text-neutral-500" style="color:<?php echo $task_bg;?>"> Task Time : <?php echo $task['date']; ?> Task Added on : <?php echo $task['dateadded']; ?> </span>
									
									
							

							
							
						</div>
						<?php if ($i >= 0 && $i != $len - 1) {
							//echo '<hr />';
						}
						?>
					</div>
					<?php $i++; } ?>
				</div></div>
				<?php //} ?>
				
				
				
				<?php if (isset($lead)) { ?>
				<?php if (count($mail_activity) > 0 || isset($show_email_activity) && $show_email_activity) { ?>
				<div role="tabpanel" class="tab-pane" id="tab_email_activity">
					<?php hooks()->do_action('before_lead_email_activity', ['lead' => $lead, 'email_activity' => $mail_activity]); ?>
					<?php foreach ($mail_activity as $_mail_activity) { ?>
					<div class="lead-email-activity">
						<div class="media-left">
							<i class="fa-regular fa-envelope"></i>
						</div>
						<div class="media-body">
							<h4 class="bold no-margin lead-mail-activity-subject">
								<?php echo e($_mail_activity['subject']); ?>
								<br />
								<small
									class="text-muted display-block mtop5 font-medium-xs"><?php echo e(_dt($_mail_activity['dateadded'])); ?></small>
							</h4>
							<div class="lead-mail-activity-body">
								<hr />
								<?php echo process_text_content_for_display($_mail_activity['body']); ?>
							</div>
							<hr />
						</div>
					</div>
					<div class="clearfix"></div>
					<?php } ?>
					<?php hooks()->do_action('after_lead_email_activity', ['lead_id' => $lead->id, 'emails' => $mail_activity]); ?>
				</div>
				<?php } ?>
				<?php if (is_gdpr() && (get_option('gdpr_enable_lead_public_form') == '1' || get_option('gdpr_enable_consent_for_leads') == '1' || (get_option('gdpr_data_portability_leads') == '1') && is_admin())) { ?>
				<div role="tabpanel" class="tab-pane" id="gdpr">
					<?php if (get_option('gdpr_enable_lead_public_form') == '1') { ?>
					<a href="<?php echo e($lead->public_url); ?>" target="_blank" class="mtop5">
						<?php echo _l('view_public_form'); ?>
					</a>
					<?php } ?>
					<?php if (get_option('gdpr_data_portability_leads') == '1' && is_admin()) { ?>
					<?php
					if (get_option('gdpr_enable_lead_public_form') == '1') {
						echo ' | ';
					}
					?>
					<a href="<?php echo admin_url('leads/export/' . $lead->id); ?>">
						<?php echo _l('dt_button_export'); ?>
					</a>
					<?php } ?>
					<?php if (get_option('gdpr_enable_lead_public_form') == '1' || (get_option('gdpr_data_portability_leads') == '1' && is_admin())) { ?>
					<hr class="-tw-mx-3.5" />
					<?php } ?>
					<?php if (get_option('gdpr_enable_consent_for_leads') == '1') { ?>
					<h4 class="no-mbot">
						<?php echo _l('gdpr_consent'); ?>
					</h4>
					<?php $this->load->view('admin/gdpr/lead_consent'); ?>
					<hr />
					<?php } ?>
				</div>
				<?php } ?>
				<div role="tabpanel" class="tab-pane active" id="lead_activity">
					<div>
						<div class="activity-feed">
							<?php foreach ($activity_log as $log) { ?>
							<div class="feed-item">
								<div class="date">
									<span class="text-has-action" data-toggle="tooltip"
										data-title="<?php echo e(_dt($log['date'])); ?>">
										<?php echo e(time_ago($log['date'])); ?>
									</span>
								</div>
								<div class="text">
									<?php if ($log['staffid'] != 0) { ?>
									<a href="<?php echo admin_url('profile/' . $log['staffid']); ?>">
										<?php 
											echo staff_profile_image($log['staffid'], ['staff-profile-xs-image pull-left mright5']);
										?>
									</a>
									<?php
								}
								$additional_data = '';
								if (!empty($log['additional_data'])) {
									$additional_data = unserialize($log['additional_data']);
									echo ($log['staffid'] == 0) ? _l($log['description'], $additional_data) : e($log['full_name']) . ' - ' . _l($log['description'], $additional_data);
								} else {
									echo e($log['full_name']) . ' - ';
	
									if ($log['custom_activity'] == 0) {
										echo e(_l($log['description']));
									} else {
										echo process_text_content_for_display(_l($log['description'], '', false));
									}
								}
								?>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="col-md-12">
							<?php echo render_textarea('lead_activity_textarea', '', '', ['placeholder' => _l('enter_activity')], [], 'mtop15'); ?>
							<div class="text-right">
								<button id="lead_enter_activity"
									class="btn btn-primary"><?php echo _l('submit'); ?></button>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="tab_proposals_leads">
					<?php if (staff_can('create', 'proposals')) { ?>
					<a href="<?php echo admin_url('proposals/proposal?rel_type=lead&rel_id=' . $lead->id); ?>"
						class="btn btn-primary mbot25"><?php echo _l('new_proposal'); ?></a>
					<?php } ?>
					<?php if (total_rows(db_prefix() . 'proposals', ['rel_type' => 'lead', 'rel_id' => $lead->id]) > 0 && (staff_can('create', 'proposals') || staff_can('edit', 'proposals'))) { ?>
					<a href="#" class="btn btn-primary mbot25" data-toggle="modal"
						data-target="#sync_data_proposal_data"><?php echo _l('sync_data'); ?></a>
					<?php $this->load->view('admin/proposals/sync_data', ['related' => $lead, 'rel_id' => $lead->id, 'rel_type' => 'lead']); ?>
					<?php } ?>
					<?php
					$table_data = [
						_l('proposal') . ' #',
						_l('proposal_subject'),
						_l('proposal_total'),
						_l('proposal_date'),
						_l('proposal_open_till'),
						_l('tags'),
						_l('proposal_date_created'),
						_l('proposal_status'), ];

					$custom_fields = get_custom_fields('proposal', ['show_on_table' => 1]);
					foreach ($custom_fields as $field) {
						array_push($table_data, [
							'name' => $field['name'],
							'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
						]);
					}
					$table_data = hooks()->apply_filters('proposals_relation_table_columns', $table_data);
					render_datatable($table_data, 'proposals-lead', [], [
						'data-last-order-identifier'=> 'proposals-relation',
						'data-default-order'		=> get_table_last_order('proposals-relation'),
					]);
				?>
				</div>
				<div role="tabpanel" class="tab-pane" id="tab_tasks_leads">
					<?php init_relation_tasks_table(['data-new-rel-id' => $lead->id, 'data-new-rel-type' => 'lead'], 'tasksFilters'); ?>
				</div>
				<div role="tabpanel" class="tab-pane" id="lead_reminders">
					<a href="#" data-toggle="modal" class="btn btn-default"
						data-target=".reminder-modal-lead-<?php echo e($lead->id); ?>"><i class="fa-regular fa-bell"></i>
						<?php echo _l('lead_set_reminder_title'); ?></a>
					<hr />
					<?php render_datatable([ _l('reminder_description'), _l('reminder_date'), _l('reminder_staff'), _l('reminder_is_notified')], 'reminders-leads'); ?>
				</div>
				<div role="tabpanel" class="tab-pane" id="attachments">
				
					<?php echo form_open('admin/leads/add_lead_attachment', ['class' => 'dropzone mtop15 mbot15', 'id' => 'lead-attachment-upload']); ?>
					
					<?php echo form_close(); ?>
					
					<?php if (get_option('dropbox_app_key') != '') { ?>
					<hr />
					<div class=" pull-left">
						<?php if (count($lead->attachments) > 0) { ?>
						<a href="<?php echo admin_url('leads/download_files/' . $lead->id); ?>" class="bold">
							<?php echo _l('download_all'); ?> (.zip)
						</a>
						<?php } ?>
					</div>
					<div class="tw-flex tw-justify-end tw-items-center tw-space-x-2">
						<button class="gpicker">
							<i class="fa-brands fa-google" aria-hidden="true"></i>
							<?php echo _l('choose_from_google_drive'); ?>
						</button>
						<div id="dropbox-chooser-lead"></div>
					</div>
					<div class=" clearfix"></div>
					<?php } ?>
					<?php if (count($lead->attachments) > 0) { ?>
					<div class="mtop20" id="lead_attachments">
						<?php $this->load->view('admin/leads/leads_attachments_template', ['attachments' => $lead->attachments]); ?>
					</div>
					<?php } ?>
				</div>
				<div role="tabpanel" class="tab-pane" id="lead_notes">
					<?php echo form_open(admin_url('leads/add_note/' . $lead->id), ['id' => 'lead-notes']); ?>
					<div class="form-group">
						<textarea id="lead_note_description" name="lead_note_description" class="form-control"
							rows="4"></textarea>
					</div>
					<div class="lead-select-date-contacted hide">
						<?php echo render_datetime_input('custom_contact_date', 'lead_add_edit_datecontacted', '', ['data-date-end-date' => date('Y-m-d')]); ?>
					</div>
					<div class="radio radio-primary">
						<input type="radio" name="contacted_indicator" id="contacted_indicator_yes" value="yes">
						<label
							for="contacted_indicator_yes"><?php echo _l('lead_add_edit_contacted_this_lead'); ?></label>
					</div>
					<div class="radio radio-primary">
						<input type="radio" name="contacted_indicator" id="contacted_indicator_no" value="no" checked>
						<label for="contacted_indicator_no"><?php echo _l('lead_not_contacted'); ?></label>
					</div>
					<button type="submit"
						class="btn btn-primary pull-right"><?php echo _l('lead_add_edit_add_note'); ?></button>
					<?php echo form_close(); ?>
					<div class="clearfix"></div>
					<hr />
					<?php
					$len	= count($notes);
					$i		= 0;
					foreach ($notes as $note) { ?>
					<div class="media lead-note">
						<a href="<?php echo admin_url('profile/' . $note['addedfrom']); ?>" target="_blank">
							<?php echo staff_profile_image($note['addedfrom'], ['staff-profile-image-small', 'pull-left mright10']); ?>
						</a>
						<div class="media-body">
							<?php if ($note['addedfrom'] == get_staff_user_id() || is_admin()) { ?>
							<a href="#" class="pull-right text-danger"
								onclick="delete_lead_note(this,<?php echo e($note['id']); ?>, <?php echo e($lead->id); ?>);return false;">
	
								<i class="fa fa fa-times"></i></a>
							<a href="#" class="pull-right mright5"
								onclick="toggle_edit_note(<?php echo e($note['id']); ?>);return false;">
								<i class="fa-regular fa-pen-to-square"></i></a>
							<?php } ?>
	
							<a href="<?php echo admin_url('profile/' . $note['addedfrom']); ?>" target="_blank">
								<h5 class="media-heading tw-font-semibold tw-mb-0">
								<?php if (!empty($note['date_contacted'])) { ?>
									<span data-toggle="tooltip"
										data-title="<?php echo e(_dt($note['date_contacted'])); ?>">
										<i class="fa fa-phone-square text-success" aria-hidden="true"></i>
									</span>
									<?php } ?>
									<?php echo e(get_staff_full_name($note['addedfrom'])); ?>
									</h5>
									<span class="tw-text-sm tw-text-neutral-500">
										<?php echo e(_l('lead_note_date_added', _dt($note['dateadded']))); ?>
									</span>
							</a>

							<div data-note-description="<?php echo e($note['id']); ?>" class="text-muted mtop10"><?php echo process_text_content_for_display($note['description']); ?></div>
							<div data-note-edit-textarea="<?php echo e($note['id']); ?>" class="hide mtop15">
								<?php echo render_textarea('note', '', $note['description']); ?>
								<div class="text-right">
									<button type="button" class="btn btn-default"
										onclick="toggle_edit_note(<?php echo e($note['id']); ?>);return false;"><?php echo _l('cancel'); ?></button>
									<button type="button" class="btn btn-primary"
										onclick="edit_note(<?php echo e($note['id']); ?>);"><?php echo _l('update_note'); ?></button>
								</div>
							</div>
						</div>
						<?php if ($i >= 0 && $i != $len - 1) {
							echo '<hr />';
						}
						?>
					</div>
					<?php $i++; } ?>
				</div>
				<?php
				if(e($lead->source)==4)
				{
				?>
				<div role="tabpanel" class="tab-pane" id="tab_leads_conversions">
					<div class="lead_conversion_list" id="lead_conversion_list">
						<?php //require_once "./telegram.php";?>
					</div>
					<div class="clearfix"></div>

					<div class="col-md-12">
						<?php echo render_input('telegram_send_message', 'send_as_plain_text', '', 'text'); ?>
						<div class="text-right">
							<button id="send_telegram_conv" data-url="/crm/telegram.php" class="btn btn-primary"><?php echo _l('send'); ?></button>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php } ?>
				<?php hooks()->do_action('after_lead_tabs_content',$lead??null);?>
			</div>
					
            </div>
	<?php } ?>
    </div>
	
	
    
<div class="modal fade" id="dealModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg " role="document">
<?php echo form_open((isset($lead) ? admin_url('leads/leadtodeal/' . $lead->id) : admin_url('leads/leadtodeal')), ['id' => 'leadtodeal_form']); ?> 
	  
<?php 
$this->db->select('id,name,');
$selected_deals=1;
$dstatus="Convert to Deal";

if(isset($lead->deal_status)&&$lead->deal_status==0){
$this->db->where('id', 1);
$selected_deals=1;
}elseif(isset($lead->deal_status)&&$lead->deal_status==1){
$this->db->where('id', 2);
$dstatus="Convert to Document";
$selected_deals=2;
}elseif(isset($lead->deal_status)&&$lead->deal_status==2){
$this->db->where('id', 3);
$dstatus="Convert to UW";
$selected_deals=3;
}elseif(isset($lead->deal_status)&&$lead->deal_status==3){
$this->db->where('id', 4);
$dstatus="Send Quotation";
$selected_deals=4;
}


$this->db->order_by('statusorder', 'asc');
$data['dealsstatus']   = $this->db->get(db_prefix() . 'deals_status')->result_array(); 
//echo $this->db->last_query();
?> 
        <div class="modal-content tw-bg-info-100">
            <div class="modal-header" style="background-color: rgb(186 230 253 / 1) !important;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">#<?php echo $lead->id;?> - <?php echo $dstatus; ?> [<?php echo $lead->deal_status;?>]</h4>
            </div>
            <div class="modal-body">
                <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                
                <input type="hidden" name="deal_id" id="deal_id" value="<?php echo $lead->id;?>"  />
				
<div>


<div class="row">

<div class="col-md-12">
<?php /*?><label for="dealsstatus"><?php echo _l('Deal Status'); ?></label> <?php */?>
<select name="deal_status" id="deal_status" class="form-control pull-right mleft5" required style="width:200px;">
<?php /*?><option value="">Select Deal Status</option><?php */?>
<?php  foreach ($data['dealsstatus'] as $rs) { ?>
<option value="<?=$rs['id'];?>" <?php if($rs['id']==$selected_deals){ ?> selected="selected" <?php } ?>><?=$rs['name'];?></option>
<?php  } ?>
</select>

</div>
<?php if(isset($lead->deal_status)&&$lead->deal_status==0){ ?>
<input type="hidden" name="vtype" value="deal" />
<input type="hidden" name="country" value="<?php echo isset($lead) ? $lead->country  : '';?> " />
<div class="col-md-4">
 <?php $value = (isset($lead) ? $lead->name  : ''); ?>
 <?php echo render_input('name', 'Full Name', $value,'text',['required' => 'true']); ?>  
 </div>    
<div class="col-md-4">
 <?php $value = (isset($lead) ? $lead->email : ''); ?>
 <?php echo render_input('email', 'Email ID', $value,'email',['required' => 'true']);  ?>  
 </div>
<div class="col-md-4">
<div class="col-md-4 tw-px-0">
 <?php $value = (isset($lead) ? $lead->country_code : ''); ?>
 <?php echo render_input('country_code', 'Country Code', $value,'text',['required' => 'true']); ?>  
 </div><div class="col-md-8 tw-px-0">
 <?php $value = (isset($lead) ? $lead->phonenumber : ''); ?>
 <?php echo render_input('phonenumber', 'Phone Number', $value,'number',['required' => 'true']); ?> 
  </div>
 </div>
 
<div class="col-md-4">
 <?php $value = (isset($lead) ? $lead->company : ''); ?>
 <?php echo render_input('company', 'Business Name', $value,'text',['required' => 'true']); //,['required' => 'true'] ?>  
 </div>    
<div class="col-md-4">
 <?php $value = (isset($lead) ? $lead->website : ''); ?>
 <?php echo render_input('website', 'Business URL', $value,'text',['required' => 'true']); //,['required' => 'true'] ?>  
 </div>

 <div class="col-md-4">
 <?php $value = (isset($lead) ? $lead->BusinessNature : ''); ?>
 <?php echo render_input('BusinessNature', 'Business Nature', $value,'text',['required' => 'true']); //,['required' => 'true'] ?>  
 </div>
<div class="col-md-4">
 <?php $value = (isset($lead) ? $lead->IncorporationCountry : ''); ?>
  
  <?php 
  
  $selected = (isset($lead->IncorporationCountry) ? $lead->IncorporationCountry : '');
  echo render_select('IncorporationCountry', $countries, [ 'country_id', [ 'short_name']], 'Incorporation Country', $selected, ['data-none-selected-text' => _l('dropdown_non_selected_tex')]); ?>  
 </div>    
<div class="col-md-4">
 <?php $value = (isset($lead) ? $lead->MonthlyVolume : ''); ?>
 <?php echo render_input('MonthlyVolume', 'Monthly Volume', $value,'text',['required' => 'true']); //lead_company to Business Name ?>  
 </div>
<div class="col-md-4">
 <?php $value = (isset($lead) ? $lead->AverageProductPrice : ''); ?>
 <?php echo render_input('AverageProductPrice', 'Average Product Price', $value,'text',['required' => 'true']); //lead_company to Business Name ?>  
 </div>
 <div class="col-md-12">
 <?php $value = (isset($lead) ? $lead->address : ''); ?>
 <?php echo render_textarea('address', 'Address', $value,['required' => 'true']); ?>
 </div>
<div class="col-md-12">
 <?php $value = (isset($lead) ? $lead->description : ''); ?>
 <?php echo render_textarea('description', 'Business Description', $value,['required' => 'true']); ?>
 </div>

 <div class="col-md-12 checkbox last:tw-mb-0">
 <input type="checkbox" checked="" class="capability" id="inserttocustomer" name="inserttocustomer" value="yes">
<label for="bulk_pdf_exporter_view">Add to Contact</label>
</div>


<?php 
}elseif(isset($lead->deal_status)&&$lead->deal_status==1){ ?>
<input type="hidden" name="vtype" value="hot" />
 
<div class="col-md-3">
 <?php
echo render_select('target_countries', $countries, [ 'country_id', [ 'short_name']], 'Target Country'); 
?>
 </div>  
 <div class="col-md-3">
 <?php echo render_input('products_services', 'Products / Services', '','',['required' => 'true']); //lead_company to Business Name ?>  
 </div>   
<div class="col-md-3">
 <?php echo render_input('descriptor', 'Descriptor', ''); //lead_company to Business Name ?>  
 </div>
<div class="col-md-3">
 <label for="date" class="control-label">Processing History</label>
 <select name="processing_history" id="processing_history" class="form-control" required>
<option value="">Select Processing History</option>
<option value="Durations">Durations</option>
<option value="Issues">Issues</option>
<option value="Prior processors">Prior processors</option>
</select>
 </div>
 

<div class="col-md-12">
  <label for="dealsstatus">&nbsp;<?php echo _l('SPOC Info'); ?></label>
  <div class="row panel_s tw-p-2 tw-mx-1">
  
<div class="col-md-3">
 <?php echo render_input('spocname', 'Name', '','text',['required' => 'true']); ?>  
 </div>
<div class="col-md-3">
 <?php echo render_input('spocphone', 'Phone', '','text',['required' => 'true']); ?>  
 </div>    
<div class="col-md-3">
 <?php echo render_input('spocemail', 'Email', '','text',['required' => 'true']); ?>  
 </div>
<div class="col-md-3">
 <?php echo render_input('spocim', 'IM', '','text',['required' => 'true']); ?>  
 </div>
</div>
</div>

<div class="col-md-12">
  <label for="dealsstatus">&nbsp;<?php echo _l('Customer Service'); ?></label>
  <div class="row panel_s tw-p-2 tw-mx-1">
  
<div class="col-md-4">
<?php echo render_input('customername', 'Name', '','text',['required' => 'true']); //lead_company to Business Name ?>  
 </div>
<div class="col-md-4">
 <?php echo render_input('customertollfree', 'Toll-Free', '','text',['required' => 'true']); //lead_company to Business Name ?>  
 </div>    
<div class="col-md-4">
 <?php echo render_input('customeremail', 'Email', '','text',['required' => 'true']); //lead_company to Business Name ?>  
 </div>

</div>
</div>

<div class="col-md-12">
  <label for="dealsstatus">&nbsp;<?php echo _l('Website Info'); ?></label>
  <div class="row panel_s tw-p-2 tw-mx-1">
  
<div class="col-md-4">
 <?php echo render_input('website_info[website_traffic_d]', 'Website Traffic (D) ', '','text',['required' => 'true']); ?>  
 </div>
<div class="col-md-4">
 <?php echo render_input('website_info[website_traffic_m]', 'Website Traffic (M) ', '','text',['required' => 'true']); ?>  
 </div>    
<div class="col-md-4">
 <?php echo render_input('website_info[website_age]', 'Website Age', '','text',['required' => 'true']); ?>  
 </div>
<div class="col-md-4">
 <?php echo render_input('website_info[contact_us]', 'Contact Us', '','text',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">
 <?php echo render_input('website_info[terms_conditions]', 'Terms & Conditions', '','text',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">
 <?php echo render_input('website_info[privacy_policy]', 'Privacy Policy', '','text',['required' => 'true']); ?>  
 </div>
</div>
</div>


<div class="col-md-12">
  <label for="dealsstatus">&nbsp;<?php echo _l('Old History'); ?></label>
  <div class="row panel_s tw-p-2 tw-mx-1">
  
<div class="col-md-3">
 <?php echo render_input('old_history[current_processor]', 'Current Processor', ''); ?>  
 </div>
<div class="col-md-3">
 <?php echo render_input('old_history[duration]', 'Duration', ''); ?>  
 </div>    
<div class="col-md-3">
 <?php echo render_input('old_history[total_transaction_monthly]', 'Total Transaction (Monthly)', ''); ?>  
 </div>
<div class="col-md-3">
 <?php echo render_input('old_history[charge_back_ratio]', 'Charge Back Ratio', ''); ?>  
 </div>
 <div class="col-md-3">
 <?php echo render_input('old_history[total_no_of_refunds]', 'Total No. of Refunds', ''); ?>  
 </div>
 <div class="col-md-3">
 <?php echo render_input('old_history[reason_of_switching]', 'Reason of Switching', ''); ?>  
 </div>

  <div class="col-md-3">
 <?php echo render_input('old_history[existing_processor_rates]', 'Existing Processor Rates', ''); ?>  
 </div>
 <div class="col-md-3">
 <?php echo render_input('old_history[product_prices]', 'Product Prices', ''); ?>  
 </div>
   <div class="col-md-12">
 <?php echo render_textarea('old_history[description]', 'Description'); ?>
 </div>
</div>
</div>



<div class="col-md-12">
<label for="transinfo">&nbsp;<?php echo _l('Accept Cards'); ?></label>
  <div class="row panel_s tw-p-2 tw-mx-1">
        <div class="col-md-6">
  
        <strong> Does merchant currently accept cards?</strong>
        <input type="radio" name="accept_cards" value="1">
        <label for="status">Yes </label>
		<input type="radio" name="accept_cards" value="0" checked>
        <label for="status">No </label> 
	</div>	
		<div class="col-md-6">
		<div class="form-group " id="cardsDiv" style="display: none;">
		<?php echo render_input('card_tdr', 'TDR', ''); ?>
		</div>
		
 </div>


  </div>
</div>
<div class="col-md-12">
<label for="transinfo">&nbsp;<?php echo _l('Transaction Information'); ?></label>
  <div class="row panel_s tw-p-2 tw-mx-1">
<div class="col-md-3">
<?php echo render_input('estimated_total_sales', 'Estimated total sales/month', '','text',['required' => 'true']); //lead_company to Business Name ?>  
 </div>
<div class="col-md-3">
 <?php echo render_input('number_of_transactions', 'Number of transactions/month ', '','text',['required' => 'true']); //lead_company to Business Name ?>  
 </div>    
<div class="col-md-3">
 <?php echo render_input('minimum_ticket_amount', 'Minimum ticket amount', '','text',['required' => 'true']); //lead_company to Business Name ?>  
 </div>
 <div class="col-md-3">
 <?php echo render_input('maximum_ticket_amount', 'Maximum ticket amount', '','text',['required' => 'true']); //lead_company to Business Name ?>  
 </div>
 </div>
</div>
<?php 
}elseif(isset($lead->deal_status)&&$lead->deal_status==2){ ?>
<input type="hidden" name="vtype" value="doc" />

<div class="col-md-12">
  <label for="dealsstatus">&nbsp;<?php echo 'Ownership Information (Owner 1) '; ?></label>
  <div class="row panel_s tw-p-2 tw-mx-1">
  
<div class="col-md-4">
 
 <?php echo render_input('ownership_name', 'Full Name', '','text',['required' => 'true']); ?>  
 </div>
<div class="col-md-4">
 
 <?php echo render_input('ownership_share', 'Ownership Share (%)', '','number',['required' => 'true']); ?>  
 </div>    
<div class="col-md-4">

 <?php echo render_input('ownership_address', 'Address', '','text',['required' => 'true']); ?>  
 </div>
 
 <div class="col-md-4">

 <?php echo render_input('ownership_city', 'City', '','text',['required' => 'true']); ?>  
 </div>
  <div class="col-md-4">

 <?php echo render_input('ownership_town', 'Town', '','text'); ?>  
 </div>
 <div class="col-md-4">

 <?php echo render_input('ownership_state', 'State', '','text',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">

 <?php echo render_input('ownership_zip', 'Zip Code', '','text',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">

 <?php echo render_input('ownership_email', 'Email', '','email',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">

 <?php echo render_input('ownership_phone', 'Cell Phone', '','text',['required' => 'true']); ?>  
 </div>

</div>
</div>

<div class="col-md-12">
  <label for="dealsstatus">&nbsp;<?php echo 'Ownership Information (Owner 2) '; ?></label>
  <div class="row panel_s tw-p-2 tw-mx-1">
  
<div class="col-md-4">
 
 <?php echo render_input('ownership_name2', 'Full Name', '','text'); ?>  
 </div>
<div class="col-md-4">
 
 <?php echo render_input('ownership_share2', 'Ownership Share (%)', '','number'); ?>  
 </div>    
<div class="col-md-4">

 <?php echo render_input('ownership_address2', 'Address', '','text',); ?>  
 </div>
 
 <div class="col-md-4">
 <?php echo render_input('ownership_city2', 'City', '','text'); ?>  
 </div>
  <div class="col-md-4">

 <?php echo render_input('ownership_town2', 'Town', '','text'); ?>  
 </div>
 <div class="col-md-4">

 <?php echo render_input('ownership_state2', 'State', '','text'); ?>  
 </div>
 <div class="col-md-4">

 <?php echo render_input('ownership_zip2', 'Zip Code', '','text'); ?>  
 </div>
 <div class="col-md-4">

 <?php echo render_input('ownership_email2', 'Email', '','text'); ?>  
 </div>
 <div class="col-md-4">

 <?php echo render_input('ownership_phone2', 'Cell Phone', '','text'); ?>  
 </div>

</div>
</div>

<div class="col-md-12">
  <label for="dealsstatus">&nbsp;<?php echo 'Bank Information'; ?></label>
  <div class="row panel_s tw-p-2 tw-mx-1">
  
 <div class="col-md-4">
 <?php echo render_input('account_holder_name', 'Account Holder Name', '','text',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">
 <?php echo render_input('account_holder_address', 'Account Holder Address', '','text',['required' => 'true']); ?>  
 </div>    
 <div class="col-md-4">
 <?php echo render_input('account_holder_country', 'Account Holder Country', '','text',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">
 <?php echo render_input('bank_name', 'Bank Name', '','text',['required' => 'true']); ?>  
 </div>
  <div class="col-md-4">
 <?php echo render_input('bank_address', 'Bank Address', '','text',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">
 <?php echo render_input('bank_country', 'Bank Country', '','text',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">
 <?php echo render_input('Bank_swift_routing_iban', 'Bank SWIFT/Routing/IBAN', '','text',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">
 <?php echo render_input('bank_account_number', 'Bank Account Number', '','text',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">
 &nbsp;&nbsp;&nbsp;
 </div>

</div>
</div>

<div class="col-md-12">
 <?php echo render_textarea('business_and_operations_description', 'Business & Operations Description', '',['required' => 'true']); ?>
 </div>
 
 <div class="col-md-12">
 <?php echo render_textarea('fulfillment_and_delivery_explanation', 'Fulfillment & delivery explanation', '',['required' => 'true']); ?>
 </div>
 
 <div class="col-md-12">
 <?php echo render_textarea('marketing_activities_overview', 'Marketing activities overview', '',['required' => 'true']); ?>
 </div>

<?php 
}elseif(isset($lead->deal_status)&&$lead->deal_status==3){ ?>
<input type="hidden" name="vtype" value="uw" />
<input type="hidden" name="assigned_id" id="assigned_id" value="<?php echo $lead->assigned;?>"  />

<div class="form-group">
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="quotation_status" name="quotation_status" value="1" checked>
        <label for="status">Approve </label>
    </div>
    <div class="radio radio-primary radio-inline">
        <input type="radio" id="quotation_status" name="quotation_status" value="0" >
        <label for="status">Reject</label>
    </div>
</div>
<div id="approveDiv">


<div class="col-md-4">
 
 <?php echo render_input('MDR', 'MDR (%)', '','number',['required' => 'true']); ?>  
 </div>
<div class="col-md-4">
 
 <?php echo render_input('SetupFee', 'Setup Fee (USD)', '','number',['required' => 'true']); ?>  
 </div>    
<div class="col-md-4">

 <?php echo render_input('HoldBack', 'Hold Back (%)', '','number',['required' => 'true']); ?>  
 </div>
 
 <div class="col-md-4">
 <div class="form-group">
  <label for="default_language" class="control-label">Card Type</label>
 <select name="CardType[]" id="CardType" class="form-control" multiple="multiple" style="height:105px;" required>
<option value="">Select Card Type</option>
<option value="VISA 3ds">VISA 3ds</option>
<option value="MASTER 3ds">MASTER 3ds</option>
<option value="AMEX">AMEX</option>
<option value="JCB">JCB</option>
<option value="RUPAY">RUPAY</option>
<option value="DINER">DINER</option>
</select>  
 </div>
 </div>
  <div class="col-md-4">

 <?php echo render_input('Settlement', 'Settlement (No. of Working Day)', '','number',['required' => 'true']); ?>  
 </div>
 <div class="col-md-4">
<div class="form-group">
  <label for="SettlementFee" class="control-label">Settlement Fee</label> 
 <select name="SettlementFee" id="SettlementFee" class="form-control" required>
<option value="">Select Settlement Fee</option>
<option value="2%" selected="selected">2%</option>
<option value="3%">3%</option>
<option value="4%">4%</option>
<option value="5%">5%</option>
</select> 
</div> 
 </div>
 <div class="col-md-4">
<div class="form-group">
 <label for="default_language" class="control-label">Min Settlement</label>
<select name="MinSettlement" id="MinSettlement" class="form-control" required>
<option value="">Select Min Settlement</option>
<option value="5K">5K</option>
<option value="10K">10K</option>
<option value="20K">20K</option>
</select>  
</div>
 </div>
 <div class="col-md-4">

 <?php echo render_input('MonthlyFee', 'Monthly Fee (USD)', '350','number',['required' => 'true']); ?>  
 </div>



  <div class="col-md-12">
 <?php echo render_textarea('Descriptor', 'Descriptor', '',['required' => 'true']); ?>
 </div>
</div>

<div id="rejectDiv" style="display:none;">
 <div class="col-md-12">
 <?php echo render_textarea('Reason', 'Reason', ''); ?>
 </div>
</div>
<?php }elseif(isset($lead->deal_status)&&$lead->deal_status==4){  echo "&nbsp;&nbsp;Invoice form" ?>

<?php } ?>
</div>





</div>  



</div>   
               
            </div>
            <div class="modal-footer" style="background-color: rgb(186 230 253 / 1) !important;">
                
                <button onclick="convert_to_dealxxx(); return false;" class="btn btn-primary"><?php echo $dstatus; ?></button>
            </div>
		<?php echo form_close(); ?>
        </div>
        <!-- /.modal-content -->
        
    </div>
    <!-- /.modal-dialog -->
</div>
<?php if (isset($lead) && $lead_locked == true) { ?>
<script>
$(function() {
    // Set all fields to disabled if lead is locked
    $.each($('.lead-wrapper').find('input, select, textarea'), function() {
        $(this).attr('disabled', true);
        if ($(this).is('select')) {
            $(this).selectpicker('refresh');
        }
    });
});
 


</script>

<?php } ?>
