<?php
session_start();
if(!(isset($_SESSION['username']) && isset($_SESSION['password']))){
    include_once("../login.php");
}

if (!isset($_SESSION["type"]) || $_SESSION["type"] != "teacher") {
    http_response_code(404);
    exit("Không có quyền truy cập");
}
require_once "../connect.php";

$sql_query = "SELECT type FROM user WHERE username = ?";
if ($stmt = mysqli_prepare($conn, $sql_query)) {
    mysqli_stmt_bind_param($stmt, "s", $_GET['username']);
    if (mysqli_stmt_execute($stmt)) {
       $sql_result = $stmt ->get_result();
       $row = $sql_result -> fetch_assoc();
       if ($row['type'] != 'student') {
           http_response_code(404);
           exit("Không có quyền xóa!");
       }
    }
    else {
        exit("Lỗi kết nối!");
    }
    mysqli_stmt_close($stmt);
}
$sql_query = "DELETE FROM user WHERE username = ?";
if ($stmt = mysqli_prepare($conn, $sql_query)) {
    mysqli_stmt_bind_param($stmt, "s", $_GET['username']);
    if (mysqli_stmt_execute($stmt)) {
       header("location: listUser.php");
    }
    else {
        exit("Lỗi kết nối!");
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
 