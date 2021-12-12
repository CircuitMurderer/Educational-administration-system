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

if (!isset($_COOKIE['uid'])) {
    pop_window("登录已过期，或你的浏览器不支持cookie！", "index.php");
}

if (!isset($_POST['manage'])) {
    pop_window("信息缺失！", "manage_process.php");
    return;
}

$qy = "DELETE FROM course WHERE cid = '{$_POST['manage']}'";
if ($linked->query($qy) === TRUE) {
    pop_window("删除成功！", "teacher_manage.php");
} else {
    pop_window($linked->error, "teacher_manage.php");
}
