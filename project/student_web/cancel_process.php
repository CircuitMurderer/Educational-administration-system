<?php
function pop_window($display, $locate)
{
    echo "<script>";
    echo "alert('{$display}');";
    echo "window.location.replace('{$locate}');";
    echo "</script>";
}

$linked = new mysqli("localhost:999", "userSD", "student");
if ($linked->connect_error) {
    die("Connect failed:" . $linked->connect_error);
}
$linked->select_db("edu");

if (!isset($_COOKIE['uid'])) {
    pop_window("登录已过期，或你的浏览器不支持cookie！", "index.php");
}

$uid = $_COOKIE['uid'];

$qy = "";
if (!isset($_POST['cancel'])) {
    pop_window("请选择要撤掉的课程！", "student_cancel.php");
} else {
    foreach ($_POST['cancel'] as $key=>$value) {
        $qy .= "DELETE FROM chose_course WHERE sid = '{$uid}' AND cid = '{$value}';";
    }
    if ($linked->multi_query($qy) === TRUE) {
        pop_window("撤课成功！", "student_cancel.php");
    } else {
        pop_window($linked->error, "student_cancel.php");
    }
}