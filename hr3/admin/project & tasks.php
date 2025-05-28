<?php
session_start();
ob_start(); // Start output buffering
$title = 'Project & Tasks';
include_once 'admin.php'; // Assuming admin.php handles common header/sidebar
include_once '../connections.php'; // Include your PDO database connection file, now expected to set $conn_hr3 and $conn_hr4

$message = ''; // For displaying success/error messages

// --- Handle Form Submissions ---

// Handle Add New Project Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_project_form'])) {
    $projectName = $_POST['project_name'] ?? '';
    $description = $_POST['description'] ?? null;
    // $departmentId = $_POST['department_id'] ?? null; // Removed
    $status = $_POST['status'] ?? 'Active';
    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;

    if (empty($projectName)) {
        $_SESSION['message'] = "Project Name is required.";
        $_SESSION['message_type'] = "danger";
    } else {
        // Use $conn_hr3 for projects table
        if (isset($conn_hr3) && $conn_hr3 instanceof PDO) {
            $sql = "INSERT INTO hr3.projects (project_name, description, status, start_date, end_date)
                    VALUES (:project_name, :description, :status, :start_date, :end_date)";
            try {
                $stmt = $conn_hr3->prepare($sql);
                $stmt->bindParam(':project_name', $projectName);
                $stmt->bindParam(':description', $description);
                // $stmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT); // Removed
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':start_date', $startDate);
                $stmt->bindParam(':end_date', $endDate);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Project '" . htmlspecialchars($projectName) . "' added successfully!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Error adding project.";
                    $_SESSION['message_type'] = "danger";
                }
            } catch (PDOException $e) {
                $_SESSION['message'] = "Database error: " . $e->getMessage();
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "HR3 database connection not established.";
            $_SESSION['message_type'] = "danger";
        }
    }
    // Redirect to clear POST data and show message
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Add New Task Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task_form'])) {
    $projectId = $_POST['project_id'] ?? null;
    $taskName = $_POST['task_name'] ?? '';
    $description = $_POST['description'] ?? null;
    $assignedToEmployeeId = $_POST['assigned_to_employee_id'] ?? null;
    $dueDate = $_POST['due_date'] ?? null;
    $status = $_POST['status'] ?? 'To Do';
    $isBillable = isset($_POST['is_billable']) ? 1 : 0;

    if (empty($projectId) || empty($taskName)) {
        $_SESSION['message'] = "Project and Task Name are required.";
        $_SESSION['message_type'] = "danger";
    } else {
        // Use $conn_hr3 for tasks table
        if (isset($conn_hr3) && $conn_hr3 instanceof PDO) {
            $sql = "INSERT INTO hr3.tasks (project_id, task_name, description, assigned_to_employee_id, due_date, status, is_billable)
                    VALUES (:project_id, :task_name, :description, :assigned_to_employee_id, :due_date, :status, :is_billable)";
            try {
                $stmt = $conn_hr3->prepare($sql);
                $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
                $stmt->bindParam(':task_name', $taskName);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':assigned_to_employee_id', $assignedToEmployeeId, PDO::PARAM_INT);
                $stmt->bindParam(':due_date', $dueDate);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':is_billable', $isBillable, PDO::PARAM_BOOL);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Task '" . htmlspecialchars($taskName) . "' added successfully!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Error adding task.";
                    $_SESSION['message_type'] = "danger";
                }
            } catch (PDOException $e) {
                $_SESSION['message'] = "Database error: " . $e->getMessage();
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "HR3 database connection not established.";
            $_SESSION['message_type'] = "danger";
        }
    }
    // Redirect to clear POST data and show message, maintaining selected project
    header("Location: " . $_SERVER['PHP_SELF'] . "?project_id=" . urlencode($projectId));
    exit();
}

