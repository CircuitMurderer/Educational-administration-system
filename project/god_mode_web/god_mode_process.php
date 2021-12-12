<?php
function get_natures($uid, $nat): string
{
    if (strlen($uid) == 10) {
        if ($nat == "uid") {
            return "sid";
        } else {
            return "s_" . $nat;
        }
    } elseif (strlen($uid) == 8) {
        if ($nat == "uid") {
            return "tid";
        } else {
            return "t_" . $nat;
        }
    } else {
        return "";
    }
}

function get_table($uid): string
{
    if (strlen($uid) == 10) {
        return "student";
    } elseif (strlen(($uid)) == 8) {
        return "teacher";
    } else {
        return "";
    }
}

function pop_window($display, $locate)
{
    echo "<script>";
    echo "alert('{$display}');";
    echo "window.location.replace('{$locate}');";
    echo "</script>";
}

function dt_flush($lk, $tb)
{
    if (!$lk->query("DELETE FROM {$tb}")) {
        pop_window("出错了！" . $lk->error, "god_mode.php");
        exit(1);
    }
}

if (!isset($_COOKIE['uid']) or $_COOKIE['uid'] != "superuser") {
    pop_window("你无权进行这项操作！", "../index.php");
    return;
}

if (!isset($_POST['from']) or $_POST['from'] != "from_god") {
    pop_window("请从管理员界面进入此处！", "god_mode.php");
    return;
}

$linked = new mysqli("localhost:999", "root", "123456");
if ($linked->connect_error) {
    die("Connect failed:" . $linked->connect_error);
}

$linked->select_db("edu");

if (!isset($_POST['change'])) {
    pop_window("请选择要进行的操作！", "god_mode.php");
    return;
}

$qy = "";
if ($_POST['change'] == "all_clear") {
    dt_flush($linked, "chose_course");
    dt_flush($linked, "course");
    dt_flush($linked, "teacher");
    dt_flush($linked, "student");
    dt_flush($linked, "user WHERE uid <> 'superuser'");
    pop_window("数据已全部清空！", "god_mode.php");
} elseif ($_POST['change'] == "delete") {
    if (!isset($_POST['no_del']) or strlen($_POST['no_del']) == 0) {
        pop_window("请输入用户名！", "god_mode.php");
        return;
    }

    $uid = $_POST['no_del'];
    $table = get_table($uid);
    $tar = get_natures($uid, "uid");

    $qy = "DELETE FROM user WHERE uid = '{$uid}';";
    $qy .= "DELETE FROM {$table} WHERE {$tar} = '{$uid}';";

    if ($linked->multi_query($qy) === TRUE) {
        pop_window("删除成功！", "god_mode.php");
    } else {
        pop_window("出错了！" . $linked->error, "god_mode.php");
    }
} elseif ($_POST['change'] == "update") {
    if (!(isset($_POST['no_up']) and isset($_POST['val_change'])) or strlen($_POST['no_up']) == 0) {
        pop_window("输入信息不全！", "god_mode.php");
        return;
    }

    $uid = $_POST['no_up'];
    $table = get_table($uid);
    $tar = get_natures($uid, "uid");
    $nat = get_natures($uid, $_POST['nature']);
    $val = $_POST['val_change'];

    if ($_POST['nature'] == "sex" and $val == "男") {
        $val = "male";
    } elseif($_POST['nature'] == "sex" and $val == "女") {
        $val = "female";
    }

    $qy = "";
    if ($_POST['nature'] == "pwd") {
        $pwd = hash("sha1", $val);
        $qy = "UPDATE user SET pwd = '{$pwd}' WHERE uid = '{$uid}'";
    } else {
        $qy = "UPDATE {$table} SET {$nat} = '{$val}' WHERE {$tar} = '{$uid}'";
    }

    if ($linked->query($qy) === TRUE) {
        pop_window("修改成功！", "god_mode.php");
    } else {
        pop_window("出错了！" . $linked->error, "god_mode.php");
    }
}

$linked->close();

