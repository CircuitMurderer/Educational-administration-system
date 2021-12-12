
<?php
function is_in($key_arr, $tar): bool
{
    foreach ($key_arr as $key) {
        if (!array_key_exists($key, $tar)) {
            return false;
        }
    }
    return true;
}

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

$keys = array('uid', 'pwd', 'name', 'sex', 'dept');
if (!is_in($keys, $_POST)) {
    pop_window("请确认是否全部填写！", "register.php");
    return;
}

$uid = $_POST['uid'];
$pwd = $_POST['pwd'];
$name = $_POST['name'];
$sex = $_POST['sex'];
$dept = $_POST['dept'];

$stmt = $linked->prepare("SELECT uid FROM user WHERE uid = ?");
$stmt->bind_param("s", $uid);
$stmt->bind_result($select_uid);

$stmt->execute();
$stmt->store_result();

if ($stmt->fetch()) {
    $stmt->close();
    pop_window("此ID已被注册！", "register.php");
    return;
} else {
    $stmt->close();

    $mode = "";
    $tb = "";
    $jd = strlen($uid);

    if (!is_numeric($uid)) {
        pop_window("用户ID必须为数字！", "register.php");
        return;
    }

    if ($jd == 10) {
        $mode = "SD";
        $tb = "student";
    } elseif ($jd == 8) {
        $mode = "TC";
        $tb = "teacher";
    } else {
        pop_window("非法的用户ID长度！学生：10位；教师：8位。", "register.php");
        return;
    }

    $pwd = hash("sha1", $pwd);

    $stmt = $linked->prepare("INSERT INTO user VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $uid, $pwd, $mode);
    $stmt->execute();

    if ($stmt->store_result()) {
        $stmt->close();

        $stmt = $linked->prepare("INSERT INTO {$tb} VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $uid, $name, $dept, $sex);
        $stmt->execute();

        $judge = $stmt->store_result();
        $stmt->close();
        if ($judge) {
            pop_window("注册成功！", "index.php");
        } else {
            $err = $linked->error;
            $linked->query("DELETE FROM user WHERE uid = '{$uid}'");
            $err_msg = "出错了！ " . $err;
            pop_window($err_msg, "register.php");
        }
    } else {
        $stmt->close();
        $err = $linked->error;
        $err_msg = "出错了！ " . $err;
        pop_window($err_msg, "register.php");
    }

}

$linked->close();
