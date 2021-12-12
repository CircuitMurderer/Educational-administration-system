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
$epochs = 5000;
$clients = rand(100, 1000);

if (!isset($_COOKIE['uid']) or $_COOKIE['uid'] != "superuser") {
    pop_window("你不是真正的管理员！", "../index.php");
    return;
}

if (isset($_POST['epochs']) and !empty($_POST['epochs'])) {
    $epochs = $_POST['epochs'];
}

$exe = "/usr/local/bin/mysqlslap --no-defaults -u{$username} -p{$password} --auto-generate-sql --number-of-queries={$epochs} -c {$clients}";

$rec = exec($exe, $res, $no_success);

if (!$no_success) {
    foreach ($res as $value) {
        echo $value;
        echo "<br>";
    }
} else {
    echo "出错了！<br>";
}

echo "<a href='god_mode.php'>点击此处返回。。</a>";

