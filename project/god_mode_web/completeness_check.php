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

$qy_f = "SELECT @@foreign_key_checks";
$qy_u = "SELECT @@unique_checks";

$output = "";

$rec_f = $linked->query($qy_f);
if ($row_f = $rec_f->fetch_array(MYSQLI_ASSOC)) {
    if ($row_f['@@foreign_key_checks'] == 1) {
        $output .= "外键检查成功";
    } else {
        $output .= "外键检查失败";
    }
} else {
    $output .= "外键检查出现错误";
}

$output .= "，";

$rec_u = $linked->query($qy_u);
if ($row_u = $rec_u->fetch_array(MYSQLI_ASSOC)) {
    if ($row_u['@@unique_checks'] == 1) {
        $output .= "主键检查成功";
    } else {
        $output .= "主键检查失败";
    }
} else {
    $output .= "主键检查出现错误";
}

$output .= "。";
pop_window($output, "god_mode.php");

$linked->close();


