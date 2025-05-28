<?php
function getAttendanceRecords($conn, $selectedShift = '') {
    $sql = "SELECT a.id, a.created_at, a.clock_in, a.break_start, a.break_end, a.clock_out, a.shift, l.full_name
            FROM attendance a
            LEFT JOIN login l ON a.user_id = l.id";

    if (!empty($selectedShift)) {
        $sql .= " WHERE a.shift = '" . $conn->real_escape_string($selectedShift) . "'";
    }

    return $conn->query($sql);
}

function getAttendanceById($conn, $id) {
    $stmt = $conn->prepare("SELECT a.*, l.full_name, a.user_id FROM attendance a LEFT JOIN login l ON a.user_id = l.id WHERE a.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function moveAttendanceToTimesheet($conn, $record) {
    $stmt = $conn->prepare("
        INSERT INTO timesheets (employee_name, user_id, clock_in, break_start, break_end, clock_out, created_at, shift)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "sssssssi",
        $record['full_name'],
        $record['user_id'],
        $record['clock_in'],
        $record['break_start'],
        $record['break_end'],
        $record['clock_out'],
        $record['created_at'],
        $record['shift']
    );
    return $stmt->execute();
}

function deleteAttendance($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM attendance WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
?>
