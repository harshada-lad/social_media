<?php  
session_start();  
include "db.php";  
  
if(!isset($_SESSION['user_id'])){  
    header("Location: index.php");  
}  
  
$user_id = $_SESSION['user_id'];  
  
/* COMMENT */  
if(isset($_POST['comment'])){  
    $post_id = $_POST['post_id'];  
    $text = $_POST['text'];  
  
    mysqli_query($conn,"INSERT INTO comments(user_id,post_id,comment) VALUES('$user_id','$post_id','$text')");  
}  
  
$posts = mysqli_query($conn,"SELECT posts.*, users.name, users.profile_pic   
FROM posts   
JOIN users ON posts.user_id = users.id   
ORDER BY posts.id DESC");  
?>  
  
<!DOCTYPE html>  
<html>  
<head>  
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<title>ConnectHub</title>  
  
<style>  
*{margin:0;padding:0;font-family:sans-serif;}  
body{background:#0f172a;color:white;}  

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px;
    background:#020617;
    border-bottom:1px solid #333;
}
.container{
    width:90%;
    max-width:500px;
    margin:auto;
    padding-bottom:80px;
}
.card{
    background:#1e293b;
    border-radius:15px;
    padding:10px;
    margin-top:15px;
}
.user{
    display:flex;
    align-items:center;
    margin-bottom:8px;
}
.user img{
    width:35px;
    height:35px;
    border-radius:50%;
    margin-right:10px;
}
.post-img{  
    width:100%;  
    max-height:500px;  
    object-fit:contain;  
    border-radius:10px;  
    background:black;
} 
.img-box{position:relative;}  

.big-heart{  
    position:absolute;  
    top:50%;left:50%;  
    transform:translate(-50%,-50%) scale(0);  
    font-size:70px;  
    opacity:0;  
    transition:0.3s;  
}  
.big-heart.show{  
    transform:translate(-50%,-50%) scale(1.2);  
    opacity:1;  
}  

.actions{
    display:flex;
    justify-content:space-between;
    margin-top:10px;
}
.left span{
    margin-right:15px;
    font-size:20px;
    cursor:pointer;
}

.comment-box{  
    position:fixed;  
    bottom:-100%;  
    left:0;  
    width:100%;  
    height:70%;  
    background:#111;  
    border-radius:20px 20px 0 0;  
    transition:0.4s;  
    z-index:999;  
    padding:15px;  
    overflow-y:auto;  
}  
.comment-box.active{bottom:0;}  

.overlay{  
    position:fixed;  
    width:100%;  
    height:100%;  
    background:rgba(0,0,0,0.6);  
    display:none;  
}  
.overlay.show{display:block;}  

.comment-form{  
    position:sticky;  
    bottom:0;  
    background:#111;  
    padding:10px;  
}

.nav{  
    position:fixed;  
    bottom:0;  
    width:100%;  
    display:flex;  
    justify-content:space-around;  
    padding:12px 0;  
    background:#020617;  
    border-top:1px solid #333;  
}  
.nav a{  
    color:white;  
    text-decoration:none;  
    font-size:22px;  
}  
</style>  
</head>  
  
<body>  

<div class="header">  
<h2>ConnectHub 🚀</h2>  
<div>
<a href="profile.php">👤</a>
<a href="logout.php">🚪</a>
</div>
</div>  
  
<div class="container">  
  
<?php while($row = mysqli_fetch_assoc($posts)){   

$liked = mysqli_query($conn,"SELECT id FROM likes WHERE user_id='$user_id' AND post_id='".$row['id']."'");  
$isLiked = mysqli_num_rows($liked)>0;  

?>  
  
<div class="card">  

<div class="user">  
<?php if(!empty($row['profile_pic'])){ ?>  
<img src="uploads/<?php echo $row['profile_pic']; ?>">  
<?php } ?>  
<b><?php echo $row['name']; ?></b>  
</div>  

<!-- 🔥 FIXED IMAGE/VIDEO -->
<div class="img-box" ondblclick="likePost(<?php echo $row['id']; ?>, this)">

<?php 
if(!empty($row['image'])){

    if($row['type']=="image" || empty($row['type'])){
?>
        <img class="post-img" src="uploads/<?php echo $row['image']; ?>">
<?php
    } else {
?>
        <video class="post-img" controls>
            <source src="uploads/<?php echo $row['image']; ?>">
        </video>
<?php
    }

}
?>

<div class="big-heart">❤️</div>
</div>  

<div class="actions">  
<div class="left">  
<span onclick="likePost(<?php echo $row['id']; ?>, this)">
<?php echo $isLiked ? "❤️" : "🤍"; ?>
</span>
<span onclick="openComments(<?php echo $row['id']; ?>)">💬</span>  
<span>➤</span>  
</div>  
<div>🔖</div>  
</div>  

<div>  
<b><?php echo $row['name']; ?></b> <?php echo $row['caption']; ?>  
</div>  

<!-- TAGS -->
<div style="color:#38bdf8; font-size:14px; margin-top:5px;">
<?php echo $row['tags']; ?>
</div>
  
</div>  
  
<?php } ?>  
  
</div>  

<div class="overlay" id="overlay" onclick="closeComments()"></div>  

<div class="comment-box" id="commentBox">  
<div id="commentContent"></div>  

<form method="POST" class="comment-form">  
<input type="hidden" name="post_id" id="postId">  
<input type="text" name="text" placeholder="Add comment..." required>  
<button name="comment">Post</button>  
</form>  
</div>  

<div class="nav">  
<a href="home.php">🏠</a>  
<a href="search.php">🔍</a>  
<a href="upload.php">➕</a>  
<a href="reels.php">🎬</a>  
<a href="profile.php">👤</a>  
</div>  

<script>  
function likePost(postId, el){  
    let heart = el.closest('.card').querySelector('.big-heart');  
    heart.classList.add('show');  
    setTimeout(()=>heart.classList.remove('show'),600);  

    let icon = el.closest('.card').querySelector('.left span');
    icon.innerHTML = (icon.innerHTML=="🤍") ? "❤️" : "🤍";

    fetch("like.php?post_id="+postId);
}  

function openComments(id){  
    document.getElementById("commentBox").classList.add("active");  
    document.getElementById("overlay").classList.add("show");  
    document.getElementById("postId").value=id;  

    fetch("fetch_comments.php?post_id="+id)  
    .then(res=>res.text())  
    .then(data=>{  
        document.getElementById("commentContent").innerHTML=data;  
    });  
}  

function closeComments(){  
    document.getElementById("commentBox").classList.remove("active");  
    document.getElementById("overlay").classList.remove("show");  
}  
</script>  

</body>  
</html>