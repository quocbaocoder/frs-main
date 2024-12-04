<?php


if (isset($_POST["addVenue"])) {
    // Sanitize and validate inputs
    $className = htmlspecialchars(trim($_POST['className']));
    $facultyCode = htmlspecialchars(trim($_POST['faculty']));
    $currentStatus = htmlspecialchars(trim($_POST['currentStatus']));
    $capacity = filter_var($_POST['capacity'], FILTER_VALIDATE_INT);
    $classification = htmlspecialchars(trim($_POST['classification']));

    // Check for required fields
    if (!$className || !$facultyCode || !$currentStatus || !$capacity || !$classification) {
        $_SESSION['message'] = "Tất cả thông tin đều bắt buộc.";
    } else {
        $dateRegistered = date("Y-m-d");

        // Prepare database operations using PDO
        try {
            // Check if venue already exists
            $stmt = $pdo->prepare("SELECT * FROM tblvenue WHERE className = :className");
            $stmt->bindParam(':className', $className);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $_SESSION['message'] = "Địa điểm đã tồn tại";
            } else {
                // Insert the new venue
                $stmt = $pdo->prepare(
                    "INSERT INTO tblvenue (className, facultyCode, currentStatus, capacity, classification, dateCreated)
                    VALUES (:className, :facultyCode, :currentStatus, :capacity, :classification, :dateCreated)"
                );
                $stmt->bindParam(':className', $className);
                $stmt->bindParam(':facultyCode', $facultyCode);
                $stmt->bindParam(':currentStatus', $currentStatus);
                $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
                $stmt->bindParam(':classification', $classification);
                $stmt->bindParam(':dateCreated', $dateRegistered);

                if ($stmt->execute()) {
                    $_SESSION['message'] = "Đã thêm địa điểm thành công";
                } else {
                    $_SESSION['message'] = "Thêm địa điểm thất bại.";
                }
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = "Cơ sở dữ liệu lỗi: " . $e->getMessage();
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="resources/images/logo/attnlg.png" rel="icon">
    <title>Điều Khiển</title>
    <link rel="stylesheet" href="resources/assets/css/admin_styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/topbar.php' ?>
    <section class="main">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main--content">

            <div id="overlay"></div>

            <div class="rooms">
                <div class="title">
                    <h2 class="section--title">Phòng học</h2>
                    <div class="rooms--right--btns">
                        <select name="date" id="date" class="dropdown room--filter">
                            <option>Filter</option>
                            <option value="free">Trống</option>
                            <option value="scheduled">Đã có lịch</option>
                        </select>
                        <button id="addClass1" class="add show-form"><i class="ri-add-line"></i>Thêm phòng học</button>
                    </div>
                </div>
                <div class="rooms--cards">
                    <a href="#" class="room--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="resources/images/office image.jpeg" alt="">
                            </div>
                        </div>
                        <p class="free">Văn Phòng</p>
                    </a>
                    <a href="#" class="room--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="resources/images/class.jpeg" alt="">
                            </div>
                        </div>
                        <p class="free">Lớp</p>
                    </a>

                    <a href="#" class="room--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="resources/images/lecture hall.jpeg" alt="">
                            </div>
                        </div>
                        <p class="free">Sảnh chờ</p>
                    </a>

                    <a href="#" class="room--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="resources/images/computer lab.jpeg" alt="">
                            </div>
                        </div>
                        <p class="free">Phòng máy tính</p>
                    </a>
                    <a href="#" class="room--card">
                        <div class="img--box--cover">
                            <div class="img--box">
                                <img src="resources/images/laboratory.jpeg" alt="">
                            </div>
                        </div>
                        <p class="free">Phòng thí nghiệm</p>
                    </a>
                </div>
            </div>
            <?php showMessage() ?>
            <div class="table-container">
                <div class="title" id="addClass2">
                    <h2 class="section--title">Phòng dạy</h2>
                    <button class="add show-form"><i class="ri-add-line"></i>Add Class</button>
                </div>

                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Tên lớp</th>
                                <th>Lớp</th>
                                <th>Tình trạng</th>
                                <th>Sỉ số</th>
                                <th>Loại phòng</th>
                                <th>Cài đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tblvenue";
                            $stmt = $pdo->query($sql);
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if ($result) {
                                foreach ($result as $row)
                                    echo "<tr id='rowvenue{$row["Id"]}'>";
                                echo "<td>" . $row["className"] . "</td>";
                                echo "<td>" . $row["facultyCode"] . "</td>";
                                echo "<td>" . $row["currentStatus"] . "</td>";
                                echo "<td>" . $row["capacity"] . "</td>";
                                echo "<td>" . $row["classification"] . "</td>";
                                echo "<td><span><i class='ri-delete-bin-line delete' data-id='{$row["Id"]}' data-name='venue'></i></span></td>";
                                echo "</tr>";
                            } else {
                                echo "<tr><td colspan='6'>No records found</td></tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="formDiv-venue" id="addClassForm" style="display:none ">
                <form method="POST" action="" name="addVenue" enctype="multipart/form-data">
                    <div style="display:flex; justify-content:space-around;">
                        <div class="form-title">
                            <p>Thêm lớp</p>
                        </div>
                        <div>
                            <span class="close">&times;</span>
                        </div>
                    </div>
                    <input type="text" name="className" placeholder="Tên Lớp" required>
                    <select name="currentStatus" id="">
                        <option value="">--Tình trạng--</option>
                        <option value="có sẵn">Có sẵn</option>
                        <option value="đã có lịch">Đã có lịch</option>
                    </select>
                    <input type="text" name="capacity" placeholder="Sỉ số" required>
                    <select required name="classification">
                        <option value="" selected> --Chọn loại lớp học--</option>
                        <option value="P. Thí Nghiệm">Thí nghiệm</option>
                        <option value="P. Máy Tính">Phòng máy tính</option>
                        <option value="Sảnh Chờ">Sảnh chờ</option>
                        <option value="Lớp Học">Lớp</option>
                        <option value="Văn Phòng">Văn phòng</option>
                    </select>
                    <select required name="faculty">
                        <option value="" selected>Chọn khoa</option>
                        <?php
                        $facultyNames = getFacultyNames();
                        foreach ($facultyNames as $faculty) {
                            echo '<option value="' . $faculty["facultyCode"] . '">' . $faculty["facultyName"] . '</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" class="submit" value="Lưu địa điểm" name="addVenue">
                </form>
            </div>
        </div>
    </section>
    <?php js_asset(["active_link", "delete_request"]) ?>


    <script>
        const show_form = document.querySelectorAll(".show-form")
        const addClassForm = document.getElementById('addClassForm');
        const overlay = document.getElementById('overlay');
        const closeButtons = document.querySelectorAll('#addClassForm .close');
        show_form.forEach((showForm) => {
            showForm.addEventListener('click', function() {
                addClassForm.style.display = 'block';
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';

            });
        })

        closeButtons.forEach(function(closeButton) {
            closeButton.addEventListener('click', function() {
                addClassForm.style.display = 'none';
                overlay.style.display = 'none';
                document.body.style.overflow = 'auto';

            });
        });
    </script>
</body>

</html>