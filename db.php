<?php
$conn = mysqli_connect("localhost", "root", "", "social_media");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>