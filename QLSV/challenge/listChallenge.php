<?php
session_start();

if(!(isset($_SESSION['username']) && isset($_SESSION['password']))){
    include_once("../login.php");
}
require_once "../connect.php";

if ($_SESSION["type"] == "teacher") $sql_query = "SELECT id, title, description, filePath, modified_time FROM challenge where teacherId = ?";
else if ($_SESSION["type"] == "student") $sql_query = "SELECT id, title, description, filePath, modified_time FROM challenge";
else {
    http_response_code(404);
    exit;
}
if ($stmt = mysqli_prepare($conn, $sql_query)) {
        if ($_SESSION["type"] == "teacher") mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
        if (mysqli_stmt_execute($stmt)) {
            $challenge_sql_result = $stmt->get_result();
        }
        else {
            echo "Lỗi kết nối!";
            exit;
        }
    mysqli_stmt_close($stmt);
}


mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Danh sách challenge</title>
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
                <li class='active'><a href="../challenge/listChallenge.php"><?php if ($_SESSION["type"] == "teacher") echo "Thêm challenge"; else echo "Challenge" ?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="../user/profile.php?username=<?php echo $_SESSION['username']?>"><span class="glyphicon glyphicon-user"></span> Thông tin người dùng</a></li>
                <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
            </ul>
        </div>
    </nav>
    <div class="page-header">
        <h1>Danh sách challenge</h1>
    </div>
    
    <?php
    if ($_SESSION["type"] == "teacher") {
        echo "
        <div class='container'>
        <a class='btn btn-success' href='addChallenge.php'>Thêm challenge</a>
        </div>
        <br>
        ";
    }
    ?>
    <div class="container panel-group">
    <?php
    while ($row = $challenge_sql_result ->fetch_assoc()) {
        echo " 
        <div class='panel panel-primary'>
            <div class='panel-heading'>{$row['title']}</div>
             <div class='panel-body'>Gợi ý: {$row['description']}</div>
        ";
        if ($_SESSION["type"] == "student") {
            echo "<div class='panel-body'><a class='btn btn-warning' href='sbmChallenge.php?challengeId={$row['id']}'>Nộp</a></div>";
        }
        if ($_SESSION["type"] == 'teacher') {
            echo "
            <div class='panel-body'>
                <form class='form-inline'>
                    <a class='btn btn-danger btn-inline' href='deleteChallenge.php?challengeId={$row['id']}' onclick=\"return confirm('Bạn có muốn xóa?')\">Xóa</a>
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