<!doctype html>
<html lang="zh">
<head>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>本科教务系统</title>
    
    <script src="https://use.typekit.net/kxf0iwn.js"></script>
    <script>try{Typekit.load({ async: true });}catch(e){}</script>

    <link href="css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
if (isset($_COOKIE['uid'])) {
    if (strlen($_COOKIE['uid']) == 10) {
        header("location: student_web/student_index.php");
    } elseif (strlen($_COOKIE['uid']) == 8) {
        header("location: teacher_web/teacher_index.php");
    }
}
?>
    <!-- Navigation Menu -->
    <header style="background: white;opacity: 0.7;">
        <a class="site-logo">
            <img src="images/logo.png" alt="">
            <span style="font-size: 18px; vertical-align: middle">
                本科教务系统
            </span>
        </a>
        <nav class="site-nav">
            <ul>
                <li><a href="register.php">注册</a></li>
                <li><a onclick="alert('Copyright &copy; 2021 Alex Zhao, WHU.\nAll rights reserved.');"
                    style="cursor: pointer">关于</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Hero -->
        <section class="hero">
			<div class="poster">
				<img src="images/Big_Sur_Simple.png" alt="">
			</div>
            <form class="box" action="log_in.php" method="post">
                <h2 style="font-size: 20px">登录</h2>
                <input type="text" name="uid" placeholder="用户名" autocomplete="off" required="">
                <input type="password" name="pwd" placeholder="密码" autocomplete="off" required="">
                <input type="submit" value="确认">
            </form>

        </section>
    </main>
    
</body>
</html>