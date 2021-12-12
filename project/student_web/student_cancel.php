<head>
    <title>教务系统-学生端-撤课</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../css/student_index.css" rel="stylesheet" type="text/css">
</head>

<body>
<script>
    function lets_click()
    {
        let input = document.getElementsByTagName("input");
        for (let i = 0; i < input.length; i++) {
            if (input[i].type == "checkbox") {
                input[i].checked = input[i].checked != true;
            }
        }
    }
</script>
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

function put_row($row)
{
    echo '<tr>';
    foreach ($row as $key => $value) {
        echo '<td class="text-left">' . $value . '</td>';
    }
    echo '<td class="text-center">';
    echo "<input type='checkbox' name='cancel[]' value='{$row['cid']}'>";
    echo '</td>';
    echo '</tr>';
}

function put_head($row)
{
    $keys = array_keys($row);
    echo '<tr>';
    foreach ($keys as $key => $value) {
        echo '<th class="text-left">' . mapped($value) . '</th>';
    }
    echo '<th class="text-center">撤课</th>';
    echo '</tr>';
}

function put_table($rec)
{
    echo '<form method="post" action="cancel_process.php" id="cancelCourses">';
    if ($row = $rec->fetch_array(MYSQLI_ASSOC)) {
        echo '<table class="table-fill">';
        echo '<thead>';
        put_head($row);
        echo '</thead>';
        echo '<tbody class="table-hover">';
        put_row($row);
        while ($row = $rec->fetch_array(MYSQLI_ASSOC)) {
            put_row($row);
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "已选课程列表为空。";
    }
    echo '</form>';
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
                撤课/Cancel course
            </div>
            <div class="toggle">
                <div class="toggle__option" onclick="lets_click()">反选</div>
                <div class="toggle__option" onclick="document.getElementById('cancelCourses').submit()">提交</div>
            </div>
        </div>
        <div class="calendar">
            <?php
            $qy = <<<QUERY
                SELECT course.cid, c_name, t_name, credit
                FROM course, chose_course, teacher 
                WHERE course.cid = chose_course.cid 
                AND course.tid = teacher.tid 
                AND chose_course.sid = '{$uid}';
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
            <a class="menu__item menu__item--active" href="#">
                <i class="menu__icon fa fa-trophy"></i>
                <span class="menu__text">撤课</span>
            </a>
            <a class="menu__item" href="student_about.php">
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