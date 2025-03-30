<div class="modal fade" tabindex="-1" aria-hidden="true" id='<?php echo $editDeviceModalId ?>'>
    <div class="modal-dialog  modal-dialog-centered">
        <form action="editdevice.php?sn=<?php echo $serialNumber ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Edit Device</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name:</label>
                        <input type="text" name="name" value="<?php echo $device_row['name'] ?>" required class="form-control" id="<?php $nameInputId ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category:</label>
                        <input type="text" name="category" value="<?php echo $device_row['category'] ?>" required class="form-control" id="<?php echo $categoryInputId ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>