<?php
require_once './utils.php';
$loan_start = date_format(date_create($row['loan_start']), 'D, d M Y');
$loan_end = date_format(date_create($row['loan_end']), 'D, d M Y');
$device_sn = $row['device_sn'];
$teacher_id = $row['teacher_id'];
$DUMMY_IMG_SRC = 'https://dummyimage.com/600x350/ced4da/6c757d';
$image_path = "assets/images/devices/" . $device_sn . '.png';
$img_src = file_exists($image_path) ? $image_path : $DUMMY_IMG_SRC;
?>

<div class="col-lg-6 p-1">
  <div class="card ps-0">
    <div class="row">
      <div class="col-md-4">
        <img src="<?php echo $img_src; ?>" class="img-fluid rounded-start list-item-card-img" alt="...">
      </div>
      <div class="col-md-8">
        <div class="card-body">
          <h5 class="card-title"><?php echo $row['device_name']; ?></h5>
          <p class="card-text">Category: <?php echo $row['device_category'] ?><br>
          <small class="text-muted"><?php echo $loan_start; ?> &mdash; <?php echo $loan_end; ?></small></p>
          <?php if (is_allowed_user_role([ROLE_ADMIN, ROLE_SUPER_ADMIN])) { ?>
            <small><strong>Booked by:</strong> <?php echo $row['user_fullname']; ?></small>
              <form method="POST" action="service/loan-device.php">
                  <input hidden type="text" name="booking_id" value="<?php echo $row['id'];?>" />
                  <button type="submit" class="btn btn-primary">Loan device</button>
              </form>
          <?php } ?>
          <?php if (is_allowed_user_role([ROLE_USER])) {?>
              <a href="service/cancel-booking.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Cancel Booking</a>
          <?php } ?>
          <?php if (is_allowed_user_role([ROLE_ADMIN, ROLE_SUPER_ADMIN])) {?>
              <a href="service/reject-booking.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Reject Booking</a>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
