<?php
session_start();
if(isset($_SESSION['username']) && isset($_SESSION['password'])){
    include_once("index.php");
}
require_once "../connect.php";

$sql_query = "DELETE FROM message WHERE id = ?";

if ($stmt = mysqli_prepare($conn, $sql_query)) {
    mysqli_stmt_bind_param($stmt, 'i', $_GET['messageId']);
    if (mysqli_stmt_execute($stmt)) {
    }
    else {
        echo "Lỗi kết nối!";
        exit;
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
header("location: {$_SERVER['HTTP_REFERER']}");

?>
