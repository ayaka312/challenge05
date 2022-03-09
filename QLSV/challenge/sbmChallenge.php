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
$challenge_sql_query = "SELECT id, title, description, filePath, modified_time FROM challenge where id = ?";
if ($stmt = mysqli_prepare($conn, $challenge_sql_query)) {
      mysqli_stmt_bind_param($stmt, "i", $_GET["challengeId"]);
        if (mysqli_stmt_execute($stmt)) {
            $challenge_sql_result = $stmt->get_result();
            if (!($row = $challenge_sql_result ->fetch_assoc())) {
                exit("Không tồn tại!");
            }
        }
        else {
            exit("Lỗi kết nối!");
        }
    mysqli_stmt_close($stmt);
}

$wrong_answer = '';
if (isset($_POST['sbmChallenge'])) {
    $answer = pathinfo($row['filePath'], PATHINFO_FILENAME);
    if ($answer == $_POST['answer']) {
        header("location: {$row['filePath']}");
    }
    else {
        $wrong_answer = 'Sai!';
    }
}
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Challenge</title>
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
        <div class='page-header'>
            <h1>Gửi đáp án</h1>
        </div>
        <div class="container panel-group"> 
            <div class='panel-heading'><?= $row['title'] ?></div>
            <div class='panel-body'>Gợi ý: <?= $row['description'] ?></div>
            <form action='' method='post'>
                <div class="form-group">
                    <label for="answer">Đáp án: </label>
                    <input class='form-control' type="text" name="answer" id="answer">
                    <?php echo $wrong_answer; ?>
                </div>
                <button class='btn btn-warning' type="submit" name="sbmChallenge">Nộp</button>
            </form>     
        </div>
    </body>
</html>