// Handle Edit Project Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_project_form'])) {
    $projectId = $_POST['edit_project_id'] ?? null;
    $projectName = $_POST['edit_project_name'] ?? '';
    $description = $_POST['edit_description'] ?? null;
    // $departmentId = $_POST['edit_department_id'] ?? null; // Removed
    $status = $_POST['edit_status'] ?? 'Active';
    $startDate = $_POST['edit_start_date'] ?? null;
    $endDate = $_POST['edit_end_date'] ?? null;

    if (empty($projectId) || empty($projectName)) {
        $_SESSION['message'] = "Project ID and Name are required for editing.";
        $_SESSION['message_type'] = "danger";
    } else {
        // Use $conn_hr3 for projects table
        if (isset($conn_hr3) && $conn_hr3 instanceof PDO) {
            $sql = "UPDATE hr3.projects SET project_name = :project_name, description = :description,
                    status = :status, start_date = :start_date,
                    end_date = :end_date
                    WHERE project_id = :project_id";
            try {
                $stmt = $conn_hr3->prepare($sql);
                $stmt->bindParam(':project_name', $projectName);
                $stmt->bindParam(':description', $description);
                // $stmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT); // Removed
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':start_date', $startDate);
                $stmt->bindParam(':end_date', $endDate);
                $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Project '" . htmlspecialchars($projectName) . "' updated successfully!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Error updating project.";
                    $_SESSION['message_type'] = "danger";
                }
            } catch (PDOException $e) {
                $_SESSION['message'] = "Database error: " . $e->getMessage();
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "HR3 database connection not established.";
            $_SESSION['message_type'] = "danger";
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Edit Task Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_task_form'])) {
    $taskId = $_POST['edit_task_id'] ?? null;
    $projectId = $_POST['edit_task_project_id'] ?? null; // Keep track of the project ID
    $taskName = $_POST['edit_task_name'] ?? '';
    $description = $_POST['edit_description'] ?? null;
    $assignedToEmployeeId = $_POST['edit_assigned_to_employee_id'] ?? null;
    $dueDate = $_POST['edit_due_date'] ?? null;
    $status = $_POST['edit_status'] ?? 'To Do';
    $isBillable = isset($_POST['edit_is_billable']) ? 1 : 0;

    if (empty($taskId) || empty($taskName) || empty($projectId)) {
        $_SESSION['message'] = "Task ID, Project ID, and Task Name are required for editing.";
        $_SESSION['message_type'] = "danger";
    } else {
        // Use $conn_hr3 for tasks table
        if (isset($conn_hr3) && $conn_hr3 instanceof PDO) {
            $sql = "UPDATE hr3.tasks SET task_name = :task_name, description = :description,
                    assigned_to_employee_id = :assigned_to_employee_id, due_date = :due_date,
                    status = :status, is_billable = :is_billable
                    WHERE task_id = :task_id";
            try {
                $stmt = $conn_hr3->prepare($sql);
                $stmt->bindParam(':task_name', $taskName);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':assigned_to_employee_id', $assignedToEmployeeId, PDO::PARAM_INT);
                $stmt->bindParam(':due_date', $dueDate);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':is_billable', $isBillable, PDO::PARAM_BOOL);
                $stmt->bindParam(':task_id', $taskId, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Task '" . htmlspecialchars($taskName) . "' updated successfully!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Error updating task.";
                    $_SESSION['message_type'] = "danger";
                }
            } catch (PDOException $e) {
                $_SESSION['message'] = "Database error: " . $e->getMessage();
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "HR3 database connection not established.";
            $_SESSION['message_type'] = "danger";
        }
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?project_id=" . urlencode($projectId));
    exit();
}

