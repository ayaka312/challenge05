<?php
session_start();

if(!(isset($_SESSION['username']) && isset($_SESSION['password']))){
    include_once("../login.php");
}
require_once "../connect.php";

$sql_query = "SELECT filePath FROM challenge WHERE id = ?";
if ($stmt = mysqli_prepare($conn, $sql_query)) {
    mysqli_stmt_bind_param($stmt, 'i', $_GET['challengeId']);
    if (mysqli_stmt_execute($stmt)) {
        $res = $stmt->get_result();
        $row = $res ->fetch_assoc();
        $deleteFilePath = $row['filePath'];
    }
    else {
        echo "Lỗi kết nối!";
        exit;
    }
    mysqli_stmt_close($stmt);
}

$sql_query = "DELETE FROM challenge WHERE id = ? AND teacherId = ?";

if ($stmt = mysqli_prepare($conn, $sql_query)) {
    mysqli_stmt_bind_param($stmt, 'ii', $_GET['challengeId'], $_SESSION['id']);
       if (mysqli_stmt_execute($stmt)) {
        $res = $stmt->get_result();
        unlink($deleteFilePath);
    }
    else {
        echo "Lỗi kết nối!";
        exit;
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
header('location: listChallenge.php');

?>
