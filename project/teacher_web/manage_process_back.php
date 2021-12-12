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

if (!(isset($_POST['sid']) and isset($_POST['score']) and isset($_POST['manage']))) {
    pop_window("信息缺失！", "manage_process.php");
    return;
}

$qy = "";
foreach ($_POST['sid'] as $key=>$value) {
    $score = "";
    if (!(is_numeric($_POST['score'][$key]) or empty($_POST['score'][$key]))) {
        pop_window("分数必须是数字！", "manage_process.php");
        return;
    }

    if (empty($_POST['score'][$key])) {
        $score = "NULL";
    } else {
        $score = $_POST['score'][$key];
    }

    if ($score != "NULL" and ($score > 100 or $score < 0)) {
        pop_window("分数必须在0~100之间！", "manage_process.php");
        return;
    }

    $qy .= "UPDATE chose_course SET score = {$score} WHERE cid = '{$_POST['manage']}' AND sid = '{$value}';";
}

if ($linked->multi_query($qy) === TRUE) {
    pop_window("成绩更新成功！", "manage_process.php");
} else {
    pop_window($linked->error, "manage_process.php");
}

$linked->close();
