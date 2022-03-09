<?php
session_start();

if(!(isset($_SESSION['username']) && isset($_SESSION['password']))){
    include_once("../login.php");
}
require_once "../connect.php";

if ($_SESSION["type"] == "teacher") $sql_query = "SELECT id, title, description, filePath, modified_time FROM exercise where teacherId = ?";
else if ($_SESSION["type"] == "student") $sql_query = "SELECT id, title, description, filePath, modified_time FROM exercise";
else {
    http_response_code(404);
    exit;
}
if ($stmt = mysqli_prepare($conn, $sql_query)) {
        if ($_SESSION["type"] == "teacher") mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
        if (mysqli_stmt_execute($stmt)) {
            $exercise_sql_result = $stmt->get_result();

        }
        else {
            echo "Lỗi kết nối!";
            exit;
        }
    mysqli_stmt_close($stmt);
}

if ($_SESSION["type"] == "student") {
    $sql_query = "SELECT exerciseId FROM sbmexercise WHERE studentId = ?";
    if ($stmt = mysqli_prepare($conn, $sql_query)) {
        mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);

        if (mysqli_stmt_execute($stmt)) {
            $sbmStatus = array();
            $sbm_sql_result = $stmt->get_result();
            while ($row = $sbm_sql_result -> fetch_assoc()) {
                $sbmStatus[$row['exerciseId']] = true;
            }
            
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
<html lang="en">
<head>
  <title>Danh sách bài tập</title>
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
    
    <div class="page-header">
        <h1>Danh sách bài tập</h1>
    </div>
    
    <?php
    if ($_SESSION["type"] == "teacher") {
        echo "
        <div class='container'>
        <a class='btn btn-success' href='addExercise.php'>Thêm bài tập</a>
        </div>
        <br>
        ";
    }
    ?>
    <div class="container panel-group">
    <?php
    while ($row = $exercise_sql_result ->fetch_assoc()) {
        echo " 
        <div class='panel panel-primary'>
            <div class='panel-heading'>{$row['title']}</div>
        ";
        if ($_SESSION["type"] == "student") {
            $status = isset($sbmStatus[$row['id']]) ?'Hoàn thành':'Chưa hoàn thành';
            echo "<div class='panel-body'>Trạng thái: $status</div>";
            echo "<div class='panel-body'><a class='btn btn-warning' href='sbmexercise.php?exerciseId={$row['id']}'>Nộp</a></div>";
        }
        if ($_SESSION["type"] == 'teacher') {
            echo "
            <div class='panel-body'>
                <form class='form-inline'>
                    <a class='btn btn-info btn-inline' href='seeSubmission.php?exerciseId={$row['id']}'>Xem các bài đã nộp</a>
                    <a class='btn btn-danger btn-inline' href='deleteExercise.php?exerciseId={$row['id']}' onclick=\"return confirm('Bạn có muốn xóa bài tập?')\">Xóa</a>
                </form>
            </div>
            ";
        } 
        echo "
        </div>
        ";
    }
    ?>
    
    </div>

</body>
</html>