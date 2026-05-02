<?php
session_start();
include "db.php";

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];
$comment = $_POST['comment'];

mysqli_query($conn,"INSERT INTO comments(user_id,post_id,comment) 
VALUES('$user_id','$post_id','$comment')");

header("Location: home.php");
?>