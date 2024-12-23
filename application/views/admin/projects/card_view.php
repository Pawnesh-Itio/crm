<?php 
// SQL query
$query ="
SELECT 
    p.id AS project_id,
    p.name AS project_name,
    p.description AS project_description,
    p.status AS project_status,
    p.deadline AS project_deadline,
    p.date_finished AS project_date_finished,
    p.progress AS project_progress,
    COUNT(t.id) AS task_count,
    s.staffid AS staff_id,
    s.firstname AS staff_name,
    s.profile_image AS staff_image
FROM 
    it_crm_projects p
LEFT JOIN 
    it_crm_tasks t 
ON 
    t.rel_id = p.id AND t.rel_type = 'project'
LEFT JOIN 
    it_crm_project_members pm 
ON 
    pm.project_id = p.id
LEFT JOIN 
    it_crm_staff s 
ON 
    s.staffid = pm.staff_id
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
<div class="row">
    <?php foreach($projects as $d){ 
    ?>
    <div class="col-sm-4">
        <div class="p-card">    
        <!-- Header Section -->
        <div class="p-header">
            <div class="p-circle">UD</div>
            <div>
                <a href="<?php echo admin_url('projects/view/' . $d['project_id']) ?>">
                <h4 style="margin:0;"><?= $d['project_name'] ?> <span class="glyphicon glyphicon-eye-open" style="color: #3bc37b;"></span></h4>
                </a>
            </div>
        </div>

        <!-- Status -->
        <div class="p-status-box">
                <div class="p-status">
                    <?php if($d['project_status']==2){ ?>
                        Ongoing
                    <?php }else{ ?>
                        Completed
                    <?php } ?>
                </div>
                <p><strong>Due Date:</strong><?= $d['project_deadline'] ?></p>
            </div>
        <div class="p-para">
            <p class="text-muted " style="font-size: 14px;"><?= $d['project_description'] ?></p>
        </div>
        <!-- Members -->
        <div>
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
                    }else{ ?>
                    <div class="p-member" data-toggle="tooltip" title="<?=$member['staff_name'] ?>">
                        <?php echo staff_profile_image($member['staff_id'], ['staff-profile-image-small'], 'thumb'); ?>
                    </div>
                    <?php 
                    }  } ?>
            </div>
        </div>  
        <!-- Stats -->
        <div class="p-stats">
            <div>
            <span>Tasks</span>
            </div>
            <div>
            <span><?= $d['task_count'] ?></span>
            </div>
        </div>
        </div>
    </div>
    <?php } ?>
</div>