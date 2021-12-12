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
if (!isset($_POST['chosen'])) {
    pop_window("尚未选课！", "student_choose.php");
} else {
    foreach ($_POST['chosen'] as $key=>$value) {
        $qy .= "INSERT INTO chose_course VALUES ('{$uid}', '{$value}', NULL);";
    }
    if ($linked->multi_query($qy) === TRUE) {
        pop_window("选课成功！", "student_choose.php");
    } else {
        pop_window($linked->error, "student_choose.php");
    }
}