<div class="modal fade" tabindex="-1" aria-hidden="true" id='<?php echo $loanDeviceModalId ?>'>
    <div class="modal-dialog  modal-dialog-centered">
        <form action="loandevice.php?sn=<?php echo $serialNumber ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Loan Device</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Teacher ID:</label>
                        <input type="text" name="teacher_id" maxlength="6" required class="form-control" id="<?php echo $teacherInputId ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loan Start:</label>
                        <input type="date" name="loan_start" required class="form-control" id="<?php echo $loanStartInputId ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loan End:</label>
                        <input type="date" name="loan_end" required class="form-control" id="<?php echo $loanEndInputId ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Loan Device</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    !(function() {
        const modal = document.querySelector('div.modal#<?php echo $loanDeviceModalId ?>');
        const loanStartInput = modal.querySelector('input[name="loan_start"]');
        const loanEndInput = modal.querySelector('input[name="loan_end"]');

        loanStartInput.addEventListener('change', () => {
            loanEndInput.min = loanStartInput.value;
        });
    })();
</script>