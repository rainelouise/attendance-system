<?php
require_once "user.php";

class Student extends User {

    public function __construct() {
        parent::__construct(); 
    }

    public function fileAttendance($user_id, $course_id) {
    $status = $this->checkIfLate() ? 'late' : 'present';

    $stmt = $this->conn->prepare("
        INSERT INTO attendance (user_id, course_id, date, time, status)
        VALUES (?, ?, CURDATE(), CURTIME(), ?)
    ");
    return $stmt->execute([$user_id, $course_id, $status]);
}

    public function checkAttendanceHistory($user_id) {
    $stmt = $this->conn->prepare("
        SELECT a.date, a.time, c.name as course_name, a.status
        FROM attendance a
        JOIN courses c ON a.course_id = c.id
        WHERE a.user_id = ?
        ORDER BY a.date DESC, a.time DESC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    private function checkIfLate() {
        date_default_timezone_set('Asia/Manila');
        $currentHour = date('H');
        $currentMinute = date('i');

        return ($currentHour > 8 || ($currentHour == 8 && $currentMinute > 0));
    }
}
?>