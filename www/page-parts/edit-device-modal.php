<div class="modal fade" tabindex="-1" aria-hidden="true" id='<?php echo $editDeviceModalId ?>'>
    <div class="modal-dialog  modal-dialog-centered">
        <form action="service/edit-device.php?sn=<?php echo $serialNumber ?>" method="post" enctype="multipart/form-data">
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
                    <div class="mb-3">
                        <labelclass="form-label">Description:</label>
                        <textarea name="description" required class="form-control"><?php echo $device_row['description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Update image:</label>
                        <input type="file" name="image" class="form-control">
                        <small>Supported formats: JPG</small>
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