document.addEventListener('DOMContentLoaded', function () {
    // --- Consolidated Global Alert Auto-Dismiss and URL Cleanup ---
    var alertBox = document.getElementById('alertBox');
    if (alertBox) {
        setTimeout(function() {
            var alertInstance = bootstrap.Alert.getOrCreateInstance(alertBox);
            if (alertInstance) {
                alertInstance.close();
            }
            // Clean URL query parameters
            if (window.history.replaceState) {
                const currentUrl = new URL(window.location);
                const paramsToClear = [
                    'added', 'edited', 'deleted', 'error', 'msg',
                    'hr1_job_added', 'offer_edited', 'applicant_edited', 'deleted_job', // recruitment.php specific
                    'employee_added', 'employee_edited', 'employee_deleted', // employee_profile_setup.php specific
                    'success_message', 'error_message' // performance_management.php & recognition.php specific
                ];
                paramsToClear.forEach(param => currentUrl.searchParams.delete(param));

                const tabParam = new URLSearchParams(window.location.search).get('tab');
                const cleanPath = window.location.protocol + "//" + window.location.host + window.location.pathname;
                let finalUrl = cleanPath;
                if (tabParam) {
                    finalUrl += '?tab=' + tabParam;
                }
                window.history.replaceState({path: finalUrl}, '', finalUrl);
            }
        }, 3000);
    }

    // --- Tab Persistence Logic (Common Function) ---
    function initializeTabPersistence(tabsContainerId, defaultTabTarget) {
        const tabsContainer = document.getElementById(tabsContainerId);
        if (!tabsContainer) return;

        const tabs = tabsContainer.querySelectorAll('button[data-bs-toggle="tab"]');
        if (tabs.length > 0) {
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function (event) {
                    const newTabId = event.target.getAttribute('data-bs-target').substring(1);
                    const url = new URL(window.location);
                    url.searchParams.set('tab', newTabId);
                    window.history.replaceState({}, '', url);
                });
            });

            const currentUrlParams = new URLSearchParams(window.location.search);
            const activeTabId = currentUrlParams.get('tab');
            let tabToActivate = null;

            if (activeTabId) {
                tabToActivate = tabsContainer.querySelector(`button[data-bs-target="#${activeTabId}"]`);
            }

            if (!tabToActivate && defaultTabTarget) {
                tabToActivate = tabsContainer.querySelector(`button[data-bs-target="${defaultTabTarget}"]`);
            }
            
            if (tabToActivate) {
                new bootstrap.Tab(tabToActivate).show();
            } else if (tabs.length > 0 && !activeTabId) { // Fallback to the first tab if no default and no URL param
                 new bootstrap.Tab(tabs[0]).show();
            }
        }
    }

    // Initialize tab persistence for different pages
    initializeTabPersistence('recruitmentTabs', '#job'); // For recruitment.php
    initializeTabPersistence('performanceTabs', '#goal_setting'); // For performance_management.php
    initializeTabPersistence('recognitionTabs', '#program'); // For recognition.php


    // --- Logic for Add Employee Modal in employee_profile_setup.php ---
    const addEmployeeModalElement = document.getElementById('addEmployeeModal');
    if (addEmployeeModalElement) {
        const employeeIdSelector = addEmployeeModalElement.querySelector('#EmployeeID_selector');
        const fullNameInput = addEmployeeModalElement.querySelector('#add_FullName');
        const genderSelect = addEmployeeModalElement.querySelector('#add_Gender');
        const positionInput = addEmployeeModalElement.querySelector('#add_Position');
        const birthdayInput = addEmployeeModalElement.querySelector('#add_Birthday');
        const appDateInput = addEmployeeModalElement.querySelector('#add_ApplicationDate');
        const docInput = addEmployeeModalElement.querySelector('#add_DocumentSubmitted');
        const skillsInput = addEmployeeModalElement.querySelector('#add_AcquiredSkillsOrQualifications');
        const completedCoursesListElement = addEmployeeModalElement.querySelector('#completed_courses_list_add');

        if (employeeIdSelector && fullNameInput && genderSelect && positionInput && completedCoursesListElement) {
            employeeIdSelector.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value !== "") {
                    fullNameInput.value = selectedOption.getAttribute('data-fullname') || '';
                    positionInput.value = selectedOption.getAttribute('data-position') || '';

                    const genderValue = selectedOption.getAttribute('data-gender');
                    let formGenderValue = '';
                    if (genderValue) {
                        if (genderValue.toLowerCase() === 'male') formGenderValue = 'Male';
                        else if (genderValue.toLowerCase() === 'female') formGenderValue = 'Female';
                        else if (genderValue.toLowerCase() === 'other') formGenderValue = 'Other';
                    }
                    genderSelect.value = formGenderValue;

                    // Update completed courses reference
                    completedCoursesListElement.innerHTML = ''; // Clear previous list
                    const selectedApplicantId = selectedOption.value;
                    
                    // Ensure completedLearningMapFromPHP is defined (it's set in a <script> tag in employee_profile_setup.php)
                    if (typeof completedLearningMapFromPHP !== 'undefined' && completedLearningMapFromPHP[selectedApplicantId] && completedLearningMapFromPHP[selectedApplicantId].length > 0) {
                        completedLearningMapFromPHP[selectedApplicantId].forEach(courseName => {
                            const listItem = document.createElement('li');
                            listItem.className = 'list-group-item py-1';
                            listItem.textContent = courseName;
                            completedCoursesListElement.appendChild(listItem);
                        });
                    } else {
                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item py-1 text-muted';
                        listItem.textContent = 'No "Complete" courses found in HR2 for this applicant.';
                        completedCoursesListElement.appendChild(listItem);
                    }

                } else {
                    // Clear fields if "-- Select Applicant --" is chosen
                    fullNameInput.value = '';
                    genderSelect.value = '';
                    positionInput.value = '';
                    if(completedCoursesListElement) completedCoursesListElement.innerHTML = '<li class="list-group-item py-1 text-muted">Select an applicant to see completed courses.</li>';
                }
            });
        }

        // Reset form when modal is shown for adding a new employee
        addEmployeeModalElement.addEventListener('show.bs.modal', function () {
            if (employeeIdSelector) employeeIdSelector.value = "";
            if (fullNameInput) fullNameInput.value = "";
            if (genderSelect) genderSelect.value = "";
            if (positionInput) positionInput.value = "";
            if (birthdayInput) birthdayInput.value = "";
            if (appDateInput) appDateInput.value = new Date().toISOString().slice(0,10); // Default to today
            if (docInput) docInput.value = ""; 
            if (skillsInput) skillsInput.value = "";
            if (completedCoursesListElement) {
                 completedCoursesListElement.innerHTML = '<li class="list-group-item py-1 text-muted">Select an applicant to see completed courses.</li>';
            }
             // Make sure auto-filled fields are also marked as readonly initially if needed, or enable them
            if (fullNameInput) fullNameInput.readOnly = true;
            if (genderSelect) genderSelect.disabled = true; // For select, use disabled
            if (positionInput) positionInput.readOnly = true;

            if (employeeIdSelector) {
                employeeIdSelector.addEventListener('change', function () {
                    if (this.value !== "") {
                        if (fullNameInput) fullNameInput.readOnly = true;
                        if (genderSelect) genderSelect.disabled = true;
                        if (positionInput) positionInput.readOnly = true;
                    } else {
                        if (fullNameInput) fullNameInput.readOnly = false;
                        if (genderSelect) genderSelect.disabled = false;
                        if (positionInput) positionInput.readOnly = false;
                    }
                });
                // Initial state based on whether a selection is made (should be none on show)
                if (fullNameInput) fullNameInput.readOnly = (employeeIdSelector.value !== "");
                if (genderSelect) genderSelect.disabled = (employeeIdSelector.value !== "");
                if (positionInput) positionInput.readOnly = (employeeIdSelector.value !== "");

            }

        });
    }


    // --- Add HR1 Job Modal Logic (Specific to recruitment.php) ---
    const addHr1JobModalElement = document.getElementById('addHr1JobModal');
    if (addHr1JobModalElement) {
        addHr1JobModalElement.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Button that triggered the modal
            const form = addHr1JobModalElement.querySelector('form');
            const hr4RequestIdInput = form.querySelector('#hr4_request_id_source_modal_input');

            // Reset form and hidden input first
            if (form) form.reset();
            if (hr4RequestIdInput) hr4RequestIdInput.value = ''; // Clear HR4 request ID

            // Set defaults for manual add
            const postingDateInput = form.querySelector('#add_hr1_postingdate');
            if(postingDateInput) postingDateInput.valueAsDate = new Date(); // Default to today
            
            const statusSelect = form.querySelector('#add_hr1_status');
            if(statusSelect) statusSelect.value = 'Open'; // Default to 'Open'

            // If triggered by 'Create HR1 Job from HR4 request' button
            if (button && button.classList.contains('create-hr1-job-from-request')) {
                addHr1JobModalElement.querySelector('.modal-title').textContent = 'Create HR1 Job Posting from HR4 Request';
                const positionName = button.getAttribute('data-position_name');
                const departmentName = button.getAttribute('data-department_name');
                const numberOfVacancies = button.getAttribute('data-number_of_vacancies');
                const hr4RequestId = button.getAttribute('data-hr4_request_id');

                form.querySelector('#add_hr1_title').value = positionName || '';
                form.querySelector('#add_hr1_department').value = departmentName || '';
                form.querySelector('#add_hr1_description').value = `Opening for: ${positionName || 'N/A'}\nDepartment: ${departmentName || 'N/A'}\nVacancies: ${numberOfVacancies || 'N/A'}\n(Source: HR4 Request ID: ${hr4RequestId || 'N/A'})`;
                form.querySelector('#add_hr1_jobtype').value = 'Full-Time'; // Default job type or fetch from request if available
                if (hr4RequestIdInput && hr4RequestId) {
                    hr4RequestIdInput.value = hr4RequestId;
                }
            } else {
                // If triggered by 'Add New HR1 Job Manually' button
                 addHr1JobModalElement.querySelector('.modal-title').textContent = 'Add New HR1 Job Posting Manually';
                 // Fields are already reset, and defaults (date, status) are set above.
            }
        });
    }

    // --- Performance Management Page Modals ---
    var editGoalKpiModal = document.getElementById('editGoalKpiModal');
    if (editGoalKpiModal) {
        editGoalKpiModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var employeeName = button.getAttribute('data-employee_name');
            var goalDescription = button.getAttribute('data-goal_description');
            var kpi = button.getAttribute('data-kpi');
            var targetDate = button.getAttribute('data-target_date');

            editGoalKpiModal.querySelector('#edit_goal_id').value = id;
            editGoalKpiModal.querySelector('#edit_employeeNameGoal').value = employeeName;
            editGoalKpiModal.querySelector('#edit_goalDescription').value = goalDescription;
            editGoalKpiModal.querySelector('#edit_kpiMetric').value = kpi;
            editGoalKpiModal.querySelector('#edit_targetDateGoal').value = targetDate;
        });
    }

    var viewAppraisalModal = document.getElementById('viewAppraisalModal');
    if (viewAppraisalModal) {
        viewAppraisalModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            viewAppraisalModal.querySelector('#view_appraisal_employeeName').textContent = button.getAttribute('data-employee_name');
            viewAppraisalModal.querySelector('#view_appraisal_reviewPeriod').textContent = button.getAttribute('data-review_period');
            viewAppraisalModal.querySelector('#view_appraisal_rating').textContent = button.getAttribute('data-performance_rating');
            viewAppraisalModal.querySelector('#view_appraisal_comments').textContent = button.getAttribute('data-comments');
        });
    }

    var editAppraisalModal = document.getElementById('editAppraisalModal');
    if (editAppraisalModal) {
        editAppraisalModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            editAppraisalModal.querySelector('#edit_appraisal_id').value = button.getAttribute('data-id');
            editAppraisalModal.querySelector('#edit_appraisal_employeeName').value = button.getAttribute('data-employee_name');
            editAppraisalModal.querySelector('#edit_appraisal_reviewPeriod').value = button.getAttribute('data-review_period');
            editAppraisalModal.querySelector('#edit_appraisal_rating').value = button.getAttribute('data-performance_rating');
            editAppraisalModal.querySelector('#edit_appraisal_comments').value = button.getAttribute('data-comments');
        });
    }
    
    // --- Recognition Page and Performance Management (Continuous Feedback) Modals ---
    // Note: viewFeedbackModal and editFeedbackModal IDs are used on both pages.
    var generalViewFeedbackModal = document.getElementById('viewFeedbackModal'); // Used by performance & recognition
    if (generalViewFeedbackModal) {
        generalViewFeedbackModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            // Check if the specific spans exist before trying to set textContent
            const employeeNameSpan = generalViewFeedbackModal.querySelector('#view_feedback_employeeName');
            const feedbackDateSpan = generalViewFeedbackModal.querySelector('#view_feedback_date');
            const feedbackTextSpan = generalViewFeedbackModal.querySelector('#view_feedback_text');

            if(employeeNameSpan) employeeNameSpan.textContent = button.getAttribute('data-employee_name');
            if(feedbackDateSpan) feedbackDateSpan.textContent = button.getAttribute('data-feedback_date');
            if(feedbackTextSpan) feedbackTextSpan.textContent = button.getAttribute('data-feedback_text');
        });
    }

    var generalEditFeedbackModal = document.getElementById('editFeedbackModal'); // Used by performance & recognition
    if (generalEditFeedbackModal) {
        generalEditFeedbackModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            generalEditFeedbackModal.querySelector('#edit_feedback_id').value = button.getAttribute('data-id');
            
            // Fields specific to performance_management.php feedback
            const employeeNameInputPerf = generalEditFeedbackModal.querySelector('#edit_feedback_employeeName'); // Performance specific ID
            if(employeeNameInputPerf) employeeNameInputPerf.value = button.getAttribute('data-employee_name');

            generalEditFeedbackModal.querySelector('#edit_feedback_date').value = button.getAttribute('data-feedback_date');
            generalEditFeedbackModal.querySelector('#edit_feedback_text').value = button.getAttribute('data-feedback_text');
            
            // Fields specific to recognition.php feedback
            const employeeIdSelectRec = generalEditFeedbackModal.querySelector('#edit_feedback_employee_id'); // Recognition specific ID
            const recognitionIdSelectRec = generalEditFeedbackModal.querySelector('#edit_recognition_id'); // Recognition specific ID
            
            if(employeeIdSelectRec) {
                employeeIdSelectRec.value = button.getAttribute('data-employee_id') || '';
            }
            if(recognitionIdSelectRec) {
                recognitionIdSelectRec.value = button.getAttribute('data-recognition_id') || '';
            }
        });
    }
    
    // --- Recognition Page Modals (Program & Recognition/Awards) ---
    const editProgramModal = document.getElementById('editProgramModal');
    if (editProgramModal) {
        editProgramModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            editProgramModal.querySelector('#edit_program_id').value = button.getAttribute('data-id');
            editProgramModal.querySelector('#edit_program_name').value = button.getAttribute('data-name');
            editProgramModal.querySelector('#edit_program_description').value = button.getAttribute('data-description');
            editProgramModal.querySelector('#edit_reward_type').value = button.getAttribute('data-reward_type');
            editProgramModal.querySelector('#edit_status').value = button.getAttribute('data-status');
            editProgramModal.querySelector('#edit_start_date').value = button.getAttribute('data-start_date');
            editProgramModal.querySelector('#edit_end_date').value = button.getAttribute('data-end_date');
            editProgramModal.querySelector('#edit_target_department').value = button.getAttribute('data-target_department');
        });
    }

    const editRecognitionModal = document.getElementById('editRecognitionModal');
    if (editRecognitionModal) {
        editRecognitionModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            editRecognitionModal.querySelector('#edit_recognition_id').value = button.getAttribute('data-id');
            editRecognitionModal.querySelector('#edit_employee_name_rec').value = button.getAttribute('data-employee_name');
            editRecognitionModal.querySelector('#edit_department_rec').value = button.getAttribute('data-department');
            editRecognitionModal.querySelector('#edit_reward_type_rec').value = button.getAttribute('data-reward_type');
            editRecognitionModal.querySelector('#edit_message_rec').value = button.getAttribute('data-message');
            const imagePath = button.getAttribute('data-image_path');
            const currentImagePathSpan = editRecognitionModal.querySelector('#current_image_path');
            if (currentImagePathSpan) {
                 currentImagePathSpan.textContent = imagePath ? imagePath.split('/').pop() : 'No image';
            }
            const imageUploadInput = editRecognitionModal.querySelector('#edit_employee_image');
            if(imageUploadInput) imageUploadInput.value = ''; // Clear file input
        });
    }

});