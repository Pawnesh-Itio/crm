<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?> 


<div id="wrapper"> 
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="_buttons tw-mb-2 sm:tw-mb-4">
				
				<?php if(isset($_SESSION['leads_page_type'])&&$_SESSION['leads_page_type']=='leads'){ ?>
                    <a href="#" onclick="init_lead(); return false;"
                        class="btn btn-primary mright5 pull-left display-block">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_lead'); ?>
                    </a>
                    <?php if (is_admin() || get_option('allow_non_admin_members_to_import_leads') == '1') { ?>
                    <a href="<?php echo admin_url('leads/import'); ?>"
                        class="btn btn-primary pull-left display-block hidden-xs">
                        <i class="fa-solid fa-upload tw-mr-1"></i>
                        <?php echo _l('import_leads'); ?>
                    </a>
                    <?php } ?>
					<?php } ?>
                    <div class="row">
                        <div class="col-sm-5 ">
						<?php if(isset($_SESSION['leads_page_type'])&&$_SESSION['leads_page_type']=='leads'){ ?>
                            <a href="#" class="btn btn-default btn-with-tooltip" data-toggle="tooltip"
                                data-title="<?php echo _l(''); ?>" data-placement="top"
                                onclick="slideToggle('.leads-overview'); return false;"><i
                                    class="fa fa-bar-chart"></i></a>
                            <a href="<?php echo admin_url('leads/switch_kanban/' . $switch_kanban); ?>"
                                class="btn btn-default mleft5 hidden-xs" data-toggle="tooltip" data-placement="top"
                                data-title="<?php echo $switch_kanban == 1 ? _l('leads_switch_to_kanban') : _l('switch_to_list_view'); ?>">
                                <?php if ($switch_kanban == 1) { ?>
                                <i class="fa-solid fa-grip-vertical"></i>
                                <?php } else { ?>
                                <i class="fa-solid fa-table-list"></i>
                                <?php } ?>
                            </a>
                            <?php }else{  ?>
                            <a href="#" class="btn btn-warning pull-left display-block mright10"><i class="fa-solid fa-handshake"></i> Deal</a>
                            <a href="<?php echo admin_url('leads/switch_kanban_deal/' . $switch_kanban_deal); ?>"
                                class="btn btn-default mleft5 hidden-xs" data-toggle="tooltip" data-placement="top"
                                data-title="<?php echo $switch_kanban_deal == 1 ? _l('leads_switch_to_kanban') : _l('switch_to_list_view'); ?>">
                                <?php if ($switch_kanban_deal == 1) { ?>
                                <i class="fa-solid fa-grip-vertical"></i>
                                <?php } else { ?>
                                <i class="fa-solid fa-table-list"></i>
                                <?php } ?>
                            </a>
                            <?php  } ?>
                        </div>
                        <div class="col-sm-4 col-xs-12 pull-right leads-search">
                            <?php if ($this->session->userdata('leads_kanban_view') == 'true') { ?>
                            <div data-toggle="tooltip" data-placement="top"
                                data-title="<?php echo _l('search_by_tags'); ?>">
                                <?php echo render_input('search', '', '', 'search', ['data-name' => 'search', 'onkeyup' => 'leads_kanban();', 'placeholder' => _l('leads_search')], [], 'no-margin') ?>
                            </div>
                            <?php } else { ?>
                            <div id="vueApp" class="tw-inline pull-right">
                                <app-filters
                                    id="<?php echo $table->id(); ?>"
                                    view="<?php echo $table->viewName(); ?>"
                                    :rules="<?php echo app\services\utilities\Js::from($this->input->get('status') ? $table->findRule('status')->setValue([$this->input->get('status')]) : []); ?>"
                                    :saved-filters="<?php echo $table->filtersJs(); ?>"
                                    :available-rules="<?php echo $table->rulesJs(); ?>">
                                </app-filters>
                            </div>
                            <?php } ?>
                            <?php echo form_hidden('sort_type'); ?>
                            <?php echo form_hidden('sort', (get_option('default_leads_kanban_sort') != '' ? get_option('default_leads_kanban_sort_type') : '')); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="hide leads-overview tw-mt-2 sm:tw-mt-4 tw-mb-4 sm:tw-mb-0">
                        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">
                            <?php echo _l('leads_summary'); ?>
                        </h4>
                        <div class="tw-flex tw-flex-wrap tw-flex-col lg:tw-flex-row tw-w-full tw-gap-3 lg:tw-gap-6">
                            <?php
                           foreach ($summary as $status) {
                            ?>
                            <div
                                class="lg:tw-border-r lg:tw-border-solid lg:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center last:tw-border-r-0">
                                <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg">
                                    <?php
                                          if (isset($status['percent'])) {
                                              echo '<span data-toggle="tooltip" data-title="' . $status['total'] . '">' . $status['percent'] . '%</span>';
                                          } else {
                                              // Is regular status
                                              echo $status['total'];
                                          }
                                       ?>
                                </span>
                                <span style="color:<?php echo e($status['color']); ?>"
                                    class="<?php echo isset($status['junk']) || isset($status['lost']) ? 'text-danger' : ''; ?>">
                                    <?php echo e($status['name']); ?>
                                </span>
                            </div>
                            <?php  } ?>
                        </div>

                    </div>
                </div>
                <div class="<?= ($_SESSION['leads_page_type'] == 'leads' && $isKanBan || $_SESSION['leads_page_type'] == 'deals' && $isKanBanDeal ) ? '' : 'panel_s' ?>">
                    <div class="<?= ($_SESSION['leads_page_type'] == 'leads' && $isKanBan || $_SESSION['leads_page_type'] == 'deals' && $isKanBanDeal) ? '' : 'panel-body' ?>">
                        <div class="tab-content">
                            <?php
                        if ($isKanBan && $_SESSION['leads_page_type'] == 'leads' || $_SESSION['leads_page_type'] == 'deals' && $isKanBanDeal ) { ?>
                            <div class="active kan-ban-tab" id="kan-ban-tab" style="overflow:auto;">
                                <div class="kanban-leads-sort">
                                    <span class="bold"><?php echo _l('leads_sort_by'); ?>: </span>
                                    <a href="#" onclick="leads_kanban_sort('dateadded'); return false"
                                        class="dateadded">
                                        <?php if (get_option('default_leads_kanban_sort') == 'dateadded') {
                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_leads_kanban_sort_type')) . '"></i> ';
                        } ?><?php echo _l('leads_sort_by_datecreated'); ?>
                                    </a>
                                    |
                                    <a href="#" onclick="leads_kanban_sort('leadorder');return false;"
                                        class="leadorder">
                                        <?php if (get_option('default_leads_kanban_sort') == 'leadorder') {
                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_leads_kanban_sort_type')) . '"></i> ';
                        } ?><?php echo _l('leads_sort_by_kanban_order'); ?>
                                    </a>
                                    |
                                    <a href="#" onclick="leads_kanban_sort('lastcontact');return false;"
                                        class="lastcontact">
                                        <?php if (get_option('default_leads_kanban_sort') == 'lastcontact') {
                            echo '<i class="kanban-sort-icon fa fa-sort-amount-' . strtolower(get_option('default_leads_kanban_sort_type')) . '"></i> ';
                        } ?><?php echo _l('leads_sort_by_lastcontact'); ?>
                                    </a>
                                </div>
                                <div class="row">
                                    <div class="container-fluid leads-kan-ban">
                                        <div id="kan-ban">

                                        </div>
                                            <!-- Deals Kanban -->
                                        <div id="kan-ban-deals" class="deals-kan-ban">
                                        <!-- Deals Kanban content goes here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } else { ?>
                            <div class="row" id="leads-table">
                                <div class="col-md-12">
                                    <a href="#" data-toggle="modal" data-table=".table-leads"
                                        data-target="#leads_bulk_actions"
                                        class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>
                                    <div class="modal fade bulk_actions" id="leads_bulk_actions" tabindex="-1"
                                        role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span
                                                            aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div>
                                                        <button class="btn btn-sm btn-info" onclick="merge_leads()">Merge Leads</button>
                                                    </div>
                                                    <hr class="mass_delete_separator" />
                                                    <?php if (staff_can('delete',  'leads')) { ?>
                                                    <div class="checkbox checkbox-danger">
                                                        <input type="checkbox" name="mass_delete" id="mass_delete">
                                                        <label
                                                            for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                                                    </div>
                                                    <hr class="mass_delete_separator" />
                                                    <?php } ?>
                                                    <div id="bulk_change">
                                                        <div class="form-group">
                                                            <div class="checkbox checkbox-primary checkbox-inline">
                                                                <input type="checkbox" name="leads_bulk_mark_lost"
                                                                    id="leads_bulk_mark_lost" value="1">
                                                                <label for="leads_bulk_mark_lost">
                                                                    <?php echo _l('lead_mark_as_lost'); ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <?php echo render_select('move_to_status_leads_bulk', $statuses, ['id', 'name'], 'ticket_single_change_status'); ?>
                                                        <?php
                                             echo render_select('move_to_source_leads_bulk', $sources, ['id', 'name'], 'lead_source');
                                             echo render_datetime_input('leads_bulk_last_contact', 'leads_dt_last_contact');
                                             echo render_select('assign_to_leads_bulk', $staff, ['staffid', ['firstname', 'lastname']], 'leads_dt_assigned');
                                             ?>
                                                        <div class="form-group">
                                                            <?php echo '<p><b><i class="fa fa-tag" aria-hidden="true"></i> ' . _l('tags') . ':</b></p>'; ?>
                                                            <input type="text" class="tagsinput" id="tags_bulk"
                                                                name="tags_bulk" value="" data-role="tagsinput">
                                                        </div>
                                                        <hr />
                                                        <div class="form-group no-mbot">
                                                            <div class="radio radio-primary radio-inline">
                                                                <input type="radio" name="leads_bulk_visibility"
                                                                    id="leads_bulk_public" value="public">
                                                                <label for="leads_bulk_public">
                                                                    <?php echo _l('lead_public'); ?>
                                                                </label>
                                                            </div>
                                                            <div class="radio radio-primary radio-inline">
                                                                <input type="radio" name="leads_bulk_visibility"
                                                                    id="leads_bulk_private" value="private">
                                                                <label for="leads_bulk_private">
                                                                    <?php echo _l('private'); ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal"><?php echo _l('close'); ?></button>
                                                    <a href="#" class="btn btn-primary"
                                                        onclick="leads_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->
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
                              /*$_table_data[] = [
                               'name'     => _l('leads_dt_phonenumber'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-phone'],
                              ];*/
							  
							  $_table_data[] = [
                               'name'     => _l('Website'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-website'],
                              ];
							  
							  $_table_data[] = [
                               'name'     => _l('Industries'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-BusinessNature'],
                              ];
                              /*$_table_data[] = [
                                 'name'     => _l('leads_dt_lead_value'),
                                 'th_attrs' => ['class' => 'toggleable', 'id' => 'th-lead-value'],
                                ];
								*/
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
							  
							  if($_SESSION['leads_page_type']=='deals'){
							  $_table_data[] = [
                               'name'     => _l('Deal Status'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-status'],
                              ];
							  }
							  
							  $_table_data[] = [
                               'name'     => _l('Observer'),
                               'th_attrs' => ['class' => 'toggleable', 'id' => 'th-assigned'],
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
                                    <div class="panel-table-full">
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
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Whatsapp Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                    <div class="wa-lodder">
                        <img src="<?= base_url("assets/images/1488.gif") ?>" alt="">
                    </div>
                <div class="chat-container" id="chatContainer" style="background: url('<?= base_url('assets/images/chatbackground3.jpg')?>">
                    <!-- Message containet -->
                </div>
                <div class="formBtnDiv">
                    <form id="messageForm" >
                        <input type="hidden" id="formUserId" value="<?= get_staff_user_id() ?>">
                        <input type="hidden" id="formNumber" class="formNumber" name="chatId"/>
                        <input type="hidden" id="formMediaId" name="formMediaId"/>
                        <input type="hidden" id="formMediaUrl"/>
                        <input type="hidden" id="formType" value="1">
                        <input type="hidden" id="mediaCategory" name="mediaCategory">
                        <div class="message-input">
                            <div class="row">
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <div class="btn-group dropup">
                                                <button class="btn wa-drop-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#" id="textMessageOption">Text Message</a></li>
                                                    <li><a href="#" id="imagePhotoOption">Photo/Image</a></li>
                                                    <li><a href="#" id="documentMessageOption">Document Message</a></li>
                                                    <li><a href="#" id="audioMessageOption">Audio Message</a></li>
                                                    <li><a href="#" id="videoMessageOption">Video Message</a></li>
                                                    <li><a href="#" id="templateMessageOption">Template Message</a></li>
                                                    <li><a href="#" id="linkMessageOption">Link Message</a></li>
                                                </ul>
                                            </div>
                                        </span>
                                        <!-- Container for the fields -->
                                            <!-- Default Text Message Field (shown by default) -->
                                            <input type="text" class="form-control message-field message-input" id="textMessageField" placeholder="Type a message..." style="display: block;">
                                            <!-- Media Message Field (hidden by default) -->
                                            <input type="file" id="mediaMessageFileField" style="display:none">
                                            <input type="text" id="mediaMessageCaptionField" class="form-control message-field message-input"placeholder="Media Caption" style="display:none">
                                            <!-- Template Message Field (hidden by default) -->
                                            <select class="form-select wa-form-select message-field" id="templateMessageField" style="display: none;" aria-label="Default select example">
                                                <option selected> Open this select menu</option>
                                                <option value="hello_world">Hello World</option>
                                            </select>
                                            <!-- Link Message Field (hidden by default) -->
                                            <input type="text" class="form-control message-field message-input" id="linkMessageField" style="display: none;" placeholder="Enter Link...">
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <button  type="submit" class="btn  wa-btn" id="sendMessageBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="padding-top:3.5px" viewBox="0 0 50 25" width="50" height="24" fill="white"><path d="M2 21v-7l11-2-11-2V3l21 9-21 9z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Web Email Modal -->
<div class="modal" id="myWebModal" style="z-index: 99999;">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
	 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><!--Heading--></h4>
        
      </div>
      <!-- Modal body -->
      <div class="modal-body">
	  <div id="loader" class="d-flex justify-content-center align-items-center" style="height: 500px;">
            <div class="spinner-border text-primary" role="status">
<span class="visually-hidden"><img src="<?php echo base_url('assets/images/mail-loader.gif')?>"  style="display: block;margin: auto;width: 30%;" /></span>
            </div>
          </div>
		 
        
		<iframe id="iframeContent" src="" style="width:100%; height:500px; border:none;" onload="hideLoader()"></iframe>
      </div>
      <!-- Modal footer -->
      <?php /*?><div class="modal-footer">
        <button type="button" class="btn btn-primary close" data-dismiss="modal">Close</button>
      </div><?php */?>
    </div>
  </div>
</div>
<!-- Lead Assign Model -->
 <!-- Modal -->
<div class="modal fade bd-example-modal-sm" id="leadAssignModel" tabindex="-1" role="dialog" aria-labelledby="leadAssignModel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Lead Assign</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <form id="assignLeadForm" action="<?= admin_url('leads/updateAssignedUser') ?>" method="post">
        <div class="modal-body">
            <div class="form-group">
                <input type="hidden" name="lead_id" id="lead_id">
                <label>Assigned</label>
                    <select name="assigned_id" class="custom-select form-control" id="mySelect">
                    <!-- Dynamically Populate Here -->
                    </select>
            </div>
        </div>
        <div class="modal-footer">
            <button name="assignSubmit" type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
 <!-- Modal -->
<div class="modal fade bd-example-modal-sm" id="leadAbsorberModel" tabindex="-1" role="dialog" aria-labelledby="leadAbsorberModel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="leadAbsorberModel">Lead Absorber Assign</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <form id="assignAbsorberForm" action="<?= admin_url('leads/updateAssignedAbsorber') ?>" method="get">
        <div class="modal-body">
            <div class="form-group">
                
                <label>Assigned to Absorber</label>
				<input type="hidden" name="lead_idx" id="lead_idx">
                    <select name="assigned_id" class="custom-select form-control" id="myAbsorberSelect">
                    <!-- Dynamically Populate Here -->
                    </select>
            </div>
        </div>
        <div class="modal-footer">
            <button name="assignSubmit"  type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
 <!-- End Lead Assign Model -->
  <!-- Create the model for lead merge -->
<div class="modal fade" id="mergeModal" tabindex="-1" role="dialog" aria-labelledby="mergeModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>
<!-- End Lead Merge Model -->
<!-- MySocket logic -->
<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
<script>
// Socket connection
const SocketURL = "<?= Whatsapp_Socket_Url ?>";
const waURL = "<?= Whatsapp_Api_Url ?>";
const socket = io(SocketURL);
socket.on('connect', () => {
    console.log('Connected to Socket.io server');
});
socket.on('disconnect', () => {
    console.log('Disconnected from Socket.io server');
});
socket.on('error', (error) => {
    console.log("Error:", error);
});
function getMessages(element){
    $('#lead-modal').modal('hide');
    var name = element.getAttribute('data-name');
    var chatId = parseInt(element.getAttribute('data-number'));
    console.log(name);
    $('.modal-title').html(name+' ('+ chatId +')');
    $('#formNumber').val(chatId);
	$('.chat-container').html('');// Remove any exisiting listener before adding new one
    $.ajax({
        url: waURL+'/api/chat/messages/'+chatId+'/665167003352668/Regular',
        method: 'GET',
        success: function (data) {
            console.log(data);
            $('.formBtnDiv').show();
            $('.wa-lodder').hide();
            $('.chat-container').html('');
            // Add Messages to chat box.
            if(data.status != 'no_contact' && data.status !='no_messages'){
                let lastDateGroup = null;
                data.messages.forEach(function (message) {
                    const messageClass = message.message_type === 'received' ? 'incoming-message' : 'sent-message';
                    // Format the Date
                    const msgDate = new Date((message.time || message.createdAt) * 1000); // assuming `time` is Unix timestamp in seconds
                    const today = new Date();
                    const yesterday = new Date();
                    yesterday.setDate(today.getDate() - 1);

                    const isToday = msgDate.toDateString() === today.toDateString();
                    const isYesterday = msgDate.toDateString() === yesterday.toDateString();

                    let dateLabel;
                    if (isToday) {
                        dateLabel = 'Today';
                    } else if (isYesterday) {
                        dateLabel = 'Yesterday';
                    } else {
                        dateLabel = msgDate.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
                    }

                    // Insert date label if group has changed
                    if (lastDateGroup !== dateLabel) {
                        const dateDivider = $(`<div class="chat-date-divider">${dateLabel}</div>`);
                        $('#chatContainer').append(dateDivider);
                        lastDateGroup = dateLabel;
                    }

                    // Format time (e.g., 4:36 PM)
                    const formattedTime = msgDate.toLocaleTimeString(undefined, {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                    // End Format the Date and Time
                    // Message body with time
                    let messageDiv;
                    if (message.message_content != 4) {
                        messageDiv = $(`<div class="${messageClass}" id="${message.message_id}">
                            <div>${message.message_body}</div>

                            <div class="chat-time">${formattedTime}</div>
                        </div>`);
                    } else {
                        const mediaPath = waURL + '/' + message?.media_details?.path.replace('\\', '/');
                        const mediaCaption = message.message_body;

                        switch (message.media_type) {
                            case 'image':
                                messageDiv = $(
                                    `<div class="${messageClass}" id="${message.message_id}" style="width:50%">
                                        <a href="${mediaPath}" target="_blank"><img src="${mediaPath}" width="100%" /></a>
                                        <p style="padding-top:10px">${mediaCaption || ''}</p>
                                        <div class="chat-time">${formattedTime}</div>
                                    </div>`
                                );
                                break;
                            case 'video':
                                messageDiv = $(
                                    `<div class="${messageClass}" id="${message.message_id}" style="width:50%">
                                        <video width="100%" controls>
                                            <source src="${mediaPath}" type="video/mp4">
                                        </video>
                                        <p style="padding-top:10px">${mediaCaption || ''}</p>
                                        <div class="chat-time">${formattedTime}</div>
                                    </div>`
                                );
                                break;
                            case 'audio':
                                messageDiv = $(
                                    `<div class="${messageClass}" id="${message.message_id}" style="width:50%">
                                        <audio controls><source src="${mediaPath}" type="audio/mpeg"></audio>
                                        <p style="padding-top:10px">${mediaCaption || ''}</p>
                                        <div class="chat-time">${formattedTime}</div>
                                    </div>`
                                );
                                break;
                            case 'document':
                                const fileName = mediaPath.split('/').pop();
                                messageDiv = $(
                                    `<div class="${messageClass}" id="${message.message_id}" style="width:50%">
                                        <a href="${mediaPath}" target="_blank">📄 ${fileName}</a>
                                        <p style="padding-top:10px">${mediaCaption || ''}</p>
                                        <div class="chat-time">${formattedTime}</div>
                                    </div>`
                                );
                                break;
                            default:
                                messageDiv = $(
                                    `<div class="${messageClass}" id="${message.message_id}" style="width:50%">
                                        <a href="${mediaPath}" target="_blank">Download Media</a>
                                        <div class="chat-time">${formattedTime}</div>
                                    </div>`
                                );
                        }
                    }
                     $('#chatContainer').append(messageDiv);
                    // Message body end
            });
        }else{
            const errSpan = "<span class='err_span text-center text-danger'>No Message found...</span>";
            $('.chat-container').append(errSpan);
        }
            autoScrollToBottom();
            // Add realtime incomming messages.
            setupChatSocketListener(chatId);

        },
        error: function () {
            $('.wa-lodder').hide();
            console.error('Failed to fetch data');
        }
    });
}
function setupChatSocketListener(chatId){
    console.log(chatId);
	socket.off('chat-' + chatId);
	socket.on('chat-' + chatId, (data)=>{
	console.log(data);//Checking.
	var type = data.type;
	var messageData = data.messageContentToInsert;//getting message body
	var data ; // Initializing empty variable'
	//Check if message is sent or status
	if(type=='received'){
    const formattedTime = messageData.time
        ? new Date(messageData.time * 1000).toLocaleTimeString(undefined, {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
            })
        : '';

 
        if(messageData.message_content != 4){
	    data = '<div id='+messageData.message_id+' class="incoming-message">'+messageData.message_body+'<div class="chat-time">'+formattedTime+'</div></div>';
	    //Appending chat data to UI
	    $('.chat-container').append(data);
        }else{

            switch (messageData.media_type) {
                case 'image':
                    data =$(`<div class="incoming-message" id="${messageData.message_id}" style="width:50%">
                        <a href="${waURL}/${messageData.media_path.replace('\\', '/')}" target="_blank">
                            <img src="${waURL}/${messageData.media_path.replace('\\', '/')}" width="100%" />
                            <p style="padding-top:10px">${messageData.message_body || ''}</p>
                            <div class="chat-time">${formattedTime}</div>
                        </a>
                            `);
                            $('.chat-container').append(data);
                    break;
                case 'video':
                    data = $(`<div id=${messageData.message_id} class="incoming-message" style="width:50%">
                        <video width="100%" controls>
                            <source src="${waURL}/${messageData.media_path.replace('\\', '/')}" type="video/mp4">
                        </video>
                        <p style="padding-top:10px">${messageData.message_body || ''}</p>
                        <div class="chat-time">${formattedTime}</div>`);
                        $('.chat-container').append(data);
                    break;
                case 'audio':
                    data = $(`<div id=${messageData.message_id} class="incoming-message" style="width:50%">
                        <audio controls>
                            <source src="${waURL}/${messageData.media_path.replace('\\', '/')}" type="audio/mpeg">
                        </audio>
                        <p style="padding-top:10px">${messageData.message_body || ''}</p>
                        <div class="chat-time">${formattedTime}</div>`);
                        $('.chat-container').append(data);
                    break;
                case 'document':
                    const fileName = messageData.media_path.split('/').pop();
                    data = $(`<div id=${messageData.message_id} class="incoming-message" style="width:50%">
                        <a href="${waURL}/${messageData.media_path.replace('\\', '/')}" target="_blank">📄 ${fileName}</a>
                        <p style="padding-top:10px">${messageData.message_body || ''}</p>
                        <div class="chat-time">${formattedTime}</div>`);
                        $('.chat-container').append(data);
                    break;
                default:
                    data = $(
                                `<div class="incoming-message" id="${messageData.message_id}" style="width:50%">
                                    <a href="${messageData.media_path.replace('\\', '/')}" target="_blank">Download Media</a>
                                    <div class="chat-time">${formattedTime}</div>
                                </div>`
                            );
                            $('.chat-container').append(data);
            }
        }
	    // Automatically scroll to the bottom of the chat box
        autoScrollToBottom();
	}else{
	    console.log(data); 
	}
	});
	

}

	function getWebEmail(element){
	//alert(11);
    //$('#lead-modal').modal('hide');
    var dataName = element.getAttribute('data-name');
    var dataEmail = element.getAttribute('data-email');
	var dataHref = element.getAttribute('data-href');
	//alert(dataHref);
  
	$('#myWebModal .modal-title').html('Webmail : ' + dataEmail );
	$('#myWebModal').modal('show');
	$('#loader').show();
    $('#iframeContent').hide();
	<?php /*?>$('#myWebModal').modal('show').find('.modal-body').load(dataHref);<?php */?>
	$('#iframeContent').attr('src', dataHref);
	$('#myWebModal .modal-dialog').css({"width":"90%","max-width":"950px"});
	
	
	}
	function hideLoader() {
      $('#loader').hide();
      $('#iframeContent').show();
	  const iframe = document.getElementById('iframeContent');
      
      try {
        const contentHeight = iframe.contentWindow.document.body.scrollHeight;
        iframe.style.height = contentHeight + 'px';
      } catch (error) {
        console.error('Error adjusting iframe height:', error);
      }
    }
</script>
<!-- End Socket lOgic -->
<script id="hidden-columns-table-leads" type="text/json">
<?php echo get_staff_meta(get_staff_user_id(), 'hidden-columns-table-leads'); ?>
</script>
<?php include_once(APPPATH . 'views/admin/leads/status.php'); ?>

<?php include_once(APPPATH . 'views/admin/leads/conversation.php'); ?>

<?php init_tail(); ?>
<script>
var openLeadID = '<?php echo e($leadid); ?>';
$(function() {
    leads_kanban();
    $('#leads_bulk_mark_lost').on('change', function() {
        $('#move_to_status_leads_bulk').prop('disabled', $(this).prop('checked') == true);
        $('#move_to_status_leads_bulk').selectpicker('refresh')
    });
    $('#move_to_status_leads_bulk').on('change', function() {
        if ($(this).selectpicker('val') != '') {
            $('#leads_bulk_mark_lost').prop('disabled', true);
            $('#leads_bulk_mark_lost').prop('checked', false);
        } else {
            $('#leads_bulk_mark_lost').prop('disabled', false);
        }
    });
});
// Function to automatically scroll the chat container to the bottom
function autoScrollToBottom() {
    var chatContainer = $('#chatContainer');
    chatContainer.scrollTop(chatContainer[0].scrollHeight);  // Scroll to the bottom
}
$(document).ready(function() {
    $('#messageForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        // Gather form data
        const source = "crm";
        const userId = $('#formUserId').val();
        const to = $('#formNumber').val();
        const type = $('#formType').val();
        const message = $('#textMessageField').val();
        const template = $('#templateMessageField').val();
        const linkMessage = $('#linkMessageField').val();
        const mediaId = $('#formMediaId').val();
        const mediaCategory = $('#mediaCategory').val();
        const mediaCaption = $('#mediaMessageCaptionField').val();
        const formMediaUrl = $('#formMediaUrl').val();
        const ContactType = "Regular";
        // sendPayload
        const sendPayload = {
            userId: userId,
            source: source,
            configurationId: "685a8bf1d6c7ab4d519bd92f",
            to: to,
            messageType: type,
            contactType:ContactType,
        }
        if(type==1){
            sendPayload.message = message;
        }
        if(type==2){
            sendPayload.message = linkMessage;
        }
        if(type==3){
            sendPayload.tempName = template;
        }
        if(type==4){
            sendPayload.mediaId = mediaId;
            sendPayload.mediaCategory = mediaCategory;
            if(mediaCaption){
            sendPayload.caption = mediaCaption;
            }
        }

        // Send the data via AJAX
        $.ajax({
            type: 'POST',
            url: waURL+'/api/messages/send/', // Replace with your actual URL
            contentType: 'application/json',
            data: JSON.stringify(sendPayload), // Convert the data to JSON string
            success: function(response) {
                // Handle success (e.g., clear input, display message, etc.)
                const messageData = response.data.messages[0];
                $('#textMessageField').val(''); // Clear the input after sending
                data = '<div id='+messageData.id+' class="sent-message">'+message+'</div>';
                //Appending chat data to UI
                $('.chat-container').append(data);
                // Automatically scroll to the bottom of the chat box
                autoScrollToBottom();
            },
            error: function(error) {
                // Handle error
                console.error('Error sending message:', error);
            }
        });
    });
});
// LeadAssigned
function leadAssign(id, Assigned_id=0){
console.log("Lead Id:"+id);
console.log("Assigned Id:"+Assigned_id);
let att ;
$.ajax({
    url: '<?= admin_url('staff/getAllStaff') ?>',  // URL to send the request to
    type: 'GET',  // Request method, e.g., GET or POST
    dataType: 'json',  // Expected data format from the server
    success: function(response) {
        // Initialize id field.
        $('#lead_id').val(id);
		$('#lead_idx').val(id);
        // Clear existing options
        $('#mySelect').empty();
		$('#myAbsorberSelect').empty();
        // Populate options from the server response

        response.forEach(function(item) {
            if(item.staffid == Assigned_id){
                att = "selected";
            }else{
                att="";
            }
            $('#mySelect').append('<option value="' + item.staffid + '" '+att+' >' + item.full_name + '</option>');
			$('#myAbsorberSelect').append('<option value="' + item.staffid + '" '+att+' >' + item.full_name + '</option>');
        });
    },
    error: function(xhr, status, error) {
        // Handle any errors that occur
        console.error('An error occurred: ' + error);
    }
});
}
function leads_kanban_update(e, t) {
    if (t === e.item.parent()[0]) {
        var $item = $(e.item);
        var leadId = $item.attr("data-lead-id");
        var statusId = parseInt($item.parent().attr("data-lead-status-id")); // Ensure it's an integer

        // Check if Deal or Lead
        var pageType = "<?= $_SESSION['leads_page_type'] ?>"; //now it's a string
        var isDeal = pageType === "deals" ? 1 : 0;

        if (isDeal) {
            $.get(admin_url + "leads/get_lead_details/" + leadId, function (response) {
                var data = typeof response === 'string' ? JSON.parse(response) : response;

                var dealStatus = parseInt(data.lead.deal_status); // Ensure it's an integer

                // === VALIDATION LOGIC ===
                if (
                    (dealStatus === 1 && statusId !== 2) ||
                    (dealStatus === 2 && statusId !== 3) ||
                    (dealStatus === 4 && statusId !== 4)
                ) {
                    alert('Operation cannot be performed due to invalid status transition.');

                    // Refresh the Kanban
                    setTimeout(function () {
                        $.post(admin_url + "leads/update_lead_status", {
                            status: statusId,
                            leadid: leadId,
                            order: []
                        }).done(function () {
                            update_kan_ban_total_when_moving(e, statusId);
                            leads_kanban();
                        });
                    }, 200);

                    return; // Stop further execution
                }
                if((dealStatus === 3 && statusId !== 3)){
                    alert('Operation cannot be performed, Underwriting is pending by Approver.');

                    // Refresh the Kanban
                    setTimeout(function () {
                        $.post(admin_url + "leads/update_lead_status", {
                            status: statusId,
                            leadid: leadId,
                            order: []
                        }).done(function () {
                            update_kan_ban_total_when_moving(e, statusId);
                            leads_kanban();
                        });
                    }, 200);

                    return; // Stop further execution
                }

                //  MODAL HANDLING LOGIC 
                if (dealStatus === 3 && data.lead.uw_status == 0 && data.staff_role != 4) {
                    console.log('Dont Show modal');
                } else if (data.lead.uw_status == 0 && data.staff_role == 4) {
                    init_lead(leadId);
                    setTimeout(() => {
                        if ($('#dealModal').length) {
                            $('#dealModal').modal('show');
                        } else {
                            console.error('Modal still not found');
                        }
                    }, 500);
                } else {
                    console.log('Show modal 2');
                    init_lead(leadId);
                    $('#lead-modal').one('shown.bs.modal', function () {
                        setTimeout(() => {
                            $('#dealModal').modal('show');
                        }, 100); // small buffer
                    });

                }
            });
            return;
        }

        // Regular lead logic
        var a = {
            status: statusId,
            leadid: leadId,
            order: []
        };

        $.each($(e.item).parents(".leads-status").find("li"), function (e, t) {
            var i = $(t).attr("data-lead-id");
            if (i) a.order.push([i, e + 1]);
        });

        setTimeout(function () {
            $.post(admin_url + "leads/update_lead_status", a).done(function (t) {
                update_kan_ban_total_when_moving(e, a.status);
                leads_kanban();
            });
        }, 200);
    }
}
// Function to merge leads
function merge_leads() {
  var ids = [];
  var rows = table_leads.find("tbody tr");
  $.each(rows, function () {
    var checkbox = $($(this).find("td").eq(0)).find("input");
    if (checkbox.prop("checked") === true) {
      ids.push(checkbox.val());
    }
  });
  if (ids.length !== 2) {
    alert_float("warning", "Please select exactly 2 leads to merge.");
    return;
  }
//Fetch lead details
  $.post(admin_url + "leads/get_leads_details", { ids: ids }, function (response) {
    var data = typeof response === 'string' ? JSON.parse(response) : response;
    console.log(data);
    if (data.status === 'success') {
        $('#mergeModal .modal-content').html(data.html);
        $('#leads_bulk_actions').modal('hide');
        $('#mergeModal').modal('show');
    } else {
        alert_float('danger', data.message || 'Unable to load leads.');
    }
  });
}
// Get Lead name by id
function getLeadNameById(leadId) {
    var leadName = '';
    $.ajax({
        url: admin_url + 'leads/getLeadNameById',
        type: 'POST',
        data: { lead_id: leadId },
        async: false,
        success: function (response) {
            var data = typeof response === 'string' ? JSON.parse(response) : response;
            if (data.status === 'success') {
                leadName = data.lead_name;
            }
        }
    });
    return leadName;
}
$(document).ready(function() {
    $('#assignLeadForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        var leadId = $('#lead_id').val();
        var assignedId = $('#mySelect').val();
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#leadAssignModel').modal('hide');
                alert_float('success', 'Lead assigned successfully');
                if ($.fn.DataTable.isDataTable('.table-leads')) {
                    $('.table-leads').DataTable().ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                alert_float('danger', 'Failed to assign lead');
            },
            complete: function() {
                submitBtn.prop('disabled', false);
            }
        });
    });
    // Add AJAX for Absorber assignment
    $('#assignAbsorberForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        var leadId = $('#lead_idx').val();
        var assignedId = $('#myAbsorberSelect').val();
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'GET',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#leadAbsorberModel').modal('hide');
                alert_float('success', 'Absorber assigned successfully');
                if ($.fn.DataTable.isDataTable('.table-leads')) {
                    $('.table-leads').DataTable().ajax.reload(null, false);
                }
            },
            error: function(xhr) {
                alert_float('danger', 'Failed to assign absorber');
            },
            complete: function() {
                submitBtn.prop('disabled', false);
            }
        });
    });
});
// Handle Change Message Type
document.getElementById('textMessageOption').addEventListener('click', function() {
    changeMessageField('textMessageField');
    $('#formType').val(1);
});
// Image Photo Option
document.getElementById('imagePhotoOption').addEventListener('click', function() {
    $('#formType').val(4);
    $('#mediaCategory').val('image'); // 
    changeMessageField('mediaMessageCaptionField');
    
    // Accept only image files
    document.getElementById('mediaMessageFileField').setAttribute('accept', 'image/png,image/jpeg');
    document.getElementById('mediaMessageFileField').click();
});
// Document Message Option
document.getElementById('documentMessageOption').addEventListener('click', function() {
    $('#formType').val(4); // Common messageType for all media
    $('#mediaCategory').val('document'); // Important: specify mediaCategory
    changeMessageField('mediaMessageCaptionField'); // Optionally show caption field

    // Accept only document files
    document.getElementById('mediaMessageFileField').setAttribute('accept', '.pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    document.getElementById('mediaMessageFileField').click();
});
//  Audio Message Option
document.getElementById('audioMessageOption').addEventListener('click', function() {
    $('#formType').val(4);
    $('#mediaCategory').val('audio');
    changeMessageField('mediaMessageCaptionField'); // You can hide this for audio if not needed

    // Accept only audio files
    document.getElementById('mediaMessageFileField').setAttribute('accept', 'audio/mpeg,audio/ogg');
    document.getElementById('mediaMessageFileField').click();
});
// Video Message Option
document.getElementById('videoMessageOption').addEventListener('click', function() {
    $('#formType').val(4);
    $('#mediaCategory').val('video');
    changeMessageField('mediaMessageCaptionField');

    // Accept only video files
    document.getElementById('mediaMessageFileField').setAttribute('accept', 'video/mp4,video/3gpp');
    document.getElementById('mediaMessageFileField').click();
});

document.getElementById('templateMessageOption').addEventListener('click', function() {
    $('#formType').val(3);
    changeMessageField('templateMessageField');
});

document.getElementById('linkMessageOption').addEventListener('click', function() {
    $('#formType').val(2);
    changeMessageField('linkMessageField');
});
// End Hanlde Change Message Type
// Function to switch the visible field
function changeMessageField(fieldId) {
    // Hide all fields
    const fields = document.querySelectorAll('.message-field');
    fields.forEach(function(field) {
        field.style.display = 'none';
    });

    // Show the selected field
    document.getElementById(fieldId).style.display = 'block';
}
// Media Upload function
$(document).ready(function () {
    $('#mediaMessageFileField').on('change', function (event) {
        console.log("Media message file triggered");
        const file = event.target.files[0];
        const userId = $('#formUserId').val();
        const source = "crm";
        const confId = "684bd26e5a6a97308dd272c4"; // Default configuration ID
        const mediaCategory = $('#mediaCategory').val();

        if (!file || !mediaCategory) {
            alert("No file or media type selected.");
            return;
        }

        const fileSizeMB = file.size / (1024 * 1024);
        const fileType = file.type;

        // ✅ File size validation by media type
        const sizeLimits = {
            image: 5,
            video: 16,
            audio: 16,
            document: 100
        };

        const allowedTypes = {
            image: ['image/jpeg', 'image/png'],
            video: ['video/mp4', 'video/3gpp'],
            audio: ['audio/mpeg', 'audio/ogg'],
            document: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        };

        if (fileSizeMB > sizeLimits[mediaCategory]) {
            alert(`File exceeds maximum size for ${mediaCategory}: ${sizeLimits[mediaCategory]} MB`);
            return;
        }

        if (!allowedTypes[mediaCategory].includes(fileType)) {
            alert(`Invalid file type for ${mediaCategory}. Allowed: ${allowedTypes[mediaCategory].join(', ')}`);
            return;
        }

        // Show media preview
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#formMediaUrl').val(e.target.result);

            let previewElement;
            const src = e.target.result;

            if (mediaCategory === 'image') {
                previewElement = $(`
                    <div style="width:50%">
                        <img src="${src}" width="100%" alt="image" />
                        <div id="overlay" class="wa-overlay">
                            <div class="wa-loader" id="loader"></div>
                        </div>
                    </div>
                `);
            } else if (mediaCategory === 'video') {
                previewElement = $(`
                    <div style="width:50%">
                        <video width="100%" controls muted>
                            <source src="${src}" type="${fileType}">
                        </video>
                        <div id="overlay" class="wa-overlay">
                            <div class="wa-loader" id="loader"></div>
                        </div>
                    </div>
                `);
            } else if (mediaCategory === 'audio') {
                previewElement = $(`
                    <div style="width:50%">
                        <audio controls>
                            <source src="${src}" type="${fileType}">
                        </audio>
                        <div id="overlay" class="wa-overlay">
                            <div class="wa-loader" id="loader"></div>
                        </div>
                    </div>
                `);
            } else {
                previewElement = $(`
                    <div class="doc-preview">
                        <i class="fa fa-file-pdf-o" style="font-size:40px;"></i>
                        <p>${file.name}</p>
                        <div id="overlay" class="wa-overlay">
                            <div class="wa-loader" id="loader"></div>
                        </div>
                    </div>
                `);
            }

            previewElement.addClass("uploading-media");
            $('#chatContainer').append(previewElement);
            setTimeout(autoScrollToBottom, 50);
        };
        reader.readAsDataURL(file);

        // Upload to backend
        const formData = new FormData();
        formData.append('file', file);
        formData.append('source', source);
        formData.append('userId', userId);
        formData.append('configurationId', confId);

        $.ajax({
            url: waURL + '/api/messages/upload',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (data) {
                $('#formMediaId').val(data.media_id);
                $('#overlay').append($('<p class="succ-upload">').text('Upload successful! Click send to deliver.'));
                $('#loader').hide();
                $(".uploading-media").css({
                    border: "4px solid green",
                    position: "relative",
                    left: "-30px"
                });
            },
            error: function (error) {
                $('#overlay').append($('<p class="err-upload">').text('Upload failed, please try again.'));
                $('#loader').hide();
                $(".uploading-media").css({
                    border: "2px solid red",
                    position: "relative",
                    left: "-30px"
                });
                console.error('Error uploading file:', error.responseJSON);
            }
        });
    });
});
$(document).ready(function () {
    socket.off("whatsapp:new_message");
    socket.on("whatsapp:new_message", function (data) {
    let phone = data.leadPhoneNumber || "";

    const localPhone = phone;

    // Match using the data-local-phone attribute
    const $row = $(`tr[data-phone="${localPhone}"]`);

    if ($row.length) {
        const $btnCol = $row.find('td').eq(2); ;
        console.log("Button Column:", $btnCol);

        const iconId = `wa-indicator-${localPhone}`;
        if (!$(`#${iconId}`).length) {
            $btnCol.append(`
                <span id="${iconId}" title="New WhatsApp Message" style="margin-left:5px;">
                    <i class="fa-brands fa-whatsapp text-success fa-bounce"></i>
                </span>
            `);
        }

    }
    });
});
</script>
</body>
</html>