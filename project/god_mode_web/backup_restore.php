<?php
function pop_window($display, $locate)
{
    echo "<script>";
    echo "alert('{$display}');";
    echo "window.location.replace('{$locate}');";
    echo "</script>";
}

$username = "root";
$password = "123456";

if (!isset($_COOKIE['uid']) or $_COOKIE['uid'] != "superuser") {
    pop_window("你不是真正的管理员！", "../index.php");
    return;
}

if (!isset($_POST['todo'])) {
    pop_window("请从管理员界面进入此处！", "god_mode.php");
    return;
}

$exe = "";
$todo = $_POST['todo'];
if ($todo == "备份") {
    $exe = "/usr/local/bin/mysqldump -u{$username} -p{$password} edu > ~/db_backup/edu_backup.sql";
} elseif ($todo == "恢复") {
    $exe = "/usr/local/bin/mysql -u{$username} -p{$password} edu < ~/db_backup/edu_backup.sql";
}

exec($exe, $res, $no_success);

if ($no_success) {
    pop_window("{$todo}失败！" . $res, "god_mode.php");
} else {
    pop_window("{$todo}成功！", "god_mode.php");
}