// Handle Delete Project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_project_id'])) {
    $projectIdToDelete = $_POST['delete_project_id'];

    if (empty($projectIdToDelete)) {
        $_SESSION['message'] = "Project ID is required for deletion.";
        $_SESSION['message_type'] = "danger";
    } else {
        // Use $conn_hr3 for projects and tasks tables
        if (isset($conn_hr3) && $conn_hr3 instanceof PDO) {
            try {
                $conn_hr3->beginTransaction(); // Start a transaction

                // First, delete related tasks
                $sql_delete_tasks = "DELETE FROM hr3.tasks WHERE project_id = :project_id";
                $stmt_delete_tasks = $conn_hr3->prepare($sql_delete_tasks);
                $stmt_delete_tasks->bindParam(':project_id', $projectIdToDelete, PDO::PARAM_INT);
                $stmt_delete_tasks->execute();

                // Then, delete the project
                $sql_delete_project = "DELETE FROM hr3.projects WHERE project_id = :project_id";
                $stmt_delete_project = $conn_hr3->prepare($sql_delete_project);
                $stmt_delete_project->bindParam(':project_id', $projectIdToDelete, PDO::PARAM_INT);
                if ($stmt_delete_project->execute()) {
                    $conn_hr3->commit(); // Commit transaction
                    $_SESSION['message'] = "Project and its associated tasks deleted successfully!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $conn_hr3->rollBack(); // Rollback on failure
                    $_SESSION['message'] = "Error deleting project.";
                    $_SESSION['message_type'] = "danger";
                }
            } catch (PDOException $e) {
                $conn_hr3->rollBack(); // Rollback on exception
                $_SESSION['message'] = "Database error: " . $e->getMessage();
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "HR3 database connection not established.";
            $_SESSION['message_type'] = "danger";
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle Delete Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_task_id'])) {
    $taskIdToDelete = $_POST['delete_task_id'];
    $projectIdAfterDelete = $_POST['delete_task_project_id'] ?? null; // To redirect back to the correct project

    if (empty($taskIdToDelete)) {
        $_SESSION['message'] = "Task ID is required for deletion.";
        $_SESSION['message_type'] = "danger";
    } else {
        // Use $conn_hr3 for tasks table
        if (isset($conn_hr3) && $conn_hr3 instanceof PDO) {
            $sql = "DELETE FROM hr3.tasks WHERE task_id = :task_id";
            try {
                $stmt = $conn_hr3->prepare($sql);
                $stmt->bindParam(':task_id', $taskIdToDelete, PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $_SESSION['message'] = "Task deleted successfully!";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Error deleting task.";
                    $_SESSION['message_type'] = "danger";
                }
            } catch (PDOException $e) {
                $_SESSION['message'] = "Database error: " . $e->getMessage();
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "HR3 database connection not established.";
            $_SESSION['message_type'] = "danger";
        }
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?project_id=" . urlencode($projectIdAfterDelete));
    exit();
}

// --- Data Fetching ---
$projects = [];
$tasks = [];
$selectedProjectId = '';
$employees = [];

// Ensure HR3 and HR4 connections are established before fetching data
if (isset($conn_hr3) && $conn_hr3 instanceof PDO && isset($conn_hr4) && $conn_hr4 instanceof PDO) {
    try {
        // Fetch projects from hr3 database (no department join needed)
        $sql_projects = "SELECT project_id, project_name, description, status, start_date, end_date
                         FROM hr3.projects
                         ORDER BY project_name ASC";
        $stmt_projects = $conn_hr3->query($sql_projects);
        $projects = $stmt_projects->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array

        // Determine the selected project for displaying tasks
        if (isset($_GET['project_id']) && !empty($_GET['project_id'])) {
            $selectedProjectId = $_GET['project_id'];
        } elseif (!empty($projects)) {
            $selectedProjectId = $projects[0]['project_id']; // Default to the first project if none selected
        }

        // Fetch tasks based on the selected project_id from hr3, joining with employees from hr4
        if ($selectedProjectId) {
            $sql_verify_project = "SELECT project_id FROM hr3.projects WHERE project_id = :project_id";
            $stmt_verify = $conn_hr3->prepare($sql_verify_project);
            $stmt_verify->bindParam(':project_id', $selectedProjectId, PDO::PARAM_INT);
            $stmt_verify->execute();
            $project_row = $stmt_verify->fetch(PDO::FETCH_ASSOC);

            if ($project_row) {
                $intSelectedProjectId = $project_row['project_id'];

                $sql_tasks = "SELECT t.task_id, t.task_name, t.description, t.assigned_to_employee_id,
                                CONCAT(e.first_name, ' ', e.last_name) AS assigned_to_name,
                                t.due_date, t.status, t.is_billable, t.project_id
                                FROM hr3.tasks t
                                LEFT JOIN hr4.employees e ON t.assigned_to_employee_id = e.employee_id
                                WHERE t.project_id = :project_id
                                ORDER BY t.due_date ASC";
                $stmt_tasks = $conn_hr3->prepare($sql_tasks); // Use hr3 connection for tasks
                $stmt_tasks->bindParam(':project_id', $intSelectedProjectId, PDO::PARAM_INT);
                $stmt_tasks->execute();
                $tasks = $stmt_tasks->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $selectedProjectId = ''; // Project not found
                $tasks = [];
            }
        }

        // Fetch employees for the task modal dropdown from hr4
        $sql_employees = "SELECT employee_id, CONCAT(first_name, ' ', last_name) AS full_name FROM hr4.employees ORDER BY full_name ASC";
        $stmt_employees = $conn_hr4->query($sql_employees);
        $employees = $stmt_employees->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $message = "Error fetching data: " . $e->getMessage();
        error_log("Database error in project & tasks.php: " . $e->getMessage());
    }
} else {
    $message = "Database connections (HR3 and HR4) not established. Please check connections.php.";
    error_log("Database connection failed in project & tasks.php (initial load)");
}

// Close connections
$conn_hr3 = null;
$conn_hr4 = null;
?>

<div class="p-2 gap-3">
    <div class="d-flex col">
        <h6 class="text-muted pe-none mb-0">
            <a class="text-decoration-none text-muted" href="#">Home</a> >
            <a class="text-decoration-none text-muted" href="#">Time Sheets</a> >
            <a class="text-decoration-none text-muted" href="#">Project & Tasks</a>
        </h6>
    </div>
    <hr>
    <div class="nav col-12 d-flex justify-content-around">
        <h3><a class="text-decoration-none" href="timesheets for approval.php">Timesheets for Approval</a></h3>
        <h3><a href="timesheets.php" class="text-decoration-none">Timesheets</a></h3>
        <h3><a href="all timesheets.php" class="text-decoration-none">All Timesheets</a></h3>
        <h3><a class="text-decoration-none" href="reports.php">Reports</a></h3>
    </div>
    <hr>

    <?php
    // Display session messages (e.g., success/error after form submission)
    if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        unset($_SESSION['message']); // Clear the message after displaying
        unset($_SESSION['message_type']);
    endif;
    ?>

    <div class="container-fluid shadow-lg col p-4">
        <div class="col d-flex flex-column p-4">
            <div class="col d-flex flex-column border border-2 border rounded-3 p-4">
                <h3 class="align-items-center text-center">Manage Hospital Projects/Programs</h3>
                <hr>
                <div>
                    <table class="table table-striped table-hover border text-center">
                        <thead>
                            <tr>
                                <th>Project ID</th>
                                <th>Project Name</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($projects)): ?>
                                <?php foreach ($projects as $proj): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($proj['project_id']) ?></td>
                                        <td><?= htmlspecialchars($proj['project_name']) ?></td>
                                        <td><?= htmlspecialchars($proj['status']) ?></td>
                                        <td><?= htmlspecialchars($proj['start_date']) ?></td>
                                        <td><?= htmlspecialchars($proj['end_date']) ?></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm edit-project-btn"
                                                    data-bs-toggle="modal" data-bs-target="#editProjectModal"
                                                    data-project-id="<?= htmlspecialchars($proj['project_id']) ?>"
                                                    data-project-name="<?= htmlspecialchars($proj['project_name']) ?>"
                                                    data-description="<?= htmlspecialchars($proj['description']) ?>"
                                                    data-status="<?= htmlspecialchars($proj['status']) ?>"
                                                    data-start-date="<?= htmlspecialchars($proj['start_date']) ?>"
                                                    data-end-date="<?= htmlspecialchars($proj['end_date']) ?>">
                                                Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-project-btn"
                                                    data-bs-toggle="modal" data-bs-target="#deleteProjectConfirmModal"
                                                    data-project-id="<?= htmlspecialchars($proj['project_id']) ?>"
                                                    data-project-name="<?= htmlspecialchars($proj['project_name']) ?>">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No projects found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProjectModal">Add New Project</button>
                </div>
                <br>
                <div>
                    <div>
                        <h4>Manage Tasks/Activities for Project:
                            <span class="d-flex">
                                <select class="form-select" name="choice" id="projectSelect" onchange="location = this.value;">
                                    <?php if (!empty($projects)): ?>
                                        <?php foreach ($projects as $proj): ?>
                                            <option value="?project_id=<?= htmlspecialchars($proj['project_id']) ?>"
                                                <?= ($selectedProjectId == $proj['project_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($proj['project_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">No Projects Available</option>
                                    <?php endif; ?>
                                </select>
                            </span>
                        </h4>
                    </div>
                    <div>
                        <table class="table table-striped table-hover border text-center" id="tasksTable">
                            <thead>
                                <tr>
                                    <th>Task ID</th>
                                    <th>Task Name</th>
                                    <th>Assigned To</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Is Billable</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($tasks)): ?>
                                    <?php foreach ($tasks as $task): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($task['task_id']) ?></td>
                                            <td><?= htmlspecialchars($task['task_name']) ?></td>
                                            <td><?= htmlspecialchars($task['assigned_to_name'] ?: 'Unassigned') ?></td>
                                            <td><?= htmlspecialchars($task['due_date']) ?></td>
                                            <td><?= htmlspecialchars($task['status']) ?></td>
                                            <td><?= $task['is_billable'] ? 'Yes' : 'No' ?></td>
                                            <td>
                                                <button class="btn btn-primary btn-sm edit-task-btn"
                                                        data-bs-toggle="modal" data-bs-target="#editTaskModal"
                                                        data-task-id="<?= htmlspecialchars($task['task_id']) ?>"
                                                        data-task-name="<?= htmlspecialchars($task['task_name']) ?>"
                                                        data-description="<?= htmlspecialchars($task['description']) ?>"
                                                        data-assigned-to-employee-id="<?= htmlspecialchars($task['assigned_to_employee_id']) ?>"
                                                        data-due-date="<?= htmlspecialchars($task['due_date']) ?>"
                                                        data-status="<?= htmlspecialchars($task['status']) ?>"
                                                        data-is-billable="<?= htmlspecialchars($task['is_billable']) ?>"
                                                        data-project-id="<?= htmlspecialchars($task['project_id']) ?>">
                                                    Edit
                                                </button>
                                                <button class="btn btn-danger btn-sm delete-task-btn"
                                                        data-bs-toggle="modal" data-bs-target="#deleteTaskConfirmModal"
                                                        data-task-id="<?= htmlspecialchars($task['task_id']) ?>"
                                                        data-task-name="<?= htmlspecialchars($task['task_name']) ?>"
                                                        data-project-id="<?= htmlspecialchars($task['project_id']) ?>">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7">No tasks found for this project or project not selected.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">Add New Task/Activity</button>
                            <button class="btn btn-secondary" id="exportTasksBtn">Export Tasks</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addProjectModal" tabindex="-1" aria-labelledby="addProjectModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProjectModalLabel">Add New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addProjectForm" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <input type="hidden" name="add_project_form" value="1">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="projectName" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="projectName" name="project_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="projectDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="projectDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="projectStatus" class="form-label">Status</label>
                        <select class="form-select" id="projectStatus" name="status" required>
                            <option value="Active">Active</option>
                            <option value="On Hold">On Hold</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="projectStartDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="projectStartDate" name="start_date">
                    </div>
                    <div class="mb-3">
                        <label for="projectEndDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="projectEndDate" name="end_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProjectForm" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <input type="hidden" name="edit_project_form" value="1">
                <input type="hidden" id="editProjectId" name="edit_project_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editProjectName" class="form-label">Project Name</label>
                        <input type="text" class="form-control" id="editProjectName" name="edit_project_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProjectDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editProjectDescription" name="edit_description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editProjectStatus" class="form-label">Status</label>
                        <select class="form-select" id="editProjectStatus" name="edit_status" required>
                            <option value="Active">Active</option>
                            <option value="On Hold">On Hold</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editProjectStartDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="editProjectStartDate" name="edit_start_date">
                    </div>
                    <div class="mb-3">
                        <label for="editProjectEndDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="editProjectEndDate" name="edit_end_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Add New Task/Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTaskForm" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <input type="hidden" name="add_task_form" value="1">
                <input type="hidden" name="project_id" value="<?= htmlspecialchars($selectedProjectId) ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="taskName" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="taskName" name="task_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="taskDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="assignedToEmployee" class="form-label">Assigned To Employee</label>
                        <select class="form-select" id="assignedToEmployee" name="assigned_to_employee_id">
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $emp): ?>
                                <option value="<?= htmlspecialchars($emp['employee_id']) ?>">
                                    <?= htmlspecialchars($emp['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="taskDueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="taskDueDate" name="due_date">
                    </div>
                    <div class="mb-3">
                        <label for="taskStatus" class="form-label">Status</label>
                        <select class="form-select" id="taskStatus" name="status" required>
                            <option value="To Do">To Do</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                            <option value="Blocked">Blocked</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="isBillable" name="is_billable">
                        <label class="form-check-label" for="isBillable">
                            Is Billable
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTaskForm" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <input type="hidden" name="edit_task_form" value="1">
                <input type="hidden" id="editTaskId" name="edit_task_id">
                <input type="hidden" id="editTaskProjectId" name="edit_task_project_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editTaskName" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="editTaskName" name="edit_task_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editTaskDescription" name="edit_description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editAssignedToEmployee" class="form-label">Assigned To Employee</label>
                        <select class="form-select" id="editAssignedToEmployee" name="edit_assigned_to_employee_id">
                            <option value="">Select Employee</option>
                            <?php foreach ($employees as $emp): ?>
                                <option value="<?= htmlspecialchars($emp['employee_id']) ?>">
                                    <?= htmlspecialchars($emp['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editTaskDueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="editTaskDueDate" name="edit_due_date">
                    </div>
                    <div class="mb-3">
                        <label for="editTaskStatus" class="form-label">Status</label>
                        <select class="form-select" id="editTaskStatus" name="edit_status" required>
                            <option value="To Do">To Do</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Done">Done</option>
                            <option value="Blocked">Blocked</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="editIsBillable" name="edit_is_billable">
                        <label class="form-check-label" for="editIsBillable">
                            Is Billable
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteProjectConfirmModal" tabindex="-1" aria-labelledby="deleteProjectConfirmModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProjectConfirmModalLabel">Confirm Project Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteProjectForm" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <input type="hidden" name="delete_project_id" id="deleteProjectIdConfirm">
                <div class="modal-body">
                    Are you sure you want to delete project "<strong id="deleteProjectNameConfirm"></strong>"?
                    <br>
                    <span class="text-danger">This will also delete all associated tasks!</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteTaskConfirmModal" tabindex="-1" aria-labelledby="deleteTaskConfirmModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTaskConfirmModalLabel">Confirm Task Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteTaskForm" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                <input type="hidden" name="delete_task_id" id="deleteTaskIdConfirm">
                <input type="hidden" name="delete_task_project_id" id="deleteTaskProjectIdConfirm">
                <div class="modal-body">
                    Are you sure you want to delete task "<strong id="deleteTaskNameConfirm"></strong>"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event listener for "Edit Project" buttons
    document.querySelectorAll('.edit-project-btn').forEach(button => {
        button.addEventListener('click', function() {
            const projectId = this.dataset.projectId;
            const projectName = this.dataset.projectName;
            const description = this.dataset.description;
            // const departmentId = this.dataset.departmentId; // Removed
            const status = this.dataset.status;
            const startDate = this.dataset.startDate;
            const endDate = this.dataset.endDate;

            document.getElementById('editProjectId').value = projectId;
            document.getElementById('editProjectName').value = projectName;
            document.getElementById('editProjectDescription').value = description;
            // document.getElementById('editProjectDepartment').value = departmentId; // Removed
            document.getElementById('editProjectStatus').value = status;
            document.getElementById('editProjectStartDate').value = startDate;
            document.getElementById('editProjectEndDate').value = endDate;
        });
    });

    // Event listener for "Delete Project" buttons
    document.querySelectorAll('.delete-project-btn').forEach(button => {
        button.addEventListener('click', function() {
            const projectId = this.dataset.projectId;
            const projectName = this.dataset.projectName;

            document.getElementById('deleteProjectIdConfirm').value = projectId;
            document.getElementById('deleteProjectNameConfirm').textContent = projectName;
        });
    });

    // Event listener for "Edit Task" buttons
    document.querySelectorAll('.edit-task-btn').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.dataset.taskId;
            const taskName = this.dataset.taskName;
            const description = this.dataset.description;
            const assignedToEmployeeId = this.dataset.assignedToEmployeeId;
            const dueDate = this.dataset.dueDate;
            const status = this.dataset.status;
            const isBillable = this.dataset.isBillable;
            const projectId = this.dataset.projectId; // Get project ID from task button

            document.getElementById('editTaskId').value = taskId;
            document.getElementById('editTaskProjectId').value = projectId; // Set project ID
            document.getElementById('editTaskName').value = taskName;
            document.getElementById('editTaskDescription').value = description;
            document.getElementById('editAssignedToEmployee').value = assignedToEmployeeId;
            document.getElementById('editTaskDueDate').value = dueDate;
            document.getElementById('editTaskStatus').value = status;
            document.getElementById('editIsBillable').checked = (isBillable == 1);
        });
    });

    //