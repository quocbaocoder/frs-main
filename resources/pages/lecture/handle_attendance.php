<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);
    $response = [];

    if ($attendanceData) {
        try {
            $sql = "INSERT INTO tblattendance (studentRegistrationNumber, course, unit, attendanceStatus, dateMarked)  
                VALUES (:studentID, :course, :unit, :attendanceStatus, :date)";

            $stmt = $pdo->prepare($sql);

            foreach ($attendanceData as $data) {
                $studentID = $data['studentID'];
                $attendanceStatus = $data['attendanceStatus'];
                $course = $data['course'];
                $unit = $data['unit'];
                $date = date("Y-m-d");

                $stmt->execute([
                    ':studentID' => $studentID,
                    ':course' => $course,
                    ':unit' => $unit,
                    ':attendanceStatus' => $attendanceStatus,
                    ':date' => $date
                ]);
            }

            $response['status'] = 'success';
            $response['message'] = "Điểm danh đã được ghi nhận thành công cho tất cả các mục nhập.";
        } catch (PDOException $e) {
            $response['status'] = 'error';
            $response['message'] = "Lỗi thêm dữ liệu điểm danh: " . $e->getMessage();
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = "Không nhận được dữ liệu điểm danh.";
    }

    echo json_encode($response);
}
