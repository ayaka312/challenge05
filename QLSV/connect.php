
<?php

$conn = mysqli_connect("localhost", "root", "", "qlsv");
if($conn){
    mysqli_query($conn, "SET NAMEs 'utf8'");
}else{
    echo "Kết nối thất bại!";
}

?>