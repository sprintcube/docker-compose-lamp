<?php
$loan_start = $row['loan_start'];
$loan_end = $row['loan_end'];
$teacher_id = $row['teacher_id'];
$device_sn = $row['device_sn'];
$id = $row['id'];
?>
<li class="list-group-item list-group-item-action" aria-current="true">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1"><?php echo $device_sn ?></h5>
      <!-- <small>3 days ago</small> -->
    </div>
    <!-- <p class="mb-1">Some placeholder content in a paragraph.</p> -->
    <small>From: <?php echo $loan_start; ?></small>
    <small>To: <?php echo $loan_end; ?></small>
</li>