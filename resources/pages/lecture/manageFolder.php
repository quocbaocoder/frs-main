<?php
require_once "../../lib/php_functions.php";
require_once "../../../database/database_connection.php";
$response = array();

if (isset($_POST['courseID']) && isset($_POST['unitID']) && isset($_POST['venueID'])) {
    $courseID = $_POST['courseID'];
    $unitID = $_POST['unitID'];
    $venueID = $_POST['venueID'];

    $sql = "SELECT registrationNumber FROM tblStudents WHERE courseCode = '$courseID'";
    $result = fetch($sql);

    if ($result) {
        $registrationNumbers = array();
        foreach ($result as $row) {
            $registrationNumbers[] = $row["registrationNumber"];
        }

        $response['status'] = 'success';
        $response['data'] = $registrationNumbers;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Không tìm thấy dữ liệu ghi nhận.';
    }

    ob_start();
    include './studentTable.php';
    $tableHTML = ob_get_clean();

    $response['html'] = $tableHTML;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Không hợp lệ.';
}


header('Content-Type: application/json');
echo json_encode($response);
