<?php

defined('BASEPATH') or exit('No direct script access allowed');

class lead_assigned_to_uw extends App_mail_template
{
    protected $for = 'leads';

    protected $staff_email;

    protected $original_password;

    protected $staffid;

    public $slug = 'lead_assigned_to_uw';

    public $rel_type = 'leads';

    public function __construct($staff_email, $staffid, $data, $dealdata)
    {



	
$table="<table border='1' cellpadding='5' cellspacing='0'>";
foreach ($dealdata as $key => $value) {
    $table.="<tr><td><strong>".ucwords(str_replace('_',' ',$key)).": </strong></td><td>$value</td></tr>";
}

$table.="</table><br><br>";

$cc="vikashg@itio.in,shivamg@itio.in";

        parent::__construct();
        $this->staff_email       = $staff_email;
        $this->staffid           = $staffid;
        $this->lead_details      = $table;
		$this->cc                = $cc;
    }

    public function build()
    {
        $this->to($this->staff_email)
        ->set_rel_id($this->staffid)
        ->set_merge_fields('staff_merge_fields', $this->staffid, $this->lead_details);
    }
}
