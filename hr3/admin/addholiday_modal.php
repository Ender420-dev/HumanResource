<?php
// addholiday_modal.php

// Ensure session is started and output buffering is on if this file is accessed directly
// In a typical setup, these would be in an includes/header file.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (ob_get_level() == 0) {
    ob_start();
}

// Include database connection if not already included in the main page
if (!isset($conn)) {
    include_once '../connections.php'; // Adjust path if necessary
}

// This file defines the modal's HTML. The actual insert logic will be in
// 'rules and config.php' since that's where this modal's form will submit.

?>
<div class="modal fade" id="addNewHolidayModal" tabindex="-1" aria-labelledby="addNewHolidayLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewHolidayLabel">Add New Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addNewHolidayForm" method="POST" action="rules and config.php"> <input type="hidden" name="action" value="add_new_holiday"> <div class="px-2 m-4">
                        <div class="mb-3">
                            <label for="holidayName" class="form-label"><h4>Holiday Name:</h4></label>
                            <input class="form-control fs-5" type="text" name="holiday_name" id="holidayName" required>
                        </div>
                        <div class="mb-3">
                            <label for="holidayDate" class="form-label"><h4>Date:</h4></label>
                            <input class="form-control fs-5" type="date" name="holiday_date" id="holidayDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="holidayType" class="form-label"><h4>Type:</h4></label>
                            <select class="form-select form-select-lg" name="holiday_type" id="holidayType" required>
                                <option value="Public">Public</option>
                                <option value="Private">Private</option>
                                <option value="Company">Company Specific</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="appliesTo" class="form-label"><h4>Applies To:</h4></label>
                            <select class="form-select form-select-lg" name="applies_to" id="appliesTo" required>
                                <option value="All Employees">All Employees</option>
                                <option value="Specific Departments">Specific Departments</option>
                                </select>
                        </div>
                        <div class="mb-4">
                            <label for="holidayDescription" class="form-label"><h4>Description:</h4></label>
                            <textarea class="form-control fs-5" name="description" id="holidayDescription" rows="3"></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-warning">Save Holiday</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addNewHolidayModal = document.getElementById('addNewHolidayModal');
    const addNewHolidayForm = document.getElementById('addNewHolidayForm');

    // Reset form when modal is hidden
    addNewHolidayModal.addEventListener('hidden.bs.modal', function () {
        addNewHolidayForm.reset();
        // Optionally pre-fill date to today if desired for holidays
        // document.getElementById('holidayDate').value = new Date().toISOString().slice(0, 10);
    });
});
</script>