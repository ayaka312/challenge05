<?php
session_start();
if(!(isset($_SESSION['username']) && isset($_SESSION['password']))){
    include_once("../login.php");
}
require_once "../connect.php";
 
$username = $password = $confirm_password = $fullname= $email = $phoneNumber = $type =  $avatar = "";
$username_err = $password_err = $confirm_password_err = $fullname_err = $email_err = $phoneNumber_err = $avatar_err = "";
$reg_ok = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Nhập tên đăng nhập";
    } else{
        $sql = "SELECT id FROM user WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            $param_username = trim($_POST["username"]);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Đã tồn tại tên đăng nhập!";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Lỗi kết nối!";
            }
            mysqli_stmt_close($stmt);
        }
    }
        if(empty(trim($_POST["password"]))){
        $password_err = "Nhập mật khẩu";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Mật khẩu quá ngắn!";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Nhập lại mật khẩu";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Mật khẩu sai!";
        }
    }
    
    if(empty(trim($_POST["fullname"]))){
        $fullname_err = "Nhập họ tên";
    }
    else {
        $fullname = trim($_POST["fullname"]);
    }
    
    if(empty(trim($_POST["email"]))){
        $email_err = "Nhập email";
    }
    else {
        $email = trim($_POST["email"]);
    }
    
    if(empty(trim($_POST["phoneNumber"]))){
        $phoneNumber_err = "Nhập số điện thoại";
    }
    else {
        $phoneNumber = trim($_POST["phoneNumber"]);
    }

    if(empty(trim($_POST["avatar"]))){
        $avatar_err = "Link hoặc file ảnh";
    }
    else {
        $avatar = trim($_POST["avatar"]);
    }
    
    if (isset($_POST["type"])) $type = $_POST["type"]; 
    else $type = "student"; 

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($fullname_err) && empty($email_err) && empty($phoneNumber_err) && empty($avatar_err)){
        
        $sql_query = "INSERT INTO user (username, password, type, fullname, email, phoneNumber, avatar) VALUES (?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql_query)){
            mysqli_stmt_bind_param($stmt, "sssssss", $param_username, $param_password, $param_type, $param_fullname, $param_email, $param_phoneNumber, $param_avatar);
            $param_username = $username;
            $param_password = $password;
            $param_type = $type;
            $param_fullname = $fullname;
            $param_email = $email;
            $param_phoneNumber = $phoneNumber;
            $param_avatar = $avatar;
            if(mysqli_stmt_execute($stmt)){
                $reg_ok = 'Thành công!';
            } else{
                exit("Lỗi kết nối!");
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($conn);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thêm sinh viên</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel='stylesheet' href='../styles/mycss.css'>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li><a href="../index.php">Trang chủ</a></li>
                    <li><a href="listExercise.php"><?php if ($_SESSION["type"] == "teacher") echo "Thêm bài tập"; else echo "Danh sách bài tập" ?></a></li>
                    <li><a href="../challenge/listChallenge.php"><?php if ($_SESSION["type"] == "teacher") echo "Thêm challenge"; else echo "Challenge" ?></a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="profile.php?username=<?php echo $_SESSION['username']?>"><span class="glyphicon glyphicon-user"></span> Thông tin người dùng</a></li>
                    <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Đăng xuất</a></li>
                </ul>
            </div>
        </nav>
    
    <div class="container">
        <div class="center">
            <br>
            <br>
            <h2>Điền thông tin sinh viên </h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label>Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>    
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Nhập lại mật khẩu</label>
                    <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($fullname_err)) ? 'has-error' : ''; ?>">
                    <label>Họ tên</label>
                    <input type="text" name="fullname" class="form-control" value="<?php echo $fullname; ?>">
                    <span class="help-block"><?php echo $fullname_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($phoneNumber_err)) ? 'has-error' : ''; ?>">
                    <label>Số điện thoại</label>
                    <input type="tel" name="phoneNumber" class="form-control" value="<?php echo $phoneNumber; ?>">
                    <span class="help-block"><?php echo $phoneNumber_err; ?></span>
                </div>
                <?php
                if ($_SESSION["username"] == "admin") {
                    echo '<div class="form-group">
                             <label>Là: </label>
                             <select name="type">
                                <option value="student">Student</option>
                                <option value="teacher">Teacher</option>
                             </select>
                          </div>';
                }
                ?>
                <div class="form-group <?php echo (!empty($avatar_err)) ? 'has-error' : ''; ?>">
                    <label>Ảnh</label>
                    <input required name="avatar" type="file" class="form-control" value="<?php echo $avatar; ?>">
                    <span class="help-block"><?php echo $avatar_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Đồng ý">
                    <input type="reset" class="btn btn-default" value="Làm mới">
                </div>
                <p><?php echo $reg_ok?></p>
            </form>
        </div>   
    </div>
</body>
</html>