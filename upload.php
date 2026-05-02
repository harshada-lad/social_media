<?php
session_start();
include "db.php";

if(isset($_POST['upload'])){
    $user_id = $_SESSION['user_id'];
    $caption = $_POST['caption'];
    $tags = $_POST['tags'];

    $file = $_FILES['media']['name'];
    $tmp = $_FILES['media']['tmp_name'];

    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if(in_array($ext, ['jpg','jpeg','png','gif'])){
        $type = "image";
    } else {
        $type = "video";
    }

    move_uploaded_file($tmp,"uploads/".$file);

    mysqli_query($conn,"INSERT INTO posts(user_id,caption,image,type,tags)
    VALUES('$user_id','$caption','$file','$type','$tags')");

    header("Location: home.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{margin:0;padding:0;font-family:sans-serif;}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#1d2671,#c33764);
}

/* Glass Card */
.box{
    width:90%;
    max-width:400px;
    padding:25px;
    border-radius:20px;
    background:rgba(255,255,255,0.1);
    backdrop-filter:blur(15px);
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
    animation:fadeIn 0.8s ease;
}

/* Inputs */
input{
    width:100%;
    padding:10px;
    margin-top:10px;
    border:none;
    border-radius:10px;
    outline:none;
}

/* File input */
input[type="file"]{
    background:white;
}

/* Button */
button{
    width:100%;
    padding:12px;
    margin-top:15px;
    border:none;
    border-radius:10px;
    background:linear-gradient(45deg,#ff416c,#ff4b2b);
    color:white;
    font-size:16px;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:scale(1.05);
    box-shadow:0 5px 20px rgba(255,75,43,0.5);
}

/* Title */
h2{
    text-align:center;
    margin-bottom:10px;
}

/* Animation */
@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(30px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>

</head>

<body>

<div class="box">
<h2>Upload Post 🚀</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="caption" placeholder="Write a caption...">

<input type="text" name="tags" placeholder="#travel #fun">

<input type="file" name="media" accept="image/*,video/*">

<button name="upload">Upload 🔥</button>

</form>
</div>

</body>
</html>