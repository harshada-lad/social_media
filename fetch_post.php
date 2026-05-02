<?php
include "db.php";

$post_id = $_GET['post_id'];

$post = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT posts.*, users.name 
FROM posts 
JOIN users ON posts.user_id = users.id
WHERE posts.id='$post_id'
"));

?>

<h3><?php echo $post['name']; ?></h3>

<?php if($post['type']=="image" || empty($post['type'])){ ?>
<img src="uploads/<?php echo $post['image']; ?>" style="width:100%;border-radius:10px;">
<?php } else { ?>
<video controls style="width:100%;">
<source src="uploads/<?php echo $post['image']; ?>">
</video>
<?php } ?>

<p><b><?php echo $post['name']; ?></b> <?php echo $post['caption']; ?></p>

<!-- TAGS -->
<p style="color:#38bdf8;"><?php echo $post['tags']; ?></p>

<hr>

<h4>Comments 💬</h4>

<?php
$comments = mysqli_query($conn,"
SELECT comments.*, users.name 
FROM comments 
JOIN users ON comments.user_id = users.id
WHERE post_id='$post_id'
");

while($c = mysqli_fetch_assoc($comments)){
?>
<p><b><?php echo $c['name']; ?>:</b> <?php echo $c['comment']; ?></p>
<?php } ?>