<?php
include "db.php";

$post_id = $_GET['post_id'];

$q = mysqli_query($conn,"SELECT comments.*, users.name 
FROM comments 
JOIN users ON comments.user_id = users.id 
WHERE post_id='$post_id' ORDER BY id DESC");

while($row = mysqli_fetch_assoc($q)){
    echo "<div class='comment'><b>".$row['name']."</b>: ".$row['comment']."</div>";
}
?>