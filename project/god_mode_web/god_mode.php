<head>
    <title>管理员模式</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>


<body style="font-family: 'PingFang SC', sans-serif">
<?php
function pop_window($display, $locate)
{
    echo "<script>";
    echo "alert('{$display}');";
    echo "window.location.replace('{$locate}');";
    echo "</script>";
}

$linked = new mysqli("localhost:999", "root", "123456");
if ($linked->connect_error) {
    die("Connect failed:" . $linked->connect_error);
}

$linked->select_db("edu");

if (!isset($_COOKIE['uid']) or $_COOKIE['uid'] != "superuser") {
    pop_window("你不是真正的管理员！", "../index.php");
    return;
}

$rec = $linked->query("SELECT pwd FROM user WHERE uid = 'superuser'");
$row = $rec->fetch_array(MYSQLI_ASSOC);

?>

<h1>管理员模式</h1>
<form action="../log_out.php">
    <input type="submit" value="登出管理员模式">
</form>
<div style="width: 50%;float: left">
    <h2>修改表数据</h2>
    <form method="post" action="god_mode_process.php">
        <label>
            <input type="radio" name="change" value="delete">注销用户信息<br>
            <input type="radio" name="change" value="update">更改用户信息<br>
        </label>
        <br>
        <br>
        <label>
            注销用户信息：<br>
            目标用户ID：<input type="text" name="no_del" autocomplete="off">
        </label>
        <br>
        <br>
        <br>
        <label>
            更改用户信息：<br>
            目标用户ID：<input type="text" name="no_up" autocomplete="off">
            <br>
            要更改的属性：
            <select name="nature">
                <option value="pwd">密码</option>
                <option value="name">姓名</option>
                <option value="dept">院系</option>
                <option value="sex">性别</option>
            </select>
            <br>
            更改后的值：<input type="text" name="val_change" autocomplete="off">
        </label>
        <br>
        <br>
        <label>
            <input type="hidden" name="from" value="from_god">
        </label>
        <br><input type="submit" value="提交">
    </form>
</div>

<div style="width: 50%;float: right">
    <h2>并发测试</h2>
    <form method="post" action="benchmark.php">
        测试查询次数：（默认为5000）<br>
        <input type="text" name="epochs" autocomplete="off">
        <input type="submit" value="提交">
    </form>

    <h2>备份与恢复</h2>
    <form method="post" action="backup_restore.php">
        <input type="submit" name="todo" value="备份">
        <input type="submit" name="todo" value="恢复">
    </form>

    <h2>清除所有数据</h2>
    <form method="post" action="god_mode_process.php" onsubmit="return confirm('你确定吗？此举将清除所有数据条目！')">
        <input type="hidden" name="from" value="from_god">
        <input type="hidden" name="change" value="all_clear">
        <input type="submit" value="清空">
    </form>

    <h2>完整性约束检查</h2>
    <form action="completeness_check.php">
        <input type="submit" value="检查">
    </form>
</div>

</body>




