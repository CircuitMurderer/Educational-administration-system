<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>用户注册</title>

    <link href="css/login_style.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="box">
    <h2>注册</h2>
    <form class="form-box" method="post" action="register_process.php">
        <div class="inputBox">
            <input type="text" name="uid" autocomplete="off" required="">
            <label>用户名</label>
        </div>
        <div class="inputBox">
            <input type="password" name="pwd" autocomplete="off" required="">
            <label>密码</label>
        </div>
        <b>个人信息：</b><br><br>
        <div class="inputBox">
            <input type="text" name="name" autocomplete="off" required="">
            <label>姓名</label>
        </div>
        <label>性别:&emsp;&emsp;
            <input type="radio" name="sex" value="male" id="" class="a-radio">
            <span class="b-radio"></span>男
        </label>
        &emsp;&emsp;&emsp;&emsp;
        <label>
            <input type="radio" name="sex" value="female" id="" class="a-radio">
            <span class="b-radio"></span>女
        </label>
        <br><br>
        <div class="inputBox">
            <input type="text" name="dept" autocomplete="off" required="">
            <label>院系</label>
        </div>
        <input type="submit" value="完成">
    </form>
</div>


</body>
</html>
