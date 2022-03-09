<?php 
session_start();
require_once "connect.php";
if(isset($_SESSION['username']) && isset($_SESSION['password'])){
    include_once("index.php");
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trang quản trị chung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles/mycss.css">
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li class='active'><a href="index.php">Trang chủ</a></li>
                <li><a href="exercise/listExercise.php">
                    <?php if ($_SESSION['type'] == 'teacher') echo 'Thêm bài tập'; else echo 'Danh sách bài tập';?>
                    </a>
                </li>
                <li><a href="challenge/listChallenge.php"><?php if ($_SESSION['type'] == 'teacher') echo 'Thêm challenge'; else echo 'Challenge';?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="user/profile.php?username=<?php echo $_SESSION['username']?>"><span class="glyphicon glyphicon-user"></span> Thông tin người dùng</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="page-header">
        <h1>Danh sách người dùng</h1>
    </div>
    <?php
    $sql_query = 'SELECT * FROM user ORDER BY type';
    if ($stmt = mysqli_prepare($conn, $sql_query)) {
         
            if (mysqli_stmt_execute($stmt)) {
                $sql_result = $stmt->get_result();
            }
            else {
                exit("Lỗi kết nối!");
            }
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
    if ($_SESSION["type"] == "teacher") {
        echo "
        <div class='container'>
            <a class='btn btn-success' href='user/addUser.php'>Thêm tài khoản</a>
        </div>
        <br>
        ";
    }
    ?>
    
    <div class="container panel-group">
    <?php
    while ($row = $sql_result -> fetch_assoc()) {
        echo "
        <div class='panel panel-success'>
            <div class='panel-heading'><img width='30' height='30' src='img/{$row['avatar']}' />Họ tên: {$row['fullname']} </div>
            <div class='panel-body'>Email: {$row['email']} </div>
            <div class='panel-body'>Số điện thoại: {$row['phoneNumber']} </div>
            <div class='panel-body'>
                <a class='btn btn-info' href='message/sendMsg.php?userId={$row['id']}#bottomPage'>Gửi tin nhắn</a>
        ";
        if ($_SESSION["type"] == "teacher") {
        echo "
            <button class='btn btn-danger' style='float:right;' onclick=\"return confirm('Bạn có muốn xóa tài khoản?')\"><a style='color:white' href='user/deleteAccount.php?username={$row['username']}'>Xóa</a></button>
            <a class='btn btn-primary' href='user/editProfile.php?id={$row['id']}' style='float:right'>Chỉnh sửa thông tin</a>
        ";
        }
        echo "
            </div>
        </div>
        ";
    }
    ?>
    </div>
</body>
</html>