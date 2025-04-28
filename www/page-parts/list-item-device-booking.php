<?php
$loan_start = $row['loan_start'];
$loan_end = $row['loan_end'];
$device_sn = $row['device_sn'];
$DUMMY_IMG_SRC = 'https://dummyimage.com/600x350/ced4da/6c757d';
$image_path = "assets/images/devices/" . $device_sn . '.png';
$img_src = file_exists($image_path) ? $image_path : $DUMMY_IMG_SRC;
?>

<li class="card my-1" style="max-width: 540px;">
  <div class="row g-0">
    <div class="col-md-4">
      <img src="<?php echo $img_src; ?>" class="img-fluid rounded-start list-item-card-img" alt="...">
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h5 class="card-title"><?php echo $row['device_name']; ?></h5>
        <p class="card-text">Category: <?php echo $row['device_category'] ?><br>
        <small class="text-muted">From: <?php echo $loan_start; ?> To: <?php echo $loan_end; ?></small></p>
      </div>
    </div>
  </div>
</li>
