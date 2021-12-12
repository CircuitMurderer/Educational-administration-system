<?php
function pop_window($display, $locate)
{
    echo "<script>";
    echo "alert('{$display}');";
    echo "window.location.replace('{$locate}');";
    echo "</script>";
}

$linked = new mysqli("localhost:999", "userTC", "teacher");
if ($linked->connect_error) {
    die("Connect failed:" . $linked->connect_error);
}
$linked->select_db("edu");

if (!isset($_COOKIE['uid'])) {
    pop_window("登录已过期，或你的浏览器不支持cookie！", "index.php");
    return;
}

if (!(isset($_POST['cid']) and isset($_POST['cname']) and isset($_POST['credit']))) {
    pop_window("输入信息不全！", "teacher_new.php");
    return;
}

if (strlen($_POST['cid']) == 0 or strlen($_POST['cname']) == 0 or strlen($_POST['credit']) == 0) {
    pop_window("输入不能为空！", "teacher_new.php");
    return;
}

$qy = "SELECT * FROM course WHERE cid = '{$_POST['cid']}'";
$rec = $linked->query($qy);
if ($rec->fetch_array(MYSQLI_ASSOC)) {
    pop_window("课程号已经存在！", "teacher_new.php");
    return;
}

$qy = "INSERT INTO course VALUES ('{$_POST['cid']}', '{$_POST['cname']}', '{$_COOKIE['uid']}', '{$_POST['credit']}');";

$rec = $linked->query($qy);
if ($rec) {
    pop_window("添加课程成功！", "teacher_new.php");
} else {
    pop_window($linked->error, "teacher_new.php");
}
