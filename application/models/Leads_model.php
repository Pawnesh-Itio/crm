<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use app\services\AbstractKanban;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require APPPATH .'vendor/autoload.php';
defined('BASEPATH') or exit('No direct script access allowed');

class Leads_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get lead
     * @param  string $id Optional - leadid
     * @return mixed
     */
    public function get($id = '', $where = [])
    {
        $this->db->select('*,' . db_prefix() . 'leads.name, ' . db_prefix() . 'leads.id,' . db_prefix() . 'leads_status.name as status_name,' . db_prefix() . 'leads_sources.name as source_name');
        $this->db->join(db_prefix() . 'leads_status', db_prefix() . 'leads_status.id=' . db_prefix() . 'leads.status', 'left');
        $this->db->join(db_prefix() . 'leads_sources', db_prefix() . 'leads_sources.id=' . db_prefix() . 'leads.source', 'left');

        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'leads.id', $id);
            $lead = $this->db->get(db_prefix() . 'leads')->row();
            if ($lead) {
                if ($lead->from_form_id != 0) {
                    $lead->form_data = $this->get_form([
                        'id' => $lead->from_form_id,
                    ]);
                }
                $lead->attachments = $this->get_lead_attachments($id);
                $lead->public_url  = leads_public_url($id);
            }

            return $lead;
        }

        return $this->db->get(db_prefix() . 'leads')->result_array();
    }

    /**
     * Get lead by given email
     *
     * @since 2.8.0
     *
     * @param  string $email
     *
     * @return \strClass|null
     */
    public function get_lead_by_email($email)
    {
        $this->db->where('email', $email);
        $this->db->limit(1);

        return $this->db->get('leads')->row();
    }
	
	 public function get_lead_id_by_email($email)
    {
	    
		$this->db->select('id');
        $this->db->where('email', $email);
        $lid = $this->db->get(db_prefix() . 'leads')->row()->id  ?? '';
		return $lid; 
		
    }
	
    public function get_lead_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->limit(1);

        return $this->db->get('leads')->row();
    }

    /**
     * Add new lead to database
     * @param mixed $data lead data
     * @return mixed false || leadid
     */
    public function add($data)
    {
        if (isset($data['custom_contact_date']) || isset($data['custom_contact_date'])) {
            if (isset($data['contacted_today'])) {
                $data['lastcontact'] = date('Y-m-d H:i:s');
                unset($data['contacted_today']);
            } else {
                $data['lastcontact'] = to_sql_date($data['custom_contact_date'], true);
            }
        }

        if (isset($data['is_public']) && ($data['is_public'] == 1 || $data['is_public'] === 'on')) {
            $data['is_public'] = 1;
        } else {
            $data['is_public'] = 0;
        }

        if (!isset($data['country']) || isset($data['country']) && $data['country'] == '') {
            $data['country'] = 0;
        }

        if (isset($data['custom_contact_date'])) {
            unset($data['custom_contact_date']);
        }

        $data['description'] = nl2br($data['description']);
        $data['dateadded']   = date('Y-m-d H:i:s');
        $data['addedfrom']   = get_staff_user_id();

        $data = hooks()->apply_filters('before_lead_added', $data);

        $tags = '';
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $data['address'] = trim($data['address']);
        $data['address'] = nl2br($data['address']);

        $data['email'] = trim($data['email']);
        $this->db->insert(db_prefix() . 'leads', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Lead Added [ID: ' . $insert_id . ']');
            $this->log_lead_activity($insert_id, 'not_lead_activity_created');

            handle_tags_save($tags, $insert_id, 'lead');

            if (isset($custom_fields)) {
                handle_custom_fields_post($insert_id, $custom_fields);
            }

            $this->lead_assigned_member_notification($insert_id, $data['assigned']);
            hooks()->do_action('lead_created', $insert_id);

            return $insert_id;
        }

        return false;
    }

    public function lead_assigned_member_notification($lead_id, $assigned, $integration = false)
    {
        if (empty($assigned) || $assigned == 0) {
            return;
        }

        if ($integration == false) {
            if ($assigned == get_staff_user_id()) {
                return false;
            }
        }

        $name = $this->db->select('name')->from(db_prefix() . 'leads')->where('id', $lead_id)->get()->row()->name;

        $notification_data = [
            'description'     => ($integration == false) ? 'not_assigned_lead_to_you' : 'not_lead_assigned_from_form',
            'touserid'        => $assigned,
            'link'            => '#leadid=' . $lead_id,
            'additional_data' => ($integration == false ? serialize([
                $name,
            ]) : serialize([])),
        ];

        if ($integration != false) {
            $notification_data['fromcompany'] = 1;
        }

        if (add_notification($notification_data)) {
            pusher_trigger_notification([$assigned]);
        }

        $this->db->select('email');
        $this->db->where('staffid', $assigned);
        $email = $this->db->get(db_prefix() . 'staff')->row()->email;

        send_mail_template('lead_assigned', $lead_id, $email);

        $this->db->where('id', $lead_id);
        $this->db->update(db_prefix() . 'leads', [
            'dateassigned' => date('Y-m-d'),
        ]);

        $not_additional_data = [
            e(get_staff_full_name()),
            '<a href="' . admin_url('profile/' . $assigned) . '" target="_blank">' . e(get_staff_full_name($assigned)) . '</a>',
        ];

        if ($integration == true) {
            unset($not_additional_data[0]);
            array_values(($not_additional_data));
        }

        $not_additional_data = serialize($not_additional_data);

        $not_desc = ($integration == false ? 'not_lead_activity_assigned_to' : 'not_lead_activity_assigned_from_form');
        $this->log_lead_activity($lead_id, $not_desc, $integration, $not_additional_data);

        hooks()->do_action('after_lead_assigned_member_notification_sent', $lead_id);
    }

    /**
     * Update lead
     * @param  array $data lead data
     * @param  mixed $id   leadid
     * @return boolean
     */
    public function update($data, $id)
    {
	
        $current_lead_data = $this->get($id);
        $current_status    = $this->get_status($current_lead_data->status);
        if ($current_status) {
            $current_status_id = $current_status->id;
            $current_status    = $current_status->name;
        } else {
            if ($current_lead_data->junk == 1) {
                $current_status = _l('lead_junk');
            } elseif ($current_lead_data->lost == 1) {
                $current_status = _l('lead_lost');
            } else {
                $current_status = '';
            }
            $current_status_id = 0;
        }

        $affectedRows = 0;
        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            if (handle_custom_fields_post($id, $custom_fields)) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }
        if (!defined('API')) {
            if (isset($data['is_public'])) {
                $data['is_public'] = 1;
            } else {
                $data['is_public'] = 0;
            }

            if (!isset($data['country']) || isset($data['country']) && $data['country'] == '') {
                $data['country'] = 0;
            }

            if (isset($data['description'])) {
                $data['description'] = nl2br($data['description']);
            }
        }

        if (isset($data['lastcontact']) && $data['lastcontact'] == '' || isset($data['lastcontact']) && $data['lastcontact'] == null) {
            $data['lastcontact'] = null;
        } elseif (isset($data['lastcontact'])) {
            $data['lastcontact'] = to_sql_date($data['lastcontact'], true);
        }

        if (isset($data['tags'])) {
            if (handle_tags_save($data['tags'], $id, 'lead')) {
                $affectedRows++;
            }
            unset($data['tags']);
        }

        if (isset($data['remove_attachments'])) {
            foreach ($data['remove_attachments'] as $key => $val) {
                $attachment = $this->get_lead_attachments($id, $key);
                if ($attachment) {
                    $this->delete_lead_attachment($attachment->id);
                }
            }
            unset($data['remove_attachments']);
        }

        $data['address'] = trim($data['address']);
        $data['address'] = nl2br($data['address']);

        $data['email'] = trim($data['email']);
		
		
		
		//return print_r($data);
		
		//exit;
		
	if (!empty($data['custom_field_name']) && !empty($data['custom_field_value'])) {
  $namesx = $data['custom_field_name'];
  $valuesx = $data['custom_field_value'];

  for ($i = 0; $i < count($namesx); $i++) {
    $key = trim($namesx[$i]);
    $value = trim($valuesx[$i]);

    if ($key !== '' && $value !== '') {
      $fields[$key] = $value;
    }
  }

  // Convert to JSON
  $data['custom_field'] = json_encode($fields);
  }else{
  $data['custom_field']="";
  }
  unset($data['custom_field_name']);
  unset($data['custom_field_value']);
  

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'leads', $data);
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            if (isset($data['status']) && $current_status_id != $data['status']) {
                $this->db->where('id', $id);
                $this->db->update(db_prefix() . 'leads', [
                    'last_status_change' => date('Y-m-d H:i:s'),
                ]);
                $new_status_name = $this->get_status($data['status'])->name;
                $this->log_lead_activity($id, 'not_lead_activity_status_updated', false, serialize([
                    get_staff_full_name(),
                    $current_status,
                    $new_status_name,
                ]));

                hooks()->do_action('lead_status_changed', [
                    'lead_id'    => $id,
                    'old_status' => $current_status_id,
                    'new_status' => $data['status'],
                ]);
            }

            if (($current_lead_data->junk == 1 || $current_lead_data->lost == 1) && $data['status'] != 0) {
                $this->db->where('id', $id);
                $this->db->update(db_prefix() . 'leads', [
                    'junk' => 0,
                    'lost' => 0,
                ]);
            }

            if (isset($data['assigned'])) {
                if ($current_lead_data->assigned != $data['assigned'] && (!empty($data['assigned']) && $data['assigned'] != 0)) {
                    $this->lead_assigned_member_notification($id, $data['assigned']);
                }
            }
            log_activity('Lead Updated [ID: ' . $id . ']');

            hooks()->do_action('after_lead_updated', $id);

            return true;
        }
        if ($affectedRows > 0) {
            hooks()->do_action('after_lead_updated', $id);
            return true;
        }

        return false;
    }

    /**
     * Delete lead from database and all connections
     * @param  mixed $id leadid
     * @return boolean
     */
    public function delete($id)
    {
        $affectedRows = 0;

        hooks()->do_action('before_lead_deleted', $id);

        $lead = $this->get($id);

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'leads');
        if ($this->db->affected_rows() > 0) {
            log_activity('Lead Deleted [Deleted by: ' . get_staff_full_name() . ', ID: ' . $id . ']');

            $attachments = $this->get_lead_attachments($id);
            foreach ($attachments as $attachment) {
                $this->delete_lead_attachment($attachment['id']);
            }

            // Delete the custom field values
            $this->db->where('relid', $id);
            $this->db->where('fieldto', 'leads');
            $this->db->delete(db_prefix() . 'customfieldsvalues');

            $this->db->where('leadid', $id);
            $this->db->delete(db_prefix() . 'lead_activity_log');

            $this->db->where('leadid', $id);
            $this->db->delete(db_prefix() . 'lead_integration_emails');

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'lead');
            $this->db->delete(db_prefix() . 'notes');

            $this->db->where('rel_type', 'lead');
            $this->db->where('rel_id', $id);
            $this->db->delete(db_prefix() . 'reminders');

            $this->db->where('rel_type', 'lead');
            $this->db->where('rel_id', $id);
            $this->db->delete(db_prefix() . 'taggables');

            $this->load->model('proposals_model');
            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'lead');
            $proposals = $this->db->get(db_prefix() . 'proposals')->result_array();

            foreach ($proposals as $proposal) {
                $this->proposals_model->delete($proposal['id']);
            }

            // Get related tasks
            $this->db->where('rel_type', 'lead');
            $this->db->where('rel_id', $id);
            $tasks = $this->db->get(db_prefix() . 'tasks')->result_array();
            foreach ($tasks as $task) {
                $this->tasks_model->delete_task($task['id']);
            }

            if (is_gdpr()) {
                $this->db->where('(description LIKE "%' . $lead->email . '%" OR description LIKE "%' . $lead->name . '%" OR description LIKE "%' . $lead->phonenumber . '%")');
                $this->db->delete(db_prefix() . 'activity_log');
            }

            $affectedRows++;
        }
        if ($affectedRows > 0) {
            hooks()->do_action('after_lead_deleted', $id);
            return true;
        }

        return false;
    }

    /**
     * Mark lead as lost
     * @param  mixed $id lead id
     * @return boolean
     */
    public function mark_as_lost($id)
    {
        $this->db->select('status');
        $this->db->from(db_prefix() . 'leads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->status;

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'leads', [
            'lost'               => 1,
            'status'             => 0,
            'last_status_change' => date('Y-m-d H:i:s'),
            'last_lead_status'   => $last_lead_status,
        ]);

        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_marked_lost');

            log_activity('Lead Marked as Lost [ID: ' . $id . ']');

            hooks()->do_action('lead_marked_as_lost', $id);

            return true;
        }

        return false;
    }

    /**
     * Unmark lead as lost
     * @param  mixed $id leadid
     * @return boolean
     */
    public function unmark_as_lost($id)
    {
        $this->db->select('last_lead_status');
        $this->db->from(db_prefix() . 'leads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->last_lead_status;

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'leads', [
            'lost'   => 0,
            'status' => $last_lead_status,
        ]);
        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_unmarked_lost');

            log_activity('Lead Unmarked as Lost [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Mark lead as junk
     * @param  mixed $id lead id
     * @return boolean
     */
    public function mark_as_junk($id)
    {
        $this->db->select('status');
        $this->db->from(db_prefix() . 'leads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->status;

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'leads', [
            'junk'               => 1,
            'status'             => 0,
            'last_status_change' => date('Y-m-d H:i:s'),
            'last_lead_status'   => $last_lead_status,
        ]);

        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_marked_junk');

            log_activity('Lead Marked as Junk [ID: ' . $id . ']');

            hooks()->do_action('lead_marked_as_junk', $id);

            return true;
        }

        return false;
    }

    /**
     * Unmark lead as junk
     * @param  mixed $id leadid
     * @return boolean
     */
    public function unmark_as_junk($id)
    {
        $this->db->select('last_lead_status');
        $this->db->from(db_prefix() . 'leads');
        $this->db->where('id', $id);
        $last_lead_status = $this->db->get()->row()->last_lead_status;

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'leads', [
            'junk'   => 0,
            'status' => $last_lead_status,
        ]);
        if ($this->db->affected_rows() > 0) {
            $this->log_lead_activity($id, 'not_lead_activity_unmarked_junk');
            log_activity('Lead Unmarked as Junk [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Get lead attachments
     * @since Version 1.0.4
     * @param  mixed $id lead id
     * @return array
     */
    public function get_lead_attachments($id = '', $attachment_id = '', $where = [])
    {
        $this->db->where($where);
        $idIsHash = !is_numeric($attachment_id) && strlen($attachment_id) == 32;
        if (is_numeric($attachment_id) || $idIsHash) {
            $this->db->where($idIsHash ? 'attachment_key' : 'id', $attachment_id);

            return $this->db->get(db_prefix() . 'files')->row();
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'lead');
        $this->db->order_by('dateadded', 'DESC');

        return $this->db->get(db_prefix() . 'files')->result_array();
    }

    public function add_attachment_to_database($lead_id, $attachment, $external = false, $form_activity = false)
    {
        $this->misc_model->add_attachment_to_database($lead_id, 'lead', $attachment, $external);

        if ($form_activity == false) {
            $this->leads_model->log_lead_activity($lead_id, 'not_lead_activity_added_attachment');
        } else {
            $this->leads_model->log_lead_activity($lead_id, 'not_lead_activity_log_attachment', true, serialize([
                $form_activity,
            ]));
        }

        // No notification when attachment is imported from web to lead form
        if ($form_activity == false) {
            $lead         = $this->get($lead_id);
            $not_user_ids = [];
            if ($lead->addedfrom != get_staff_user_id()) {
                array_push($not_user_ids, $lead->addedfrom);
            }
            if ($lead->assigned != get_staff_user_id() && $lead->assigned != 0) {
                array_push($not_user_ids, $lead->assigned);
            }
            $notifiedUsers = [];
            foreach ($not_user_ids as $uid) {
                $notified = add_notification([
                    'description'     => 'not_lead_added_attachment',
                    'touserid'        => $uid,
                    'link'            => '#leadid=' . $lead_id,
                    'additional_data' => serialize([
                        $lead->name,
                    ]),
                ]);
                if ($notified) {
                    array_push($notifiedUsers, $uid);
                }
            }
            pusher_trigger_notification($notifiedUsers);
        }
    }

    /**
     * Delete lead attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_lead_attachment($id)
    {
        $attachment = $this->get_lead_attachments('', $id);
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('lead') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete(db_prefix() . 'files');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                log_activity('Lead Attachment Deleted [ID: ' . $attachment->rel_id . ']');
            }

            if (is_dir(get_upload_path_by_type('lead') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('lead') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('lead') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    // Sources

    /**
     * Get leads sources
     * @param  mixed $id Optional - Source ID
     * @return mixed object if id passed else array
     */
    public function get_source($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'leads_sources')->row();
        }

        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'leads_sources')->result_array();
    }

    /**
     * Add new lead source
     * @param mixed $data source data
     */
    public function add_source($data)
    {
        $this->db->insert(db_prefix() . 'leads_sources', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Leads Source Added [SourceID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
        }

        return $insert_id;
    }

    /**
     * Update lead source
     * @param  mixed $data source data
     * @param  mixed $id   source id
     * @return boolean
     */
    public function update_source($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'leads_sources', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Leads Source Updated [SourceID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete lead source from database
     * @param  mixed $id source id
     * @return mixed
     */
    public function delete_source($id)
    {
        $current = $this->get_source($id);
        // Check if is already using in table
        if (is_reference_in_table('source', db_prefix() . 'leads', $id) || is_reference_in_table('lead_source', db_prefix() . 'leads_email_integration', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'leads_sources');
        if ($this->db->affected_rows() > 0) {
            if (get_option('leads_default_source') == $id) {
                update_option('leads_default_source', '');
            }
            log_activity('Leads Source Deleted [SourceID: ' . $id . ']');

            return true;
        }

        return false;
    }

    // Statuses

    /**
     * Get lead statuses
     * @param  mixed $id status id
     * @return mixed      object if id passed else array
     */
    public function get_status($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'leads_status')->row();
        }

        $whereKey = md5(serialize($where));
      
        $statuses = $this->app_object_cache->get('leads-all-statuses-'.$whereKey);

        if (!$statuses) {
            $this->db->where($where);
            $this->db->order_by('statusorder', 'asc');

            $statuses = $this->db->get(db_prefix() . 'leads_status')->result_array();
            $this->app_object_cache->add('leads-all-statuses-'.$whereKey, $statuses);
        }

        //print_r($statuses);//exit;
        return $statuses;
    }
////////////////////////// Deal Status /////////////////////////	

	 public function get_deal_status($id = '', $where = [])
       {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'deals_status')->row();
        }
       
            $this->db->where($where);
            $this->db->order_by('statusorder', 'asc');
            $result = $this->db->get(db_prefix() . 'deals_status')->result_array();
            return $result;
    }
	
	 public function add_deal_status($data)
    {
        if (isset($data['color']) && $data['color'] == '') {
            $data['color'] = hooks()->apply_filters('default_lead_status_color', '#757575');
        }

        if (!isset($data['statusorder'])) {
            $data['statusorder'] = total_rows(db_prefix() . 'deals_status') + 1;
        }

        $this->db->insert(db_prefix() . 'deals_status', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Deal Status Added [StatusID: ' . $insert_id . ', Name: ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    public function update_deal_status($data, $id)
    {
	//print_r($data);echo $id;exit;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'deals_status', $data);
		 //echo $this->db->last_query(); echo $this->db->affected_rows(); exit;
        if ($this->db->affected_rows() > 0) {
            //log_activity('Deal Status Updated [StatusID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete lead status from database
     * @param  mixed $id status id
     * @return boolean
     */
    public function delete_deal_status($id)
    {
       
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'deals_status');
        if ($this->db->affected_rows() > 0) {
            
            log_activity('Deal Status Deleted [StatusID: ' . $id . ']');

            return true;
        }

        return false;
    }
////////////////////////// Deal Status /////////////////////////


////////////////////////// UW Status /////////////////////////	
	 public function get_uw_status($id = '', $where = [])
     {
        /*if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'deal_quotation')->row();
        }*/
            
		if (!is_admin() && get_staff_rolex()<>4) {
		$this->db->where(db_prefix() . 'leads.assigned', $_SESSION['staff_logged_in']);	// Use condition
		}
			
		$this->db->select('' . db_prefix() . 'deal_quotation.*,leads.name,leads.company,leads.email');
        $this->db->join(db_prefix() . 'leads', '' . db_prefix() . 'leads.id=' . db_prefix() . 'deal_quotation.deal_id');
		$this->db->order_by(db_prefix() . 'deal_quotation.id', 'DESC');
        $result     = $this->db->get(db_prefix() . 'deal_quotation')->result_array();
		//echo $this->db->last_query();exit;	
        return $result;
    }

////////////////////////// End UW Status /////////////////////////	
	
	//=========================Task=====================
	 public function get_task_status($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'task_status')->row();
        }


       
            $this->db->where($where);
            $this->db->order_by('statusorder', 'asc');

            $result = $this->db->get(db_prefix() . 'task_status')->result_array();
           
       

        //print_r($statuses);//exit;
        return $result;
    }
	
	 public function add_task_status($data)
    {
        if (isset($data['color']) && $data['color'] == '') {
            $data['color'] = hooks()->apply_filters('default_lead_status_color', '#757575');
        }

        if (!isset($data['statusorder'])) {
            $data['statusorder'] = total_rows(db_prefix() . 'task_status') + 1;
        }

        $this->db->insert(db_prefix() . 'task_status', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Task Status Added [StatusID: ' . $insert_id . ', Name: ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    public function update_task_status($data, $id)
    {
	//print_r($data);echo $id;exit;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'task_status', $data);
		 //echo $this->db->last_query(); echo $this->db->affected_rows(); exit;
        if ($this->db->affected_rows() > 0) {
            //log_activity('Deal Status Updated [StatusID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete lead status from database
     * @param  mixed $id status id
     * @return boolean
     */
    public function delete_task_status($id)
    {
       
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'task_status');
        if ($this->db->affected_rows() > 0) {
            
            log_activity('Task Status Deleted [StatusID: ' . $id . ']');

            return true;
        }

        return false;
    }
	//======================END TASK========================
	
	public function get_tags_list($id = '', $where = [])
    {
       

      
        $tagses = $this->db->get(db_prefix() . 'tags')->result_array();

        return $tagses;
    }

    /**
     * Add new lead status
     * @param array $data lead status data
     */
    public function add_status($data)
    {
        if (isset($data['color']) && $data['color'] == '') {
            $data['color'] = hooks()->apply_filters('default_lead_status_color', '#757575');
        }

        if (!isset($data['statusorder'])) {
            $data['statusorder'] = total_rows(db_prefix() . 'leads_status') + 1;
        }

        $this->db->insert(db_prefix() . 'leads_status', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Leads Status Added [StatusID: ' . $insert_id . ', Name: ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    public function update_status($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'leads_status', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Leads Status Updated [StatusID: ' . $id . ', Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Delete lead status from database
     * @param  mixed $id status id
     * @return boolean
     */
    public function delete_status($id)
    {
        $current = $this->get_status($id);
        // Check if is already using in table
        if (is_reference_in_table('status', db_prefix() . 'leads', $id) || is_reference_in_table('lead_status', db_prefix() . 'leads_email_integration', $id)) {
            return [
                'referenced' => true,
            ];
        }

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'leads_status');
        if ($this->db->affected_rows() > 0) {
            if (get_option('leads_default_status') == $id) {
                update_option('leads_default_status', '');
            }
            log_activity('Leads Status Deleted [StatusID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * Update canban lead status when drag and drop
     * @param  array $data lead data
     * @return boolean
     */
    public function update_lead_status($data)
    {

        $this->db->select('status, assigned');
        $this->db->where('id', $data['leadid']);
        $_old = $this->db->get(db_prefix() . 'leads')->row();
        $old_status = '';

        if ($_old) {
            $old_status = $this->get_status($_old->status);
            if ($old_status) {
                $old_status = $old_status->name;
            }
        }

        $affectedRows   = 0;
        $current_status = $this->get_status($data['status'])->name;

        $this->db->where('id', $data['leadid']);
        $this->db->update(db_prefix() . 'leads', [
            'status' => $data['status'],
        ]);

        $_log_message = '';

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
            // Notify the user that the lead status has been changed
                        // add_notification
                $notification_data = [
                    'description'     => 'lead_stauss_updated',
                    'touserid'        => $_old->assigned,
                    'link'            => 'leads/index/' . $data['leadid']
                ];
                if (add_notification($notification_data)) {
                    pusher_trigger_notification([$_old->assigned]);
                }

            if ($current_status != $old_status && $old_status != '') {
                $_log_message    = 'not_lead_activity_status_updated';
                $additional_data = serialize([
                    get_staff_full_name(),
                    $old_status,
                    $current_status,
                ]);

                hooks()->do_action('lead_status_changed', [
                    'lead_id'    => $data['leadid'],
                    'old_status' => $old_status,
                    'new_status' => $current_status,
                ]);
            }
            $this->db->where('id', $data['leadid']);
            $this->db->update(db_prefix() . 'leads', [
                'last_status_change' => date('Y-m-d H:i:s'),
            ]);
        }

        if (isset($data['order'])) {
            AbstractKanban::updateOrder($data['order'], 'leadorder', 'leads', $data['status']);
        }

        if ($affectedRows > 0) {
            if ($_log_message == '') {
                return true;
            }

            $this->log_lead_activity($data['leadid'], $_log_message, false, $additional_data);

            return true;
        }

        return false;
    }

    /* Ajax */

    /**
     * All lead activity by staff
     * @param  mixed $id lead id
     * @return array
     */
    public function get_lead_activity_log($id)
    {
        $sorting = hooks()->apply_filters('lead_activity_log_default_sort', 'DESC');

        $this->db->where('leadid', $id);
        $this->db->order_by('date', $sorting);
		

        return $this->db->get(db_prefix() . 'lead_activity_log')->result_array();
    }

    public function staff_can_access_lead($id, $staff_id = '')
    {
        $staff_id = $staff_id == '' ? get_staff_user_id() : $staff_id;

        if (has_permission('leads', $staff_id, 'view')) {
            return true;
        }

        $CI = &get_instance();

        if (total_rows(db_prefix() . 'leads', 'id="' . $CI->db->escape_str($id) . '" AND (assigned=' . $CI->db->escape_str($staff_id) . ' OR is_public=1 OR addedfrom=' . $CI->db->escape_str($staff_id) . ')') > 0) {
            return true;
        }

        return false;
    }

    /**
     * Add lead activity from staff
     * @param  mixed  $id          lead id
     * @param  string  $description activity description
     */
    public function log_lead_activity($id, $description, $integration = false, $additional_data = '')
    {
        $log = [
            'date'            => date('Y-m-d H:i:s'),
            'description'     => $description,
            'leadid'          => $id,
            'staffid'         => get_staff_user_id(),
            'additional_data' => $additional_data,
            'full_name'       => get_staff_full_name(get_staff_user_id()),
        ];
        if ($integration == true) {
            $log['staffid']   = 0;
            $log['full_name'] = '[CRON]';
        }

        $this->db->insert(db_prefix() . 'lead_activity_log', $log);

        return $this->db->insert_id();
    }

    /**
     * Get email integration config
     * @return object
     */
    public function get_email_integration()
    {
        $this->db->where('id', 1);

        return $this->db->get(db_prefix() . 'leads_email_integration')->row();
    }

    /**
     * Get lead imported email activity
     * @param  mixed $id leadid
     * @return array
     */
    public function get_mail_activity($id)
    {
        $this->db->where('leadid', $id);
        $this->db->order_by('dateadded', 'asc');

        return $this->db->get(db_prefix() . 'lead_integration_emails')->result_array();
    }

    /**
     * Update email integration config
     * @param  mixed $data All $_POST data
     * @return boolean
     */
    public function update_email_integration($data)
    {
        $this->db->where('id', 1);
        $original_settings = $this->db->get(db_prefix() . 'leads_email_integration')->row();

        $data['create_task_if_customer']        = isset($data['create_task_if_customer']) ? 1 : 0;
        $data['active']                         = isset($data['active']) ? 1 : 0;
        $data['delete_after_import']            = isset($data['delete_after_import']) ? 1 : 0;
        $data['notify_lead_imported']           = isset($data['notify_lead_imported']) ? 1 : 0;
        $data['only_loop_on_unseen_emails']     = isset($data['only_loop_on_unseen_emails']) ? 1 : 0;
        $data['notify_lead_contact_more_times'] = isset($data['notify_lead_contact_more_times']) ? 1 : 0;
        $data['mark_public']                    = isset($data['mark_public']) ? 1 : 0;
        $data['responsible']                    = !isset($data['responsible']) ? 0 : $data['responsible'];

        if ($data['notify_lead_contact_more_times'] != 0 || $data['notify_lead_imported'] != 0) {
            if (isset($data['notify_type']) && $data['notify_type'] == 'specific_staff') {
                if (isset($data['notify_ids_staff'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_staff']);
                    unset($data['notify_ids_staff']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_staff']);
                }
                if (isset($data['notify_ids_roles'])) {
                    unset($data['notify_ids_roles']);
                }
            } else {
                if (isset($data['notify_ids_roles'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_roles']);
                    unset($data['notify_ids_roles']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_roles']);
                }
                if (isset($data['notify_ids_staff'])) {
                    unset($data['notify_ids_staff']);
                }
            }
        } else {
            $data['notify_ids']  = serialize([]);
            $data['notify_type'] = null;
            if (isset($data['notify_ids_staff'])) {
                unset($data['notify_ids_staff']);
            }
            if (isset($data['notify_ids_roles'])) {
                unset($data['notify_ids_roles']);
            }
        }

        // Check if not empty $data['password']
        // Get original
        // Decrypt original
        // Compare with $data['password']
        // If equal unset
        // If not encrypt and save
        if (!empty($data['password'])) {
            $or_decrypted = $this->encryption->decrypt($original_settings->password);
            if ($or_decrypted == $data['password']) {
                unset($data['password']);
            } else {
                $data['password'] = $this->encryption->encrypt($data['password']);
            }
        }

        $this->db->where('id', 1);
        $this->db->update(db_prefix() . 'leads_email_integration', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }

    public function change_status_color($data)
    {
        $this->db->where('id', $data['status_id']);
        $this->db->update(db_prefix() . 'leads_status', [
            'color' => $data['color'],
        ]);
    }

    public function update_status_order($data)
    {
        foreach ($data['order'] as $status) {
            $this->db->where('id', $status[0]);
            $this->db->update(db_prefix() . 'leads_status', [
                'statusorder' => $status[1],
            ]);
        }
    }

    public function get_form($where)
    {
        $this->db->where($where);

        return $this->db->get(db_prefix() . 'web_to_lead')->row();
    }

    public function add_form($data)
    {
        $data                       = $this->_do_lead_web_to_form_responsibles($data);
        $data['success_submit_msg'] = nl2br($data['success_submit_msg']);
        $data['form_key']           = app_generate_hash();

        $data['create_task_on_duplicate'] = (int) isset($data['create_task_on_duplicate']);
        $data['mark_public']              = (int) isset($data['mark_public']);

        if (isset($data['allow_duplicate'])) {
            $data['allow_duplicate']           = 1;
            $data['track_duplicate_field']     = '';
            $data['track_duplicate_field_and'] = '';
            $data['create_task_on_duplicate']  = 0;
        } else {
            $data['allow_duplicate'] = 0;
        }

        $data['dateadded'] = date('Y-m-d H:i:s');

        $this->db->insert(db_prefix() . 'web_to_lead', $data);
		//echo $this->db->last_query();exit;
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Web to Lead Form Added [' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }

    public function update_form($id, $data)
    {
        $data                       = $this->_do_lead_web_to_form_responsibles($data);
        $data['success_submit_msg'] = nl2br($data['success_submit_msg']);

        $data['create_task_on_duplicate'] = (int) isset($data['create_task_on_duplicate']);
        $data['mark_public']              = (int) isset($data['mark_public']);

        if (isset($data['allow_duplicate'])) {
            $data['allow_duplicate']           = 1;
            $data['track_duplicate_field']     = '';
            $data['track_duplicate_field_and'] = '';
            $data['create_task_on_duplicate']  = 0;
        } else {
            $data['allow_duplicate'] = 0;
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'web_to_lead', $data);

        return ($this->db->affected_rows() > 0 ? true : false);
    }

    public function delete_form($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'web_to_lead');

        $this->db->where('from_form_id', $id);
        $this->db->update(db_prefix() . 'leads', [
            'from_form_id' => 0,
        ]);

        if ($this->db->affected_rows() > 0) {
            log_activity('Lead Form Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    private function _do_lead_web_to_form_responsibles($data)
    {
        if (isset($data['notify_lead_imported'])) {
            $data['notify_lead_imported'] = 1;
        } else {
            $data['notify_lead_imported'] = 0;
        }

        if ($data['responsible'] == '') {
            $data['responsible'] = 0;
        }
        if ($data['notify_lead_imported'] != 0) {
            if ($data['notify_type'] == 'specific_staff') {
                if (isset($data['notify_ids_staff'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_staff']);
                    unset($data['notify_ids_staff']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_staff']);
                }
                if (isset($data['notify_ids_roles'])) {
                    unset($data['notify_ids_roles']);
                }
            } else {
                if (isset($data['notify_ids_roles'])) {
                    $data['notify_ids'] = serialize($data['notify_ids_roles']);
                    unset($data['notify_ids_roles']);
                } else {
                    $data['notify_ids'] = serialize([]);
                    unset($data['notify_ids_roles']);
                }
                if (isset($data['notify_ids_staff'])) {
                    unset($data['notify_ids_staff']);
                }
            }
        } else {
            $data['notify_ids']  = serialize([]);
            $data['notify_type'] = null;
            if (isset($data['notify_ids_staff'])) {
                unset($data['notify_ids_staff']);
            }
            if (isset($data['notify_ids_roles'])) {
                unset($data['notify_ids_roles']);
            }
        }

        return $data;
    }

    public function do_kanban_query($status, $search = '', $page = 1, $sort = [], $count = false)
    {
        _deprecated_function('Leads_model::do_kanban_query', '2.9.2', 'LeadsKanban class');

        $kanBan = (new LeadsKanban($status))
            ->search($search)
            ->page($page)
            ->sortBy($sort['sort'] ?? null, $sort['sort_by'] ?? null);

        if ($count) {
            return $kanBan->countAll();
        }

        return $kanBan->get();
    }
    public function tech_wizard_insert_lead($data) {
        // Insert data into the database
        $this->db->insert('it_crm_leads', $data);
        return $this->db->insert_id(); // Return the last inserted ID
    }
    public function get_all_whatsapp_data(){
        $this->db->where('source', 3);
		if (!is_admin()) {
		$this->db->where('assigned', $_SESSION['staff_logged_in']);	// Use condition
		}
		$query = $this->db->get('leads'); // Get data from leads
		return $query->result_array(); // Return the result as an associative array
    }
    public function updateAssignedUser($lead_id, $assigned_id){
        $current_lead_data = $this->get($lead_id);
		
        // Check if the current lead status is 2
        if ($current_lead_data->status == 2) {
            $this->db->where('id', $lead_id);
            $this->db->update(db_prefix() . 'leads', [
                'assigned' => $assigned_id,
                'status' => 3, // Update status to 3 only when it is 2
            ]);
        } else {
            $this->db->where('id', $lead_id);
            $this->db->update(db_prefix() . 'leads', [
                'assigned' => $assigned_id,
            ]);
        }
        if (isset($assigned_id)) {
            if ($current_lead_data->assigned != $assigned_id && (!empty($assigned_id) && $assigned_id != 0)) {
                $this->lead_assigned_member_notification($lead_id, $assigned_id);
            }
        }
        return true;
    }
	
	public function updateAssignedAbsorber($lead_id, $assigned_id){
        
            $this->db->where('id', $lead_id);
            $this->db->update(db_prefix() . 'leads', [
                'absorber' => $assigned_id,
            ]);
        
        return true;
    }
	
    
	
	public function getAllDepartments(){
        $this->db->select('departmentid, name');
        $resultArray = $this->db->get(db_prefix() . 'departments')->result_array();
        if($resultArray){
            return $resultArray;
        }
    }
	
	function getLocationFromIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // IP from shared internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // IP passed from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // Direct IP address
        $ip = $_SERVER['REMOTE_ADDR'];
    }
	
	if($ip=="::1" || $ip==""){
	$ip="66.249.79.194";
	}
	
	$apiURL = "http://ipinfo.io/{$ip}/json";
	$response = @file_get_contents($apiURL);
	$data = json_decode($response, true);
    if (isset($data['country']) && isset($data['region'])) {
        return [
            'country' => $data['country'],
            'region' => $data['region'],
            'city' => $data['city'] ?? '',
			'postal' => $data['postal'] ?? '',
            'ip' => $ip
        ];
    } else {
        return 1;
    }
	
    
   }
   
   public function get_deal_task($id = '')
    {   
	    if(isset($id)&&$id){
        $this->db->where('rel_id', $id);
		}
		
        $this->db->order_by('date', 'asc');
        return $this->db->get(db_prefix() . 'deal_task')->result_array();
    }
    public function get_task_type($id)
    {
	
	
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . 'task_status')->result_array();
		
    }
	
	
	public function add_deal_task($data, $rel_type, $rel_id)
    {
        
        $data['staff']   = get_staff_user_id();
        $data['rel_id']      = $rel_id;
        $data['description'] = nl2br($data['description']);
        $insert_data = $data;
        unset($insert_data['assigned_id']); // Remove assign_id only from insert data
        $this->db->insert(db_prefix() . 'deal_task', $insert_data);
        $insert_id = $this->db->insert_id();
        $assigned = $data['assigned_id'];
        if ($insert_id) {
            // add_notification
            $notification_data = [
                'description'     => 'lead_todo_task_created',
                'touserid'        => $assigned,
                'link'            => 'leads/index/' . $rel_id
            ];
            if (add_notification($notification_data)) {
                pusher_trigger_notification([$assigned]);
            }
            hooks()->do_action('note_created', $insert_id, $data);

            return $insert_id;
        }

        return false;
    }
	
	public function convert_to_deal($data,$id)
    {
	    $this->db->where('id', $id);
        $this->db->update(db_prefix().'leads', $data);
        if ($this->db->affected_rows() > 0) {
		$this->log_lead_activity($id,'Lead converted to deal');
		return "Lead converted to deal";
		}else{
		return "Lead not converted";
		}
    }
	
	public function change_task_status($data,$id)
    {
	    $this->db->where('id', $id);
        $this->db->update(db_prefix().'deal_task', $data);
        if ($this->db->affected_rows() > 0) {
		$this->log_lead_activity($id,'Task Completed');
		return "Task Completed";
		}else{
		return "Task not Completed";
		}
    }
	
   	public function get_deal_status_title($id)
    {
	    $this->db->select('name,color');
        //$this->db->from(db_prefix() . 'deals_status');
        $this->db->where('id', $id);
        //$deal_status = $this->db->get()->row();
		$deal_status = $this->db->get(db_prefix() . 'deals_status')->result_array();
		return $deal_status = "<a href='#' class='btn btn-default' style='background:".$deal_status[0]['color']."'>".$deal_status[0]['name']."</a>";
       
    }
	
	public function get_deal_status_data($id)
    {
	    $this->db->select('name');
        $this->db->where('id', $id);
		$deal_status = $this->db->get(db_prefix() . 'deals_status')->result_array();
		return $deal_status[0]['name'];
       
    }
	
	public function get_deal_status_color($id)
    {
	    $this->db->select('color');
        $this->db->where('id', $id);
		$deal_status = $this->db->get(db_prefix() . 'deals_status')->result_array();
		return $deal_status[0]['color'];
       
    }
	
	public function get_deal_name_companyname($id)
    {
	    $this->db->select('name,company,website');
        $this->db->where('id', $id);
		return $deal_status = $this->db->get(db_prefix() . 'leads')->result_array();
    }
	
	public function updateleads($data,$id)
    {
	    
		
		//echo $id;
		
		if (isset($data['vtype'])&&$data['vtype']=="doc") {
		
		
		
		$lead_id=$data['deal_id'];
		
		unset($data['deal_id']);
		unset($data['vtype']);
		
		// For Ownearship info
		// Create associative array and encode to JSON
		$data['ownership_info1'] = json_encode([
		'ownership_name'  => $data['ownership_name'],
		'ownership_share' => $data['ownership_share'],
		'ownership_address' => $data['ownership_address'],
		'ownership_city'    => $data['ownership_city'],
		'ownership_town' => $data['ownership_town'],
		'ownership_state' => $data['ownership_state'],
		'ownership_zip' => $data['ownership_zip'],
		'ownership_email' => $data['ownership_email'],
		'ownership_phone' => $data['ownership_phone']
		]);
		
		unset($data['ownership_name']);
		unset($data['ownership_share']);
		unset($data['ownership_address']);
		unset($data['ownership_city']);
		unset($data['ownership_town']);
		unset($data['ownership_state']);
		unset($data['ownership_zip']);
		unset($data['ownership_email']);
		unset($data['ownership_phone']);
		
		// For Ownearship info
		// Create associative array and encode to JSON
		$data['ownership_info2'] = json_encode([
		'ownership_name'  => $data['ownership_name2'],
		'ownership_share' => $data['ownership_share2'],
		'ownership_address' => $data['ownership_address2'],
		'ownership_city'    => $data['ownership_city2'],
		'ownership_town' => $data['ownership_town2'],
		'ownership_state' => $data['ownership_state2'],
		'ownership_zip' => $data['ownership_zip2'],
		'ownership_email' => $data['ownership_email2'],
		'ownership_phone' => $data['ownership_phone2']
		]);
		
		unset($data['ownership_name2']);
		unset($data['ownership_share2']);
		unset($data['ownership_address2']);
		unset($data['ownership_city2']);
		unset($data['ownership_town2']);
		unset($data['ownership_state2']);
		unset($data['ownership_zip2']);
		unset($data['ownership_email2']);
		unset($data['ownership_phone2']);
		
		
		// For Bank Details
		// Create associative array and encode to JSON
		$data['bank_info'] = json_encode([
		'account_holder_name'  => $data['account_holder_name'],
		'account_holder_address' => $data['account_holder_address'],
		'account_holder_country' => $data['account_holder_country'],
		'bank_name'    => $data['bank_name'],
		'bank_address' => $data['bank_address'],
		'bank_country' => $data['bank_country'],
		'Bank_swift_routing_iban' => $data['Bank_swift_routing_iban'],
		'bank_account_number' => $data['bank_account_number']
		]);
		
		unset($data['account_holder_name']);
		unset($data['account_holder_address']);
		unset($data['account_holder_country']);
		unset($data['bank_name']);
		unset($data['bank_address']);
		unset($data['bank_country']);
		unset($data['Bank_swift_routing_iban']);
		unset($data['bank_account_number']);
	
		
		$data['deal_id']=$lead_id;
		 $this->db->insert(db_prefix().'deal_document', $data);
		 unset($data['deal_id']);
		
		
		if ($this->db->affected_rows() > 0) {
		$datax['deal_status']=3;
		$datax['last_status_change']= date('Y-m-d H:i:s');
		$this->db->where('id', $id);
        $this->db->update(db_prefix().'leads', $datax);
		$log_message=" Converted this lead to UW";
        $this->log_lead_activity($id, $log_message);
		// For Email Data
		$this->db->select('name,company,description,country,address,email,website,country_code,phonenumber,BusinessNature,MonthlyVolume,IncorporationCountry,AverageProductPrice,products_services,descriptor,processing_history,subject,target_countries,website_info,old_history');
		$this->db->where('id', $id);
        $dealdata=$this->db->get('leads')->row();
        $dealdata->country=get_country($dealdata->country)->short_name ?? null; // Get Country name from country code
		$dealdata->IncorporationCountry=get_country($dealdata->IncorporationCountry)->short_name ?? null; // Get Country name from country code
		$dealdata->target_countries=$dealdata->target_countries ?? null; // Get Country name from country code
		
		
		
		if(isset($dealdata->website_info)&&$dealdata->website_info){
		$dealdata->website_info=$this->leads_model->jsonToTable($dealdata->website_info);
		}
		
		if(isset($dealdata->old_history)&&$dealdata->old_history){
		$dealdata->old_history=$this->leads_model->jsonToTable($dealdata->old_history);
		}
		
		
		//print_r($dealdata);exit;
		
		//Get UW Department email
		$this->db->select('email,staffid');
		$this->db->where('role', 4);
		$this->db->limit(1);
        $uwstaff=$this->db->get('staff')->row();
		
		
		$staffemail = isset($uwstaff->email) ? strtolower($uwstaff->email) : "udayj@itio.in";
		$staffidx = isset($uwstaff->staffid) ? strtolower($uwstaff->staffid) : "12";
		///////////////////////////////////////
		$cname=$this->leads_model->get_deal_name_companyname($lead_id);
		$companyname = isset($cname[0]['company']) ? $cname[0]['company'] : $cname[0]['name'];
		
		if(isset($cname[0]['website'])&&$cname[0]['website']){
		$datax['website']=$cname[0]['website'];
		$companyname=$companyname." # ".$lead_id." - ".$datax['website'];
		}
		
		send_mail_template('lead_assigned_to_uw', $staffemail, $staffidx, $lead_id, $dealdata, $companyname);
		
		///////////////////End Email///////////////////////////////////////////////////
		
		}
		
		
		
		}elseif (isset($data['vtype'])&&$data['vtype']=="uw") {
		
		
		$lead_id=$data['deal_id'];
		unset($data['deal_id']);
		unset($data['vtype']);
		$assigned_id=$data['assigned_id'];
		unset($data['assigned_id']);
		
		if($data["quotation_status"]==0){
		unset($data['MDR']);
		unset($data['SetupFee']);
		unset($data['HoldBack']);
		unset($data['CardType']);
		unset($data['Settlement']);
		unset($data['SettlementFee']);
		unset($data['MinSettlement']);
		unset($data['MonthlyFee']);
		unset($data['Descriptor']);
		
		
		$data['deal_id']=$lead_id;
		 $this->db->insert(db_prefix().'deal_quotation', $data);
		 unset($data['deal_id']);
		 $data["quotation_status"]="Rejected";
		//echo $this->db->last_query();
		
		if ($this->db->affected_rows() > 0) {
		$datax['deal_status']=2;
		$datax['last_status_change']= date('Y-m-d H:i:s');
		$this->db->where('id', $id);
        $this->db->update(db_prefix().'leads', $datax);
		$log_message=" Rejected and Converted this lead to Document";
        $this->log_lead_activity($id, $log_message);
		}
		
		
		}else{
		
		unset($data['vtype']);
		unset($data['Reason']);
		$data['CardType']=json_encode($data['CardType']);
		$data['deal_id']=$lead_id;
		
		 $this->db->insert(db_prefix().'deal_quotation', $data);
		 unset($data['deal_id']);
		 $data["quotation_status"]="Approved";
		//echo $this->db->last_query();
		
		if ($this->db->affected_rows() > 0) {
		$datax['deal_status']=4;
		$datax['last_status_change']= date('Y-m-d H:i:s');
		$this->db->where('id', $id);
        $this->db->update(db_prefix().'leads', $datax);
		$log_message=" Approved and Converted this lead to Final Invoice";
        $this->log_lead_activity($id, $log_message);
		}
		
		}
		
		$deal_statusx="";
		if(isset($datax['deal_status'])&&$datax['deal_status']==4){
		$deal_statusx=$datax['deal_status']="Final Invoice";
		}else{
		$deal_statusx=$datax['deal_status']="Documentation";
		}
		//////////////////////////////////////////////
		//Get UW Department email
		$this->db->select('email,staffid');
		$this->db->where('staffid', $assigned_id);
		$this->db->limit(1);
        $uwstaff=$this->db->get('staff')->row();
		
		
		$staffemail = isset($uwstaff->email) ? strtolower($uwstaff->email) : "udayj@itio.in";
		$staffidx = isset($uwstaff->staffid) ? strtolower($uwstaff->staffid) : "12";
		///////////////////////////////////////
		$datax=array_merge($data,$datax);
		$cname=$this->leads_model->get_deal_name_companyname($lead_id);
		
		
		
		
		$companyname = isset($cname[0]['company']) ? $cname[0]['company'] : $cname[0]['name'];
		
		if(isset($cname[0]['website'])&&$cname[0]['website']){
		$datax['website']=$cname[0]['website'];
		$companyname=$companyname." # ".$lead_id." - ".$datax['website'];
		}
		//print_r($datax);exit;	
	$keyChanges = [
    'MDR' => 'MDR (%)',
    'SetupFee' => 'Setup Fee (USD)',
    'HoldBack' => 'Hold Back (%)',
    'CardType' => 'Card Type',
    'Settlement' => 'Settlement (No. of Working Day)',
    'SettlementFee' => 'Settlement Fee',
    'MinSettlement' => 'Minimum Settlement',
    'MonthlyFee' => 'Monthly Fee (USD)',
    'website' => 'Website URL'
];
$datax = [];
foreach ($data as $key => $value) {
    $newKey = isset($keyChanges[$key]) ? $keyChanges[$key] : $key;
    $datax[$newKey] = $value;
}
	$datax['deal_status']=$deal_statusx;
	//print_r($datax);exit;	
	
		send_mail_template('lead_assigned_to_uw', $staffemail, $staffidx, $lead_id, $datax, $companyname);
		
		//////////////////////////////////////////////
		
		
		
		}elseif (isset($data['vtype'])&&$data['vtype']=="hot") {
		
		//echo "For hot";
		//print_r($data);exit;
		
		
		unset($data['deal_id']);
		unset($data['vtype']);
		
		// For spoc
		// Create associative array and encode to JSON
		$data['spoc_info'] = json_encode([
		'name'  => $data['spocname'],
		'email' => $data['spocemail'],
		'phone' => $data['spocphone'],
		'im'    => $data['spocim']
		]);
		
		unset($data['spocname']);
		unset($data['spocphone']);
		unset($data['spocemail']);
		unset($data['spocim']);
		
		// For customer
		// Create associative array and encode to JSON
		$data['customer_info'] = json_encode([
		'name'  => $data['customername'],
		'phone' => $data['customertollfree'],
		'email' => $data['customeremail']
		]);
		
		unset($data['customername']);
		unset($data['customertollfree']);
		unset($data['customeremail']);
	$data['target_countries']=json_encode($data['target_countries']);
	//convert data from array to json value	
	$winfo = [];
    foreach ($data['website_info'] as $key => $value) {
    $winfo[$key] = htmlspecialchars($value);
    }
    // Convert associative array to JSON
    $data['website_info'] = json_encode($winfo);
	
	//convert data from array to json value
	$ohistory = [];
    foreach ($data['old_history'] as $key => $value) {
    $ohistory[$key] = htmlspecialchars($value);
    }
    // Convert associative array to JSON
    $data['old_history'] = json_encode($ohistory);
	$data['last_status_change']= date('Y-m-d H:i:s');
		
		$this->db->where('id', $id);
        $this->db->update(db_prefix().'leads', $data);
		//echo $this->db->last_query();exit;
		$log_message=" Converted this lead to Document";
        $this->log_lead_activity($id, $log_message);
		
		
       
		
		
		
		}else{
		
		
		
		
		if (isset($data['inserttocustomer'])) {
		
		$data_clients['leadid']=$data['deal_id'];
		$data_clients['default_language']="";
		$data_clients['company']=$data['company'];
		$data_clients['phonenumber']=$data['country_code'].' '.$data['phonenumber'];
		$data_clients['website']=$data['website'];
		$data_clients['address']=$data['address'];
		$data_clients['country']=$data['country'];
		$data_clients['billing_street']=$data['address'];
		$data_clients['billing_country']=$data['country'];
		$data_clients['show_primary_contact']=0;
		$data_clients['default_currency']=0;
		$data_clients['shipping_country']=0;
        $parts = explode(" ", $data['name']);
        $firstname = ucfirst($parts[0]);
        $lastname = isset($parts[1]) ? ucfirst($parts[1]) : "";
		$data_clients['firstname']=$firstname;
		$data_clients['lastname']=$lastname;
		$data_clients['email']=$data['email'];
		$data_clients['password']=rand(100000,999999);
		$data_clients['is_primary']=1;
		} 
		$iscustomer=$data['inserttocustomer'];
		$deal_id=$data['deal_id'];
		unset($data['deal_id']);
		$log_message=" Converted this lead to ".$data['vtype'];
		unset($data['vtype']);
		unset($data['inserttocustomer']);
		
		$data['is_deal']=1; // for convert to deal
		$data['last_status_change']= date('Y-m-d H:i:s');
		$this->db->where('id', $id);
        $this->db->update(db_prefix().'leads', $data);
		//echo $this->db->last_query();exit;
		//For Lead Activity
        $this->log_lead_activity($id, $log_message);
 
		empty($data);
		
		$data=$data_clients;
		
		
		
		
		if (isset($iscustomer)) { 
		 
		// Collect dynamic fields (stored in a single column as JSON)
           
            $default_country  = get_option('customer_default_country');

            $original_lead_email = $data['email'] ? $data['email'] : "";

            if ($data['country'] == '' && $default_country != '') {
                $data['country'] = $default_country;
            }
            $data['is_primary'] = 1;
            $id                 = $this->clients_model->add($data, true); //////////////
            if ($id) {
                $primary_contact_id = get_primary_contact_user_id($id);

                if (isset($notes)) {
                    foreach ($notes as $note) {
                        $this->db->insert(db_prefix() . 'notes', [
                            'rel_id'         => $id,
                            'rel_type'       => 'customer',
                            'dateadded'      => $note['dateadded'],
                            'addedfrom'      => $note['addedfrom'],
                            'description'    => $note['description'],
                            'date_contacted' => $note['date_contacted'],
                            ]);
                    }
                }
                if (isset($consents)) {
                    foreach ($consents as $consent) {
                        unset($consent['id']);
                        unset($consent['purpose_name']);
                        $consent['lead_id']    = 0;
                        $consent['contact_id'] = $primary_contact_id;
                        $this->gdpr_model->add_consent($consent);
                    }
                }
                if (staff_cant('view', 'customers') && get_option('auto_assign_customer_admin_after_lead_convert') == 1) {
                    $this->db->insert(db_prefix() . 'customer_admins', [
                        'date_assigned' => date('Y-m-d H:i:s'),
                        'customer_id'   => $id,
                        'staff_id'      => get_staff_user_id(),
                    ]);
                }
                /*$this->leads_model->log_lead_activity($data['leadid'], 'not_lead_activity_converted', false, serialize([
                    get_staff_full_name(),
                ]));*/
                $default_status = $this->leads_model->get_status('', [
                    'isdefault' => 1,
                ]);
                $this->db->where('id', $data['leadid']);
                $this->db->update(db_prefix() . 'leads', [
                    'date_converted' => date('Y-m-d H:i:s'),
                    'status'         => $default_status[0]['id'],
                    'junk'           => 0,
                    'lost'           => 0,
                ]);
                // Check if lead email is different then client email
                $contact = $this->clients_model->get_contact(get_primary_contact_user_id($id));
                if ($contact->email != $original_lead_email) {
                    if ($original_lead_email != '') {
                        $this->leads_model->log_lead_activity($data['leadid'], 'not_lead_activity_converted_email', false, serialize([
                            $original_lead_email,
                            $contact->email,
                        ]));
                    }
                }
                if (isset($include_leads_custom_fields)) {
                    foreach ($include_leads_custom_fields as $fieldid => $value) {
                        // checked don't merge
                        if ($value == 5) {
                            continue;
                        }
                        // get the value of this leads custom fiel
                        $this->db->where('relid', $data['leadid']);
                        $this->db->where('fieldto', 'leads');
                        $this->db->where('fieldid', $fieldid);
                        $lead_custom_field_value = $this->db->get(db_prefix() . 'customfieldsvalues')->row()->value;
                        // Is custom field for contact ot customer
                        if ($value == 1 || $value == 4) {
                            if ($value == 4) {
                                $field_to = 'contacts';
                            } else {
                                $field_to = 'customers';
                            }
                            $this->db->where('id', $fieldid);
                            $field = $this->db->get(db_prefix() . 'customfields')->row();
                            // check if this field exists for custom fields
                            $this->db->where('fieldto', $field_to);
                            $this->db->where('name', $field->name);
                            $exists               = $this->db->get(db_prefix() . 'customfields')->row();
                            $copy_custom_field_id = null;
                            if ($exists) {
                                $copy_custom_field_id = $exists->id;
                            } else {
                                // there is no name with the same custom field for leads at the custom side create the custom field now
                                $this->db->insert(db_prefix() . 'customfields', [
                                    'fieldto'        => $field_to,
                                    'name'           => $field->name,
                                    'required'       => $field->required,
                                    'type'           => $field->type,
                                    'options'        => $field->options,
                                    'display_inline' => $field->display_inline,
                                    'field_order'    => $field->field_order,
                                    'slug'           => slug_it($field_to . '_' . $field->name, [
                                        'separator' => '_',
                                    ]),
                                    'active'        => $field->active,
                                    'only_admin'    => $field->only_admin,
                                    'show_on_table' => $field->show_on_table,
                                    'bs_column'     => $field->bs_column,
                                ]);
                                $new_customer_field_id = $this->db->insert_id();
                                if ($new_customer_field_id) {
                                    $copy_custom_field_id = $new_customer_field_id;
                                }
                            }
                            if ($copy_custom_field_id != null) {
                                $insert_to_custom_field_id = $id;
                                if ($value == 4) {
                                    $insert_to_custom_field_id = get_primary_contact_user_id($id);
                                }
                                $this->db->insert(db_prefix() . 'customfieldsvalues', [
                                    'relid'   => $insert_to_custom_field_id,
                                    'fieldid' => $copy_custom_field_id,
                                    'fieldto' => $field_to,
                                    'value'   => $lead_custom_field_value,
                                ]);
                            }
                        } elseif ($value == 2) {
                            if (isset($merge_db_fields)) {
                                $db_field = $merge_db_fields[$fieldid];
                                // in case user don't select anything from the db fields
                                if ($db_field == '') {
                                    continue;
                                }
                                if ($db_field == 'country' || $db_field == 'shipping_country' || $db_field == 'billing_country') {
                                    $this->db->where('iso2', $lead_custom_field_value);
                                    $this->db->or_where('short_name', $lead_custom_field_value);
                                    $this->db->or_like('long_name', $lead_custom_field_value);
                                    $country = $this->db->get(db_prefix() . 'countries')->row();
                                    if ($country) {
                                        $lead_custom_field_value = $country->country_id;
                                    } else {
                                        $lead_custom_field_value = 0;
                                    }
                                }
                                $this->db->where('userid', $id);
                                $this->db->update(db_prefix() . 'clients', [
                                    $db_field => $lead_custom_field_value,
                                ]);
                            }
                        } elseif ($value == 3) {
                            if (isset($merge_db_contact_fields)) {
                                $db_field = $merge_db_contact_fields[$fieldid];
                                if ($db_field == '') {
                                    continue;
                                }
                                $this->db->where('id', $primary_contact_id);
                                $this->db->update(db_prefix() . 'contacts', [
                                    $db_field => $lead_custom_field_value,
                                ]);
                            }
                        }
                    }
                }
                // set the lead to status client in case is not status client
                $this->db->where('isdefault', 1);
                $status_client_id = $this->db->get(db_prefix() . 'leads_status')->row()->id;
                $this->db->where('id', $data['leadid']);
                $this->db->update(db_prefix() . 'leads', [
                    'status' => $status_client_id,
                ]);

                set_alert('success', _l('lead_to_client_base_converted_success'));

                if (is_gdpr() && get_option('gdpr_after_lead_converted_delete') == '1') {
                    // When lead is deleted
                    // move all proposals to the actual customer record
                    $this->db->where('rel_id', $data['leadid']);
                    $this->db->where('rel_type', 'lead');
                    $this->db->update('proposals', [
                        'rel_id'   => $id,
                        'rel_type' => 'customer',
                    ]);

                    $this->leads_model->delete($data['leadid']);

                    $this->db->where('userid', $id);
                    $this->db->update(db_prefix() . 'clients', ['leadid' => null]);
                }

                log_activity('Created Lead Client Profile [LeadID: ' . $data['leadid'] . ', ClientID: ' . $id . ']');
                hooks()->do_action('lead_converted_to_customer', ['lead_id' => $data['leadid'], 'customer_id' => $id]);
		}
		
		
		}
		$this->log_lead_activity($id,'Leads to Deal Converted');
		}
		
		
		
        if ($this->db->affected_rows() > 0) {
		
		return "success";
		}else{
		return "failed";
		}
    }
	
	function jsonToTable($json) {
    $data = json_decode($json, true);

    if (!is_array($data)) {
        return "Invalid JSON data.";
    }

    $html = "<table border='1' cellpadding='5' cellspacing='5' width='100%'>";

    // Handle associative array
    if (array_keys($data) !== range(0, count($data) - 1)) {
        foreach ($data as $key => $value) {
            $html .= "<tr><td class='tw-font-bold' width='50%'>" . ucwords(str_replace("_"," ",htmlspecialchars($key))) . "</td><td width='50%'> :: " . htmlspecialchars($value) . "</td></tr>";
        }
    } else {
        // Handle array of associative arrays (e.g. multiple rows)
        $headersPrinted = false;
        foreach ($data as $row) {
            if (is_array($row)) {
                if (!$headersPrinted) {
                    $html .= "<tr>";
                    foreach ($row as $key => $val) {
                        $html .= "<th>" . ucwords(str_replace("_"," ",htmlspecialchars($key))) . "</th>";
                    }
                    $html .= "</tr>";
                    $headersPrinted = true;
                }

                $html .= "<tr>";
                foreach ($row as $val) {
                    $html .= "<td> :: " . htmlspecialchars($val) . "</td>";
                }
                $html .= "</tr>";
            }
        }
    }

    $html .= "</table>";
    return $html;
}
    public function get_multiple($ids = []){
            if (empty($ids)) {
                return [];
            }
            $this->db->select('leads.*, it_crm_leads_status.name as status_name, it_crm_staff.firstname, it_crm_staff.lastname,it_crm_countries.short_name as country_name ');
            $this->db->from('it_crm_leads as leads');
            $this->db->where_in('leads.id', $ids);
            $this->db->join('it_crm_leads_status', 'leads.status = it_crm_leads_status.id', 'left');
            $this->db->join('it_crm_staff', 'leads.assigned = it_crm_staff.staffid', 'left');
            $this->db->join('it_crm_countries', 'it_crm_countries.country_id = leads.country', 'left');
            $query = $this->db->get();
            return $query->result_array();
        }
    public function insert_merged_lead($data){
        $this->db->insert(db_prefix() . 'leads', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            if (!empty($data['merged_lead_ids'])) {
            $merged_ids = explode(',', $data['merged_lead_ids']);
            $mergedLeadOneLink = '<a href="' . admin_url('leads/index/' . $merged_ids[0]) . '" onclick="init_lead(' . $merged_ids[0] . ');return false;">' . $merged_ids[0] . '</a>';
            $mergedLeadTwoLink = '<a href="' . admin_url('leads/index/' . $merged_ids[1]) . '" onclick="init_lead(' . $merged_ids[1] . ');return false;">' . $merged_ids[1] . '</a>';
            // Additional data as array
            $additional_data = serialize([
                $mergedLeadOneLink,
                $mergedLeadTwoLink,
            ]);
            $description_key = 'leads_merged';
            // Log activity
            $SaveLog = $this->leads_model->log_lead_activity($insert_id, $description_key, false, $additional_data);


            $this->db->where_in('id', $merged_ids);
            $this->db->update(db_prefix() . 'leads', [
                'parent_id' => $insert_id,
                'is_child'  => 1
            ]);
        }
            return $insert_id;
        } else {
            return false;
        }
    }
    public function getLeadNameById($lead_id){
        $this->db->select('name');
        $this->db->where('id', $lead_id);
        $result = $this->db->get(db_prefix() . 'leads')->row();
        if($result){
            return $result->name;
        }else{
            return false;
        }
    }
	
	public function countEmail($email){
	 $this->db->select('count(`id`) as cnt');
	 $this->db->where('from_email', $email);
	 $this->db->where('status', 1);
	$result = $this->db->get(db_prefix() . 'emails')->row();
	$qr=$this->db->last_query();//exit;
	
	 return $result->cnt;
   
   }
}
