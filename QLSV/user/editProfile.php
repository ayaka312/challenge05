<?php
session_start();
if(!(isset($_SESSION['username']) && isset($_SESSION['password']))){
    include_once("../login.php");
}
require_once "../connect.php";
$user_id = $_GET['id'];
if ($_SESSION['type'] == 'teacher') {
    $sql_query = "SELECT type FROM user where id = ?";
    if ($stmt = mysqli_prepare($conn, $sql_query)) {
        mysqli_stmt_bind_param($stmt, "s", $_GET['id']);

        if (mysqli_stmt_execute($stmt)) {
            $sql_result = $stmt-> get_result();
            $row = $sql_result->fetch_assoc();
            if ($row['type'] != 'student' && $user_id != $_SESSION['id']) {
                http_response_code(404);
                exit("Bạn không có quyền sửa thông tin");
            }
        }
        else {
            exit("Lỗi kết nối!");
        }
        mysqli_stmt_close($stmt);
    }
}  
if ($_SESSION['type'] == 'student') {
    if ($_SESSION['id'] != $user_id) {
        http_response_code(404);
        exit("Bạn không có quyền sửa thông tin");
    }
}


$username = $password = $fullname = $email = $phoneNumber = $avatar = '';
$username_err = $password_err = $fullname_err = $email_err = $phoneNumber_err = $avatar_err = '';

$sql_query = "SELECT username, password, fullname, email, phoneNumber, avatar FROM user where id = ?";
if ($stmt = mysqli_prepare($conn, $sql_query)) {
    mysqli_stmt_bind_param($stmt, "s", $param_id);
    $param_id = $_GET["id"];
   
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $username, $password,  $fullname, $email, $phoneNumber, $avatar);
        
        if (mysqli_stmt_fetch($stmt)) {
           
        }
    }
    else {
        echo "Lỗi kết nối!";
    }
    mysqli_stmt_close($stmt);
}
function check_username($username) {
    return '';
}

function check_password($password) {
    return '';
}

function check_fullname($fullname) {
    return '';
}

function check_email($email) {
    return '';
}

function check_phoneNumber($phoneNumber) {
    return '';
}

function check_avatar($avatar) {
    return '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_err = check_username($_POST['username']);
    $password_err = check_password($_POST['password']);
    $fullname_err = check_fullname($_POST['fullname']);
    $email_err = check_email($_POST["email"]);
    $phoneNumber_err = check_phoneNumber($_POST["phoneNumber"]);
    $avatar_err = check_avatar($_POST["avatar"]);

    
    
    if (empty($fullname_err) && empty($username_err) && empty($password_err) && empty($email_err) && empty($phoneNumber_err) && empty($avatar_err)) {
        $sql_query = "UPDATE user SET username = ?, password = ?,  fullname = ?,  email = ?, phoneNumber = ?, avatar = ? where id = ?";
        if ($stmt = mysqli_prepare($conn, $sql_query)) {
            if ($_SESSION['type'] == 'teacher') {
                $newFullname = $_POST['fullname'];
                $newUsername = $_POST['username'];
            }
            else {
                $newFullname = $fullname;
                $newUsername = $username;
            }
            mysqli_stmt_bind_param($stmt, "sssssss", $newUsername,  $_POST["password"], $newFullname,  $_POST["email"], $_POST["phoneNumber"], $_POST["avatar"], $_GET["id"]);
            
            $update_success = false;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                $update_success = true;
            }
            else {
                echo "Lỗi kết nối!";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Chỉnh sửa thông tin</title>
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
                <li><a href="../challenge/listChallenge.php"><?php if ($_SESSION["type"] == "teacher") echo "Thêm challenge"; else echo "Challenge" ?></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class='active'><a href="profile.php?username=<?php echo $_SESSION['username']?>"><span class="glyphicon glyphicon-user"></span> Thông tin người dùng</a></li>
                <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
            </ul>
        </div>
    </nav>
     <div class="page-header">
        <h1>Chỉnh sửa thông tin</h1>
    </div>
    <div class="container">
        <form action="" method="post">
            <?php
            if ($_SESSION['type'] == 'teacher') {
            echo "
            <div class='form-group'>
                <label>Tên đăng nhập: </label>
                <input class='form-control' type='username' name='username' value='$username'>
                <span class='help-block'><?php echo $username_err; ?></span>
            </div>
            <div class='form-group'>
                <label>Họ tên: </label>
                <input class='form-control' type='text' name='fullname' value='$fullname'>
                <span class='help-block'><?php echo $fullname_err; ?></span>
            </div>
            ";
            }
            ?>
            <div class="form-group">
                <label>Mật khẩu: </label>
                <input class="form-control" type="password" name="password" value="<?php echo $password;?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group">
                <label>Email: </label>
                <input class="form-control" type="email" name="email" value="<?php echo $email;?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Số điện thoại: </label>
                <input class="form-control" type="tel" name="phoneNumber" value="<?php echo $phoneNumber;?>" pattern="[0-9]{7,10}">
                <span class="help-block"><?php echo $phoneNumber_err; ?></span>
            </div>
            <div class="form-group">
                <label>Ảnh sản phẩm</label>
                <input required name="avatar" type="file" class="form-control" value="<?php echo $avatar; ?>">
                <span class="help-block"><?php echo $avatar_err; ?></span>
            </div>
            <button class="btn btn-success" type='submit'>Xác nhận</button>
        </form>
         <?php
            if (isset($update_success) && $update_success) {
                echo '<h2>Chỉnh sửa thành công</h2>';
            }
         ?>
    </div>

</body>
</html>