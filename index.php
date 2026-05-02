<?php
session_start();
include "db.php";

$msg = "";

// REGISTER
if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $msg = "Email already exists!";
    } else {
        mysqli_query($conn, "INSERT INTO users(name,email,password) VALUES('$name','$email','$password')");
        $msg = "Registered successfully! Now login.";
    }
}

// LOGIN
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        header("Location: home.php");
    } else {
        $msg = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Cinematic Auth</title>

<style>
*{margin:0;padding:0;font-family:sans-serif;}

body{
    height:100vh;
    overflow:hidden;
    background:black;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* PARTICLES */
canvas{
    position:absolute;
    top:0;
    left:0;
}

/* BOX */
.container{
    width:400px;
    padding:30px;
    background:rgba(255,255,255,0.05);
    border-radius:20px;
    backdrop-filter:blur(20px);
    box-shadow:0 0 50px rgba(0,0,0,0.7);
    position:relative;
}

/* FORM */
form{
    display:flex;
    flex-direction:column;
    color:white;
}

h2{text-align:center;margin-bottom:20px;}

/* INPUT */
.input-box{
    position:relative;
    margin:15px 0;
}

.input-box input{
    width:100%;
    padding:10px;
    background:transparent;
    border:1px solid #555;
    border-radius:10px;
    color:white;
    outline:none;
}

.input-box label{
    position:absolute;
    top:50%;
    left:10px;
    color:#aaa;
    transform:translateY(-50%);
    transition:0.3s;
}

.input-box input:focus + label,
.input-box input:valid + label{
    top:-8px;
    font-size:12px;
    color:#00f2ff;
}

/* BUTTON */
button{
    padding:10px;
    border:none;
    border-radius:20px;
    background:#00f2ff;
    color:black;
    cursor:pointer;
    box-shadow:0 0 10px #00f2ff,0 0 40px #00f2ff;
    margin-top:10px;
}

/* SWITCH TEXT */
.switch{
    text-align:center;
    margin-top:10px;
    cursor:pointer;
    color:#ccc;
}

/* PASSWORD ICON */
.eye{
    position:absolute;
    right:10px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
}

/* MESSAGE */
.msg{
    text-align:center;
    color:yellow;
    margin-bottom:10px;
}
</style>
</head>

<body>

<canvas id="particles"></canvas>

<div class="container">

<div class="msg"><?php echo $msg; ?></div>

<!-- LOGIN -->
<form method="POST" id="loginForm">
<h2>Login</h2>

<div class="input-box">
<input type="email" name="email" required>
<label>Email</label>
</div>

<div class="input-box">
<input type="password" id="loginPass" name="password" required>
<label>Password</label>
<span class="eye" onclick="togglePass('loginPass')">👁️</span>
</div>

<button name="login">Login</button>
<div class="switch" onclick="toggle()">Don't have account? Register</div>
</form>

<!-- REGISTER -->
<form method="POST" id="registerForm" style="display:none;">
<h2>Register</h2>

<div class="input-box">
<input type="text" name="name" required>
<label>Name</label>
</div>

<div class="input-box">
<input type="email" name="email" required>
<label>Email</label>
</div>

<div class="input-box">
<input type="password" id="regPass" name="password" required>
<label>Password</label>
<span class="eye" onclick="togglePass('regPass')">👁️</span>
</div>

<button name="register">Register</button>
<div class="switch" onclick="toggle()">Already have account? Login</div>
</form>

</div>

<script>
// SWITCH FORMS
function toggle(){
    let login = document.getElementById("loginForm");
    let register = document.getElementById("registerForm");

    if(login.style.display === "none"){
        login.style.display = "flex";
        register.style.display = "none";
    } else {
        login.style.display = "none";
        register.style.display = "flex";
    }
}

// PASSWORD SHOW/HIDE
function togglePass(id){
    let x = document.getElementById(id);
    x.type = x.type === "password" ? "text" : "password";
}

// PARTICLES
let canvas = document.getElementById("particles");
let ctx = canvas.getContext("2d");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particles = [];
for(let i=0;i<60;i++){
    particles.push({
        x:Math.random()*canvas.width,
        y:Math.random()*canvas.height,
        r:Math.random()*3
    });
}

function draw(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
    ctx.fillStyle="#00f2ff";
    particles.forEach(p=>{
        ctx.beginPath();
        ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
        ctx.fill();
        p.y += 0.4;
        if(p.y > canvas.height) p.y = 0;
    });
    requestAnimationFrame(draw);
}
draw();
</script>

</body>
</html>