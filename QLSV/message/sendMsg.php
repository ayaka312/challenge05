<?php
session_start();
if(!(isset($_SESSION['username']) && isset($_SESSION['password']))){
    include_once("login.php");
}
require_once "../connect.php";

$sql_query = 'SELECT fullname FROM user WHERE id = ?';
if ($stmt = mysqli_prepare($conn, $sql_query)) {
    mysqli_stmt_bind_param($stmt, "i", $_GET['userId']);
    if (mysqli_stmt_execute($stmt)) {
        $receiverInfo = $stmt ->get_result() -> fetch_assoc();
    }
    else {
        exit("Lỗi kết nối!");
    }
    mysqli_stmt_close($stmt);
}

$sql_query = 'SELECT * FROM message WHERE (sendId = ? AND receiveId = ?) OR (sendId = ? AND receiveId = ?) ORDER BY sendTime';
if ($stmt = mysqli_prepare($conn, $sql_query)) {
    mysqli_stmt_bind_param($stmt, "iiii", $_SESSION['id'], $_GET['userId'], $_GET['userId'], $_SESSION['id']);
    if (mysqli_stmt_execute($stmt)) {
        $allMessage = $stmt ->get_result();
        
    }
    else {
        exit("Lỗi kết nối!");
    }
    mysqli_stmt_close($stmt);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['newMessage']) && !empty($_POST['messageContent'])) {
    $sql_query = 'INSERT INTO message SET sendId = ?, receiveId = ?, content = ?, sendTime = now()';
    if ($stmt = mysqli_prepare($conn, $sql_query)) {
        mysqli_stmt_bind_param($stmt, "iis", $_SESSION['id'], $_GET['userId'], $_POST['messageContent']);
        if (mysqli_stmt_execute($stmt)) {
            header('Location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
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
    <meta charset="UTF-8">
    <title>Send message</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/mycss.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
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
        <h1>Gửi tin nhắn tới: <?= $receiverInfo['fullname']?></h1>
    </div>
    
    <div class="container">
        <?php
        while ($message = $allMessage -> fetch_assoc()) {
            if ($message['sendId'] == $_SESSION['id']) {
                echo "
                <div class='media'>
                    <div class='media-body text-right'>
                        <h3 class='media-heading'>{$_SESSION['fullname']}</h3>
                        <div class='mess'>{$message['content']}</div>
                        <br>
                        <a href='editMsg.php?messageId={$message['id']}&userId={$_GET['userId']}' style='float:right'>Sửa
                        </a>
                        <a onclick=\"return confirm('Bạn có muốn xóa tin nhắn?')\" href='deleteMsg.php?messageId={$message['id']}' style='float:right'> Xóa </a>
                    </div>
                </div>
                ";
            }
            else if ($message['sendId'] == $_GET['userId']){
                echo "
                <div class='media'>
                    <div class='media-body'>
                        <h3 class='media-heading'>{$receiverInfo['fullname']}</h3>
                        <div class='mess'>{$message['content']}</div>           
                    </div>
                </div>
                ";
            }
            
        }
        ?>
       
        <div id="bottomPage" >
            <br>
            <form action='' method='post'>
                <div class="input-group">
                    <input type="text" class="newmess" class="form-control " placeholder="Gửi tin nhắn" name='messageContent' >
                    <span class="input-group-btn">
                    <button class="sendmess" type="submit" name='newMessage' class="btn btn-default">
                    Gửi
                    </button>
                </span>
                 </div>  
            </form>     
        </div>
        <br>
       
    </div>
  
</body>
</html>