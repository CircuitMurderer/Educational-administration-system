<head>
    <title>教务系统-教师端-个人信息</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../css/student_index.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
function put_table($rec)
{
    $row = $rec->fetch_array(MYSQLI_ASSOC);
    echo "<br>";
    echo "教师号：{$row['tid']}<br>";
    echo "姓名：{$row['t_name']}<br>";
    echo "性别：";
    if ($row['t_sex'] == "male") {
        echo "男<br>";
    } else {
        echo "女<br>";
    }
    echo "所属院系：{$row['t_dept']}<br>";
    echo "<br>";
    $year0 = substr($row['tid'], 0, 4);
    echo "任职年度：{$year0}<br>";
    $now = new DateTime();
    $year = $now->format("Y");
    $bias = $year - (int)$year0;
    echo "任职年数：{$bias}";
}

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

if (!isset($_COOKIE['uid'])) {
    pop_window("登录已过期，或你的浏览器不支持cookie！", "index.php");
    return;
}

$uid = $_COOKIE['uid'];

if (strlen($uid) != 8) {
    pop_window("用户ID非教师ID！", "index.php");
    return;
}

$rec = $linked->query("SELECT t_name FROM teacher WHERE tid = '{$uid}'");
$row = $rec->fetch_array(MYSQLI_ASSOC);

$user_name = $row['t_name'];

?>
<div class="wrapper">
    <main>
        <div class="toolbar">
            <div class="current-month" style="font-weight: normal;font-size: 20px">
                个人信息/Personal details
            </div>
        </div>
        <div class="calendar">
            <?php
            $qy = <<<QUERY
                SELECT * FROM teacher WHERE tid = '{$uid}';
            QUERY;
            $rec = $linked->query($qy);
            put_table($rec);
            ?>
        </div>
    </main>
    <sidebar>
        <div class="logo">教师</div>
        <div class="avatar">
            <div class="avatar__img">
                <img src="../images/head_t.jpg" alt="avatar">
            </div>
            <div class="avatar__name">
                <?php echo $user_name; ?>
            </div>
        </div>
        <nav class="menu">
            <a class="menu__item" href="teacher_index.php">
                <i class="menu__icon fa fa-calendar"></i>
                <span class="menu__text">开设课程</span>
            </a>
            <a class="menu__item" href="teacher_manage.php">
                <i class="menu__icon fa fa-bar-chart"></i>
                <span class="menu__text">管理课程</span>
            </a>
            <a class="menu__item" href="teacher_new.php">
                <i class="menu__icon fa fa-trophy"></i>
                <span class="menu__text">新开课程</span>
            </a>
            <a class="menu__item menu__item--active" href="#">
                <i class="menu__icon fa fa-sliders"></i>
                <span class="menu__text">个人信息</span>
            </a>
            <a class="menu__item" href="../log_out.php">
                <i class="menu__icon fa fa-list"></i>
                <span class="menu__text">登出</span>
            </a>

        </nav>
        <div class="copyright">Copyright &copy; 2021</div>
    </sidebar>
</div>
</body>
