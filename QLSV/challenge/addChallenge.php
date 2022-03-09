<?php
session_start();
if(!(isset($_SESSION['username']) && isset($_SESSION['password']))){
    include_once("../login.php");
}

if ($_SESSION["type"] != "teacher") {
    http_response_code(404);
    exit;
}
require_once "../connect.php";
require_once "upload_cha.php";
if (empty($upload_err) && isset($_POST["submit"])) {
    $insert_query = "INSERT INTO challenge SET teacherId = {$_SESSION["id"]}, title = ?, description = ?, filePath = ?, modified_time = now()";
    if ($stmt = mysqli_prepare($conn, $insert_query)) {
            mysqli_stmt_bind_param($stmt, "sss", $_POST['title'], $_POST["description"], $target_file);
            if (mysqli_stmt_execute($stmt)) {
                $upload_err = "Thêm thành công";
            }
            else {
                echo "Lỗi kết nối!";
                exit;
            }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Thêm challenge</title>
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
                    <li><a href="../exercise/listExercise.php"><?php if ($_SESSION["type"] == "teacher") echo "Thêm bài tập"; else echo "Danh sách bài tập" ?></a></li>
                    <li class='active'><a href="listChallenge.php"><?php if ($_SESSION["type"] == "teacher") echo "Thêm challenge"; else echo "Challenge" ?></a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../user/profile.php?username=<?php echo $_SESSION['username']?>"><span class="glyphicon glyphicon-user"></span> Thông tin người dùng</a></li>
                    <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
                </ul>
            </div>
        </nav>
        <div class="page-header">
            <h1>Thêm challenge</h1>
        </div>
        <div class='container'>
            <form action='' method='post' enctype='multipart/form-data'>
                <div class='form-group'>
                    <label for='title'>Challenge: </label>
                    <input type='text' id='title' name='title' required><br>
                </div>
                <div class='form-group'>
                    <label for='description'>Gợi ý: </label>
                    <textarea id='description' name='description' placeholder='Enter hint here' required></textarea><br>
                </div>
                <div class='form-group'>
                    <label for='fileToUpload'>Chọn từ tệp: </label>
                    <input type="file" name="fileToUpload" id="fileToUpload" required> <br>
                </div>
                <div class='form-group'>
                    <button type="submit" class="btn btn-success" value="Upload File" name="submit">Thêm challenge</button>  
                    <a class='btn btn-primary' href='listChallenge.php'>Hủy</a>
                </div>
                <span class="form-text text-muted"><?php echo $upload_err; ?></span> 
            </form>
        </div>
    </body>
</html>