<?php
session_start();
if(!(isset($_SESSION['username']) && isset($_SESSION['password']))){
    include_once("../login.php");
}
require_once "../connect.php";
$exercise_sql_query = "SELECT id, title, description, filePath, modified_time FROM exercise where id = ?";

if ($stmt = mysqli_prepare($conn, $exercise_sql_query)) {
      mysqli_stmt_bind_param($stmt, "i", $_GET["exerciseId"]);

        if (mysqli_stmt_execute($stmt)) {
            $exercise_sql_result = $stmt->get_result();
            if (!($exercise = $exercise_sql_result ->fetch_assoc())) {
                exit("Không tồn tại bài tập!");
            }
        }
        else {
            exit("Lỗi kết nối!");
        }
    mysqli_stmt_close($stmt);
}

$sbmexercise_sql_query = "SELECT studentId, fullname, filePath, sbm_time FROM sbmexercise s INNER JOIN user a on s.studentId = a.id WHERE exerciseId = ?";
if ($stmt = mysqli_prepare($conn, $sbmexercise_sql_query)) {
      mysqli_stmt_bind_param($stmt, "i", $_GET["exerciseId"]);
        if (mysqli_stmt_execute($stmt)) {
            $sbmexercise_sql_result = $stmt->get_result();
        }
        else {
            exit("Lỗi kết nối!");
        }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Danh sách nộp</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="../styles/mycss.css">
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
    <div class="page-header">
        <h1>Danh sách nộp</h1>
    </div>
    <div class='container'>
        <div class="panel panel-success">
            <div class="panel-heading">Bài tập: <?php echo $exercise['title']; ?></div>
            <div class='panel-body'>Gợi ý: <?php echo $exercise['description']; ?></div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">Danh sách đã nộp</div>
            <div class="panel-body">
            <?php
            while ($row = $sbmexercise_sql_result->fetch_assoc()) {
                echo "
                <div class='panel panel-info'>
                    <div class='panel-heading'>Họ tên: {$row['fullname']}</div>
                    <div class='panel-body'>Thời gian nộp: {$row['sbm_time']}</div>
                    <div class='panel-body'><a role='button' class='btn btn-warning' href='{$row['filePath']}'>File: {$row['filePath']}</a></div>
                </div>
                ";
            }
            ?>    
            </div>
           
        </div>
    </div>
</body>
</html>