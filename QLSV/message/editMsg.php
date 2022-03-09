<?php
session_start();
if(!(isset($_SESSION['username']) && isset($_SESSION['password']))){
    include_once("login.php");
}
require_once "../connect.php";
$oldMessageContent = '';
$sql_query = 'SELECT content FROM message WHERE id = ?';
if ($stmt = mysqli_prepare($conn, $sql_query)) {
    mysqli_stmt_bind_param($stmt, "s", $_GET['messageId']);
    if (mysqli_stmt_execute($stmt)) {
        $oldMessageContent = $stmt ->get_result() -> fetch_assoc()['content'];
    }
    else {
        exit("Cannot execute select old contentMsg SQL query");
    }
    mysqli_stmt_close($stmt);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['messageContent']) && !empty($_POST['messageContent'])) {
    $sql_query = 'UPDATE message SET content = ? WHERE id = ?';
    if ($stmt = mysqli_prepare($conn, $sql_query)) {
        mysqli_stmt_bind_param($stmt, "si", $_POST['messageContent'], $_GET['messageId']);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: sendMsg.php?userId={$_GET['userId']}#bottomPage"); 
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
<html lang="en">
<head>
    <title>Sửa tin nhắn</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/mycss.css">
    <link rel="stylesheet" href="../styles/styles.css"><body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <ul class="nav navbar-nav">
                <li><a href="../index.php">Trang chủ</a></li>
                <li><a href="../exercise/listExercise.php"><?php if ($_SESSION["type"] == "teacher") echo "Thêm bài tập"; else echo "Danh sách bài tập" ?></a></li>
                <li><a href="../challenge/listChallenge.php"><?php if ($_SESSION["type"] == "teacher") echo "Thêm challenge"; else echo "Challenge" ?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="../user/profile.php?username=<?php echo $_SESSION['username']?>"><span class="glyphicon glyphicon-user"></span> Thông tin người dùng</a></li>
                <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
            </ul>
        </div>
    </nav>
     <div class="page-header">
        <h1>Sửa tin nhắn</h1>
    </div>
    <div class="container">
        <form action='' method='post'>
            <div class="input-group">
                <input type="text" class="newmess" class="form-control " placeholder="Gửi tin nhắn" name='messageContent' value='<?= $oldMessageContent?>'>
                <span class="input-group-btn">
                    <button class="sendmess" type="submit" class="btn btn-default">
                    Gửi
                    </button>
                </span>
             </div>  
        </form>    
    </div>

</body>
</html>