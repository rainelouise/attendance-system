<?php
session_start();
require_once "Student.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}

$student = new Student();
$user_id = $_SESSION['user_id'];

if(isset($_POST['file_attendance'])) {
    $course_id = $_POST['course_id'];
    $student->fileAttendance($user_id, $course_id);
}

$attendance = $student->checkAttendanceHistory($user_id);

$conn = (new Database())->connect();
$courses = $conn->query("SELECT * FROM courses")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-red-700 text-white p-4 flex justify-between">
        <span class="font-bold">EAC Student Dashboard</span>
        <a href="logout.php" class="hover:underline">Logout</a>
    </nav>

    <div class="container mx-auto mt-6 p-4">
        <div class="bg-white shadow rounded p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">File Attendance</h2>
            <form method="POST" class="flex gap-4">
                <select name="course_id" required class="p-2 border border-gray-300 rounded flex-1">
                    <option value="">Select Course</option>
                    <?php foreach($courses as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?> (Year <?= $c['year_level'] ?>)</option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="file_attendance" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Submit</button>
            </form>
        </div>

        <div class="bg-white shadow rounded p-6">
            <h2 class="text-xl font-bold mb-4">Attendance History</h2>
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">Date</th>
                        <th class="border px-4 py-2">Time</th> 
                        <th class="border px-4 py-2">Course</th>
                        <th class="border px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($attendance as $att): ?>
                        <tr>
                            <td class="border px-4 py-2"><?= $att['date'] ?></td>
                            <td class="border px-4 py-2"><?= $att['time'] ?></td>
                            <td class="border px-4 py-2"><?= $att['course_name'] ?></td>
                            <td class="border px-4 py-2"><?= ucfirst($att['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
        </div>
    </div>
</body>
</html>