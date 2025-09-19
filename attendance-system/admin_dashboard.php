<?php
session_start();
require_once "Admin.php";

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$admin = new Admin();

if(isset($_POST['add_course'])) {
    $name = $_POST['name'];
    $year_level = $_POST['year_level'];
    $admin->addCourse($name, $year_level);
    $add_success = "Course added successfully!";
}

if(isset($_POST['edit_course'])) {
    $course_id = $_POST['course_id'];
    $name = $_POST['name'];
    $year_level = $_POST['year_level'];
    $admin->editCourse($course_id, $name, $year_level);
    $edit_success = "Course updated successfully!";
}

$attendance_records = [];
if(isset($_POST['view_attendance'])) {
    $course_id = $_POST['attendance_course_id'];
    $year_level = $_POST['attendance_year_level'];
    $attendance_records = $admin->viewAttendance($course_id, $year_level);
}

$courses = $admin->getAllCourses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-red-600 text-white p-4 flex justify-between">
        <span class="font-bold">EAC Admin Dashboard</span>
        <a href="logout.php" class="hover:underline">Logout</a>
    </nav>

    <div class="container mx-auto mt-6 p-4">

        <div class="bg-white shadow rounded p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Add New Course/Program</h2>

            <?php if(isset($add_success)): ?>
                <div class="bg-red-600 text-white p-3 rounded mb-4"><?= $add_success ?></div>
            <?php endif; ?>

            <form method="POST" class="flex gap-4">
                <input type="text" name="name" placeholder="Course Name" required class="p-2 border border-gray-300 rounded flex-1">
                <input type="number" name="year_level" placeholder="Year Level" required class="p-2 border border-gray-300 rounded w-32">
                <button type="submit" name="add_course" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">Add</button>
            </form>
        </div>

        <div class="bg-white shadow rounded p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Edit Courses/Programs</h2>

            <?php if(isset($edit_success)): ?>
                <div class="bg-red-600 text-white p-3 rounded mb-4"><?= $edit_success ?></div>
            <?php endif; ?>

            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">Course Name</th>
                        <th class="border px-4 py-2">Year Level</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($courses as $course): ?>
                        <tr>
                            <form method="POST" class="flex gap-2">
                                <td class="border px-4 py-2">
                                    <input type="text" name="name" value="<?= $course['name'] ?>" class="w-full p-1 border rounded">
                                </td>
                                <td class="border px-4 py-2">
                                    <input type="number" name="year_level" value="<?= $course['year_level'] ?>" class="w-20 p-1 border rounded">
                                </td>
                                <td class="border px-4 py-2">
                                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                    <button type="submit" name="edit_course" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                        Update
                                    </button>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="bg-white shadow rounded p-6">
            <h2 class="text-xl font-bold mb-4">Check Attendance</h2>

            <form method="POST" class="flex gap-4 mb-4">
                <select name="attendance_course_id" required class="p-2 border border-gray-300 rounded flex-1">
                    <option value="">Select Course</option>
                    <?php foreach($courses as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="attendance_year_level" placeholder="Year Level" required class="p-2 border border-gray-300 rounded w-32">
                <button type="submit" name="view_attendance" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">View</button>
            </form>

            <?php if(!empty($attendance_records)): ?>
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">Student Name</th>
                            <th class="border px-4 py-2">Date</th>
                            <th class="border px-4 py-2">Time</th>
                            <th class="border px-4 py-2">Course</th>
                            <th class="border px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($attendance_records as $r): ?>
                            <tr>
                                <td class="border px-4 py-2"><?= $r['name'] ?></td>
                                <td class="border px-4 py-2"><?= $r['date'] ?></td>
                                <td class="border px-4 py-2"><?= $r['time'] ?></td>
                                <td class="border px-4 py-2"><?= $r['course_name'] ?></td>
                                <td class="border px-4 py-2"><?= ucfirst($r['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>