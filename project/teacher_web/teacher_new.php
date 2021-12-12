<head>
    <title>教务系统-教师端-新开课程</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../css/new_style.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
function mapped($value): string
{
    switch ($value) {
        case "cid":
            return "课头号";
        case "c_name":
            return "课程名";
        case "t_name":
            return "授课教师";
        case "t_dept":
            return "开课学院";
        case "credit":
            return "学分";
        case "score":
            return "成绩";
        default:
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
                新开课程/New course
            </div>
            <div class="toggle">
                <div class="toggle__option" onclick="window.location.replace('teacher_new.php')">撤销</div>
                <div class="toggle__option" onclick="document.getElementById('newCourse').submit()">提交</div>
            </div>
        </div>
        <div class="calendar">
            <div class="container">
                <form method="post" id="newCourse" action="new_process.php">
                    <div class="group">
                        <input name="cid" type="text" required="" autocomplete="off">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label id="newer">课头号(6位)</label>
                    </div>
                    <div class="group">
                        <input name="cname" type="text" required="" autocomplete="off">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label id="newer">课程名</label>
                    </div>
                    <div class="group">
                        <input name="credit" type="text" required="" autocomplete="off">
                        <span class="highlight"></span>
                        <span class="bar"></span>
                        <label id="newer">学分</label>
                    </div>
                </form>
            </div>
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
            <a class="menu__item menu__item--active" href="#">
                <i class="menu__icon fa fa-trophy"></i>
                <span class="menu__text">新开课程</span>
            </a>
            <a class="menu__item" href="teacher_about.php">
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