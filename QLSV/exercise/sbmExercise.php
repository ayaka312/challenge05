<?php
session_start();
if(!(isset($_SESSION['username']) && isset($_SESSION['password']))){
    include_once("../login.php");
}
if ($_SESSION["type"] != "student") {
    http_response_code(404);
    exit;
}
require_once "../connect.php";
$exercise_sql_query = "SELECT id, title, description, filePath, modified_time FROM exercise where id = ?";


if ($stmt = mysqli_prepare($conn, $exercise_sql_query)) {
      mysqli_stmt_bind_param($stmt, "i", $_GET["exerciseId"]);
        if (mysqli_stmt_execute($stmt)) {
            $exercise_sql_result = $stmt->get_result();
            if (!($row = $exercise_sql_result ->fetch_assoc())) {
                echo "Không tồn tại!";
                exit;
            }
        }
        else {
            echo "Lỗi kết nối!";
            exit;
        }
    mysqli_stmt_close($stmt);
}

require_once "upload.php";

if (isset($_POST["submit"])) {
    $insert_query = "INSERT INTO sbmexercise SET exerciseId = ?, studentId = ?, filePath = ?, sbm_time = now()";
    if ($stmt = mysqli_prepare($conn, $insert_query)) {
            mysqli_stmt_bind_param($stmt, "iis", $_GET["exerciseId"], $_SESSION["id"], $target_file);
            if (mysqli_stmt_execute($stmt)) {
                $upload_err = 'Nộp thành công';
            }
            else {
                exit("Lỗi kết nối!");
            }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Nộp bài tập</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel='stylesheet' href='../styles/mycss.css'>
        <link rel="stylesheet" href="../styles/styles.css">
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li><a href="../index.php">Trang chủ</a></li>
                    <li class='active'><a href="listExercise.php"><?php if ($_SESSION["type"] == "teacher") echo "Thêm bài tập"; else echo "Danh sách bài tập" ?></a></li>
                    <li><a href="../challenge/listChallenge.php"><?php if ($_SESSION["type"] == "teacher") echo "Thêm challenge"; else echo "Challenge" ?></a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../user/profile.php?username=<?php echo $_SESSION['username']?>"><span class="glyphicon glyphicon-user"></span> Thông tin người dùng</a></li>
                    <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
                </ul>
            </div>
        </nav>
        <div class='page-header'>
            <br><br>
            <h1>Nộp bài tập</h1>
        </div>
        <div class='container'>
            <div class='row'>
                <div class='col-lg-6'>
                    <div class='panel panel-primary'>
                        <div class="panel-heading"><?= $row['title'] ?></div>
                        <div class="panel-body"><i><?= $row['description'] ?></i></div>
                        <div class='panel-body'>Thời gian nộp: <?= $row['modified_time'] ?></div>
                        <div class='panel-body'><a class='btn btn-warning' href='<?= $row['filePath'] ?>'>Tình trạng</a></div>
                    </div>
                </div>
                <div class='col-lg-6'>
                    <br>
                    <form action='' method='post' enctype='multipart/form-data'>
                        <div class='form-group'>
                            <label for='fileToUpload'>Chọn từ tệp: </label>
                            <input class='form-control' type="file" name="fileToUpload" id="fileToUpload" required>
                        </div>
                        <input class='btn btn-success' type="submit" value="Nộp bài tập" name="submit">

                        <span class="help-block"><?php echo $upload_err; ?></span> 

                    </form>
        
                </div>
            </div>
           
        </div>    
    </body>
</html>