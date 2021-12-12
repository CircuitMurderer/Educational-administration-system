<head>
    <title>教务系统-学生端-个人信息</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../css/student_index.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
function put_table($rec)
{
    $row = $rec->fetch_array(MYSQLI_ASSOC);
    echo "<br>";
    echo "学号：{$row['sid']}<br>";
    echo "姓名：{$row['s_name']}<br>";
    echo "性别：";
    if ($row['s_sex'] == "male") {
        echo "男<br>";
    } else {
        echo "女<br>";
    }
    echo "院系：{$row['s_dept']}<br>";
    echo "<br>";
    $year0 = substr($row['sid'], 0, 4);
    echo "入学年度：{$year0}<br>";
    $now = new DateTime();
    $year = $now->format("Y");
    $bias = $year - (int)$year0;
    echo "年级：{$bias}";
}

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
    return;
}

$uid = $_COOKIE['uid'];

if (strlen($uid) != 10) {
    pop_window("用户ID非学生ID！", "index.php");
    return;
}

$rec = $linked->query("SELECT s_name FROM student WHERE sid = '{$uid}'");
$row = $rec->fetch_array(MYSQLI_ASSOC);

$user_name = $row['s_name'];

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
                SELECT * FROM student WHERE sid = '{$uid}';
            QUERY;
            $rec = $linked->query($qy);
            put_table($rec);
            ?>
        </div>
    </main>
    <sidebar>
        <div class="logo">学生</div>
        <div class="avatar">
            <div class="avatar__img">
                <img src="../images/head.jpg" alt="avatar">
            </div>
            <div class="avatar__name">
                <?php echo $user_name; ?>
            </div>
        </div>
        <nav class="menu">
            <a class="menu__item" href="student_index.php">
                <i class="menu__icon fa fa-calendar"></i>
                <span class="menu__text">已选课程</span>
            </a>
            <a class="menu__item" href="student_choose.php">
                <i class="menu__icon fa fa-bar-chart"></i>
                <span class="menu__text">选课</span>
            </a>
            <a class="menu__item" href="student_cancel.php">
                <i class="menu__icon fa fa-trophy"></i>
                <span class="menu__text">撤课</span>
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
