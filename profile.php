<?php
session_start();
include "db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: index.php");
}

$user_id = $_SESSION['user_id'];

/* UPDATE PROFILE */
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $bio = $_POST['bio'];

    if(!empty($_FILES['image']['name'])){
        $img = time().$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$img);

        mysqli_query($conn,"UPDATE users SET name='$name', bio='$bio', profile_pic='$img' WHERE id='$user_id'");
    } else {
        mysqli_query($conn,"UPDATE users SET name='$name', bio='$bio' WHERE id='$user_id'");
    }
}

$user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'"));
$posts = mysqli_query($conn,"SELECT * FROM posts WHERE user_id='$user_id'");
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profile</title>

<style>
body{
    background:#0f172a;
    color:white;
    font-family:sans-serif;
}

/* PROFILE HEADER */
.profile{
    text-align:center;
    padding:20px;
    animation:fadeIn 0.8s ease;
}

.profile img{
    width:110px;
    height:110px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:10px;
    border:3px solid #38bdf8;
}

.bio{
    font-size:14px;
    color:#ccc;
}

/* FORM */
form{
    margin:20px;
    text-align:center;
}

input, textarea{
    width:80%;
    padding:8px;
    margin:5px;
    border-radius:8px;
    border:none;
    outline:none;
}

button{
    padding:10px 15px;
    background:linear-gradient(45deg,#38bdf8,#6366f1);
    border:none;
    border-radius:8px;
    color:white;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.05);
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:6px;
    padding:10px;
}

/* MEDIA BOX */
.media{
    position:relative;
    overflow:hidden;
    border-radius:10px;
}

/* IMAGE + VIDEO */
.media img,
.media video{
    width:100%;
    height:180px;
    object-fit:contain;
    background:black;
}

/* HOVER EFFECT */
.media:hover img,
.media:hover video{
    transform:scale(1.1);
}

/* PLAY ICON */
.play-icon{
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    font-size:30px;
    color:white;
    pointer-events:none;
}

/* ANIMATION */
@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}

/* POPUP */
.post-popup{
    position:fixed;
    bottom:-100%;
    left:0;
    width:100%;
    height:80%;
    background:#111;
    border-radius:20px 20px 0 0;
    transition:0.4s;
    z-index:999;
    padding:15px;
    overflow:auto;
}

.post-popup.active{
    bottom:0;
}

.overlay{
    position:fixed;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    display:none;
}

.overlay.show{
    display:block;
}

</style>

</head>

<body>

<div class="profile">

<?php if(!empty($user['profile_pic'])){ ?>
<img src="uploads/<?php echo $user['profile_pic']; ?>">
<?php } ?>

<h2><?php echo $user['name']; ?></h2>
<p class="bio"><?php echo $user['bio']; ?></p>

</div>

<!-- EDIT FORM -->
<form method="POST" enctype="multipart/form-data">
<input type="text" name="name" value="<?php echo $user['name']; ?>" required><br>
<textarea name="bio"><?php echo $user['bio']; ?></textarea><br>
<input type="file" name="image"><br>
<button name="update">Update Profile</button>
</form>

<hr>

<!-- POSTS GRID -->
<div class="grid">
<?php while($p = mysqli_fetch_assoc($posts)){ ?>

<div class="media" onclick="openPost(<?php echo $p['id']; ?>)">

<?php 
if(!empty($p['image'])){

    if($p['type']=="image" || empty($p['type'])){
?>
        <img src="uploads/<?php echo $p['image']; ?>">
<?php
    } else {
?>
        <video muted>
            <source src="uploads/<?php echo $p['image']; ?>">
        </video>
        <div class="play-icon">▶</div>
<?php
    }

}
?>

</div>

<?php } ?>
</div>

</div>

<?php?>
</div>
<div class="overlay" id="overlay" onclick="closePost()"></div>

<div class="post-popup" id="postPopup">
    <div id="popupContent"></div>
</div>

<script>
function openPost(id){
    document.getElementById("postPopup").classList.add("active");
    document.getElementById("overlay").classList.add("show");

    fetch("fetch_post.php?post_id="+id)
    .then(res=>res.text())
    .then(data=>{
        document.getElementById("popupContent").innerHTML=data;
    });
}

function closePost(){
    document.getElementById("postPopup").classList.remove("active");
    document.getElementById("overlay").classList.remove("show");
}
</script>

</body>
</html>


</body>
</html>