<?php 
// SQL query
if (staff_cant('view', 'projects')) {
    $where_clause = " AND p.id IN (SELECT project_id FROM " . db_prefix() . "project_members WHERE staff_id = " . get_staff_user_id() . ")";
} else {
    $where_clause = ""; // No restriction
}

$query = "
SELECT 
    p.id AS project_id,
    p.name AS project_name,
    p.description AS project_description,
    p.status AS project_status,
	p.start_date AS project_start_date,
    p.deadline AS project_deadline,
    p.date_finished AS project_date_finished,
    p.progress AS project_progress,
    COUNT(t.id) AS task_count,
    s.staffid AS staff_id,
    s.firstname AS staff_name,
    s.profile_image AS staff_image
FROM 
    " . db_prefix() . "projects p
LEFT JOIN 
    " . db_prefix() . "tasks t 
ON 
    t.rel_id = p.id AND t.rel_type = 'project'
LEFT JOIN 
    " . db_prefix() . "project_members pm 
ON 
    pm.project_id = p.id
LEFT JOIN 
    " . db_prefix() . "staff s 
ON 
    s.staffid = pm.staff_id
WHERE 
    1 = 1 $where_clause
GROUP BY 
    p.id, s.staffid;
";


// Execute the query
$result = $this->db->query($query);

// Fetch the results
$data = $result->result_array();
// Simplify grouping in PHP
$projects = [];
foreach ($data as $row) {
    $projectId = $row['project_id'];

    // Initialize the project data if not already present
    if (!isset($projects[$projectId])) {
        $projects[$projectId] = [
            'project_id' => $row['project_id'],
            'project_name' => $row['project_name'],
            'project_description' => $row['project_description'],
            'project_status' => $row['project_status'],
            'project_start_date' => $row['project_start_date'],
			'project_deadline' => $row['project_deadline'],
            'project_date_finished' => $row['project_date_finished'],
            'project_progress' => $row['project_progress'],
            'task_count' => $row['task_count'],
            'project_staff' => [] // Empty array for staff
        ];
    }

    // Add staff to the project if available
    if (!empty($row['staff_id'])) {
        $projects[$projectId]['project_staff'][] = [
            'staff_id' => $row['staff_id'],
            'staff_name' => $row['staff_name'],
            'staff_image' => $row['staff_image']
        ];
    }
}

// Convert to numeric array for final output
$projects = array_values($projects);

// Output the result
// print_r($projects);
?>

<div class="row vikash">
    <?php 
    // Array of 4 colors
    $colorsProject = ['#FF5733', '#33FF57', '#3357FF', '#F0E130'];
    foreach($projects as $index => $d){ 
        $arrProjectName = explode(" ", $d['project_name']);
        $color = $colorsProject[$index % count($colorsProject)];
        $firstProjectLetter = strtoupper($arrProjectName[0][0]);
        if(sizeof($arrProjectName)>1){
        $secondProjectLetter = strtoupper($arrProjectName[1][0]);
        $finalShortName = $firstProjectLetter.$secondProjectLetter;
        }else{
            $finalShortName = $firstProjectLetter;
        }
    ?>
	<a href="<?php echo admin_url('projects/view/' . $d['project_id']) ?>">
	
    <div class="col-sm-4 ">
        <div class="card">
 <span class="text-muted toggle-menu-options main-item-options pull-right"><i class="fa-solid fa-calendar-days"></i> <?= $d['project_start_date'] ?> To <?=!empty($d['project_deadline']) ? $d['project_deadline'] : 'N/A'; ?></span>
  <span class="title" title="<?= $d['project_name']; ?>"><?= substr($d['project_name'],0,30); ?></span>
  <span class="desc"><?=!empty($d['project_description']) ? substr($d['project_description'],0,50) : '<p>N/A</p>'; ?>
  </span>
  <h5 class="text-muted"><strong>MEMBERS</strong></h5>
  <div class="p-members">
                <?php $members = $d['project_staff'];
                // Array of 4 colors
                $colors = ['#FF5733', '#33FF57', '#3357FF', '#F0E130'];
                foreach ($members as $index => $member) {
                    if(!$member['staff_image']){
                    $firstLetter = strtoupper($member['staff_name'][0]);
                    // Get the color based on the index (loop over the 4 colors)
                      $color = $colors[$index % count($colors)];
                    // Output the div for each member with the first letter and background color
                      echo '<div class="p-member" data-toggle="tooltip" title="'.$member['staff_name'].'" style="background-color: ' . $color . ';">' . $firstLetter . '</div>';
                    } else{ ?>
                    <div class="p-member" data-toggle="tooltip" title="<?=$member['staff_name'] ?>">
                        <?php echo staff_profile_image($member['staff_id'], ['staff-profile-image-small'], 'thumb'); ?>
                    </div>
                    <?php 
                    }  } ?>
            </div>
			
<?php if($d['project_status']==2){ $status="Ongoing"; $clr="#FF9900";}else{ $status="Completed"; $clr="#009933"; } ?>

	<div class="buttons">
  <span  class="button" style="background-color:<?=$clr;?> !important;">
    <?php if($status=="Completed"){ ?>
    <i class="fa-regular fa-circle-check fa-2x tw-text-white"></i>
	<?php }else{ ?>
    <i class="fa-solid fa-cog fa-spin fa-2x tw-text-white"></i>
	<?php } ?>

<div class="button-text google" ><span><?=$status;?></span></div>
                    
   
  </span>
  <span  class="button">
    <i class="tw-text-white task-circle"><?= $d['task_count']; ?></i>
    <div class="button-text apple">
      <span>Tasks</span>
    </div>
  </span>
</div>		


</div>
	 </div>
   </a>

    <?php } ?>
</div>