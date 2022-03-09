<?php
session_start();

require_once '../connect.php';

if ($_SESSION['username'] != $_GET['username']) {
    exit("Why you here I don't allow you!");
}
$sql_query = "SELECT fullname, email, phoneNumber, type, avatar FROM user where username = ?";
if ($stmt = mysqli_prepare($conn, $sql_query)) {
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $_GET["username"];
   
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $fullname, $email, $phonenumber, $type, $avatar);
        
        if (mysqli_stmt_fetch($stmt)) {
            
        }
    }
    else {
        echo "Lỗi kết nối!";
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Thông tin</title>
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
                <li><a href="../exercise/listExercise.php">
                    <?php if ($_SESSION['type'] == 'teacher') echo 'Thêm bài tập'; else echo 'Danh sách bài tập';?>
                    </a>
                </li>
                <li><a href="../challenge/addChallenge.php"><?php if ($_SESSION['type'] == 'teacher') echo 'Thêm challenge'; else echo 'Challenge';?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class='active'><a href="user/profile.php?php echo $_SESSION['username']?>"><span class="glyphicon glyphicon-user"></span> Thông tin người dùng</a></li>
                <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
            </ul>
        </div>
    </nav>
    <div class="page-header">
        <h1>Thông tin</h1>
    </div>
    <div class='container'>
        <div class="panel panel-success">
            <div class="panel-heading"><img width='30' height="30" src="../img/<?php echo $avatar; ?>"/>Họ tên: <?php echo $fullname; ?></div>
            <div class="panel-body">Email: <?php echo $email; ?></div>
            <div class="panel-body">Số điện thoại: <?php echo $phonenumber; ?></div>
        </div>
    </div>

    <div class="container">
        <a href="editProfile.php?id=<?= $_SESSION['id']?>" class="btn btn-primary">Chỉnh sửa thông tin</a>
        
    </div>

</body>

