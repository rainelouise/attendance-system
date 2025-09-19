<?php
require_once "User.php";

class Admin extends User {

    public function __construct() {
        parent::__construct(); 
    }

    public function addCourse($name, $year_level) {
        $stmt = $this->conn->prepare("
            INSERT INTO courses (name, year_level)
            VALUES (?, ?)
        ");
        return $stmt->execute([$name, $year_level]);
    }

    public function editCourse($course_id, $name, $year_level) {
    $stmt = $this->conn->prepare("
        UPDATE courses
        SET name = ?, year_level = ?
        WHERE id = ?
    ");
    return $stmt->execute([$name, $year_level, $course_id]);
}

    public function deleteCourse($course_id) {
        $stmt = $this->conn->prepare("
            DELETE FROM courses
            WHERE id = ?
        ");
        return $stmt->execute([$course_id]);
    }

    public function viewAttendance($course_id, $year_level) {
    $stmt = $this->conn->prepare("
        SELECT u.name, a.date, a.time, a.status, c.name as course_name
        FROM attendance a
        JOIN users u ON a.user_id = u.id
        JOIN courses c ON a.course_id = c.id
        WHERE c.id = ? AND c.year_level = ?
        ORDER BY a.date DESC, a.time DESC
    ");
    $stmt->execute([$course_id, $year_level]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getAllCourses() {
        $stmt = $this->conn->prepare("SELECT * FROM courses ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>