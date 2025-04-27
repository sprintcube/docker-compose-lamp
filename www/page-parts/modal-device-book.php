<?php
require_once './utils.php';
?>

<div class="modal fade" tabindex="-1" aria-hidden="true" id='<?php echo $bookDeviceModalId; ?>'>
    <div class="modal-dialog  modal-dialog-centered">
        <form action="bookdevice.php?sn=<?php echo $serialNumber; ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Book Device</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (is_allowed_user_role([ROLE_USER])) { ?>
                        <input type="hidden" value="<?php echo $_SESSION['username']; ?>" name="teacher_id" id="<?php echo $teacherInputId; ?>">
                    <?php } else if (is_allowed_user_role([ROLE_ADMIN, ROLE_SUPER_ADMIN])) { ?>
                        <div class="mb-3">
                            <label class="form-label">Teacher ID:</label>
                            <input type="text" name="teacher_id" maxlength="6" required class="form-control" id="<?php echo $teacherInputId; ?>">
                        </div>
                    <?php } ?>
                    <div class="mb-3">
                        <label class="form-label">Book Start:</label>
                        <input type="date" name="loan_start" required class="form-control" id="<?php echo $loanStartInputId; ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Book End:</label>
                        <input type="date" name="loan_end" required class="form-control" id="<?php echo $loanEndInputId; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Book Device</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    !(function() {
        const modal = document.querySelector('div.modal#<?php echo $bookDeviceModalId ?>');
        const bookStartInput = modal.querySelector('input[name="book_start"]');
        const bookEndInput = modal.querySelector('input[name="book_end"]');

        bookStartInput.addEventListener('change', () => {
            bookEndInput.min = bookStartInput.value;
        });
    })();
</script>