<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title>Đăng nhập</title>
			<link href="css/bootstrap-table.css" rel="stylesheet">
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
			<link rel="stylesheet" href="styles/mycss.css">
   			<link rel="stylesheet" href="styles/styles.css">
		</head>
	<body>
		<?php
		session_start();
        require_once "connect.php";
		if (isset($_POST['sbm'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];

			$sql =  $conn->prepare("SELECT id, username, password, type, fullname  FROM user WHERE username = ? AND password = ?");
			$sql->bind_param("ss", $username, $password);
			$sql->execute();
			$num_row = $sql->get_result()->fetch_assoc();
			if ($num_row > 0) {
				$_SESSION["password"] = $password;
				$_SESSION["username"] = $username;
                $_SESSION["type"] = $num_row['type'];
                $_SESSION['id'] = $num_row['id'];
                $_SESSION['fullname'] = $num_row['fullname'];
				header("location: index.php");

				
			} else {
				$err = '<div class="alert alert-danger">Tài khoản không hợp lệ !</div>';
			}
		}
		?>
		<div class="row" class="center">
			<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
				<div class="login-panel panel panel-default">
					<div class="panel-heading">Đăng nhập</div>
					<div class="panel-body">
						<?php if (isset($err)) {
							echo $err;
						} ?>
						<form role="form" method="post">
							<fieldset>
								<div class="form-group">
									<input class="form-control" placeholder="Tên đăng nhập" name="username" type="username" autofocus>
								</div>
								<div class="form-group">
									<input class="form-control" placeholder="Mật khẩu" name="password" type="password" value="">
								</div>
								<button name="sbm" type="submit" class="btn btn-primary">Đăng nhập</button>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>

</html>