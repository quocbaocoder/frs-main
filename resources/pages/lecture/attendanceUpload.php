<?php

include './includes/dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);

    if (!empty($attendanceData)) {
        foreach ($attendanceData as $data) {
            $studentID = $data['studentID'];
            $attendanceStatus = $data['attendanceStatus'];
            $course = $data['course'];
            $unit = $data['unit'];
            $date = date("Y-m-d"); 

            $sql = "INSERT INTO tblattendance(studentRegistrationNumber, course, unit, attendanceStatus, dateMarked)  
                    VALUES ('$studentID', '$course', '$unit', '$attendanceStatus', '$date')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Dữ liệu điểm danh học sinh $studentID đã thêm thành công.<br>";
            } else {
                echo "Lỗi thêm dữ liệu điểm danh: " . $conn->error . "<br>";
            }
        }
    } else {
        echo "Không có dữ liệu điểm danh.<br>";
    }
} else {
    echo "Yêu cầu không hợp lệ.<br>";
}

?>
