<?php
session_start();
include "db.php";

$user_id = $_SESSION['user_id'];
$post_id = $_GET['post_id'];

$check = mysqli_query($conn,"SELECT * FROM likes 
WHERE user_id='$user_id' AND post_id='$post_id'");

if(mysqli_num_rows($check)>0){
mysqli_query($conn,"DELETE FROM likes WHERE user_id='$user_id' AND post_id='$post_id'");
} else {
mysqli_query($conn,"INSERT INTO likes(user_id,post_id) VALUES('$user_id','$post_id')");
}
?>