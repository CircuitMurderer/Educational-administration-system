<body>
<?php
function pop_window($display, $locate)
{
    echo "<script>";
    echo "alert('{$display}');";
    echo "window.location.replace('{$locate}');";
    echo "</script>";
}

function confirm_window($display, $locate_1, $locate_2)
{
    echo "<script>";
    echo "if (confirm('{$display}')) { window.location.replace('{$locate_1}'); }";
    echo "else { window.location.replace('{$locate_2}'); }";
    echo "</script>";
}

$linked = new mysqli("localhost:999", "root", "123456");
if ($linked->connect_error) {
    die("Connect failed:" . $linked->connect_error);
}
$linked->select_db("edu");

$tar_web = "#";
$tar_uid = "";
$tar_pwd = "";

if (isset($_POST['uid'])) {
    $uid = $_POST['uid'];

    $stmt = $linked->prepare("SELECT mode, pwd FROM user WHERE uid = ?");
    $stmt->bind_param("s", $uid);
    $stmt->bind_result($select_mode, $select_pwd);

    $stmt->execute();
    $stmt->store_result();
    if ($stmt->fetch()) {
        $pwd = hash("sha1", $_POST['pwd']);
        $tar_pwd = $pwd;

        if ($pwd == $select_pwd) {
            $tar_uid = $uid;
            switch ($select_mode) {
                case "SU":
                    $tar_web = "god_mode_web/god_mode.php";
                    break;
                case "SD":
                    $tar_web = "student_web/student_index.php";
                    break;
                case "TC":
                    $tar_web = "teacher_web/teacher_index.php";
                    break;
                default:
            }

            setcookie("uid", $tar_uid, time() + 60 * 30);
            // time() + sec * min * hour * day
        } else {
            pop_window("密码错误，请重试！", "index.php");
        }
    } else {
        confirm_window("用户不存在。要注册吗？", "register.php", "index.php");
    }
    $stmt->close();
} else {
    pop_window("请从登录页面进入！", "index.php");
}

$linked->close();
?>

<form action="<?php echo $tar_web;?>" method="post" id="form1">
    <input type="hidden" name="usr_pwd" value="<?php echo $tar_pwd;?>">
    <input type="hidden" name="from" value="from_login">
</form>
<script>
    let form = document.getElementById('form1');
    let ele = form.getElementsByTagName('input');
    if (ele['usr_pwd'].length != 0) {
        form.submit();
    }
</script>

</body>

