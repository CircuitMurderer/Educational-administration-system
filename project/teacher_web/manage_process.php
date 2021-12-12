<head>
    <title>教务系统-教师端-管理课程</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../css/student_index.css" rel="stylesheet" type="text/css">
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
        case "s_name":
            return "学生姓名";
        case "s_dept":
            return "院系";
        case "sid":
            return "学号";
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
        if ($key == 'score') {
            break;
        }
        echo "<td class='text-left'>" . $value . "</td>";
    }
    echo "<td class='text-center'>";
    echo "<input type='hidden' name='sid[]' value='{$row['sid']}'>";
    echo "<input type='text' name='score[]' value='{$row['score']}' autocomplete='off'>";
    echo "</td>";
    echo '</tr>';
}

function put_head($row)
{
    $keys = array_keys($row);
    echo '<tr>';
    foreach ($keys as $key => $value) {
        if ($value == 'score') {
            echo "<th class='text-center'>" . mapped($value) . "</th>";
        } else {
            echo "<th class='text-left'>" . mapped($value) . "</th>";
        }
    }
    echo '</tr>';
}

function put_table($man, $rec)
{
    echo '<form method="post" action="manage_process_back.php" id="manageCourses">';
    echo "<input type='hidden' name='manage' value='{$man}'>";
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
        echo "暂时没有学生选这门课。";
    }
    echo '</form>';
    echo '<form method="post" action="delete_process.php" id="deleteCourses">';
    echo "<input type='hidden' name='manage' value='{$man}'>";
    echo '</form>';
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

if (!isset($_POST['manage'])) {
    header("location: teacher_manage.php");
    return;
}
$manage = $_POST['manage'];

$rec = $linked->query("SELECT t_name FROM teacher WHERE tid = '{$uid}'");
$row = $rec->fetch_array(MYSQLI_ASSOC);

$user_name = $row['t_name'];

$rec = $linked->query("SELECT c_name FROM course WHERE cid = '{$manage}'");
$row = $rec->fetch_array(MYSQLI_ASSOC);

$course_name = $row['c_name'];

?>
<div class="wrapper">
    <main>
        <div class="toolbar">
            <div class="current-month" style="font-weight: normal;font-size: 20px">
                管理课程 - (<?php echo $manage ?>)<?php echo $course_name ?> - 选课名单
            </div>
            <div class="toggle">
                <div class="toggle__option" onclick="document.getElementById('deleteCourses').submit()">删除</div>
                <div class="toggle__option" onclick="document.getElementById('manageCourses').submit()">提交</div>
            </div>
        </div>
        <div class="calendar">
            <?php
            $qy = <<<QUERY
                SELECT student.sid, s_name, s_dept, score
                FROM student, chose_course
                WHERE chose_course.cid = '{$manage}'
                AND chose_course.sid = student.sid;
            QUERY;
            $rec = $linked->query($qy);
            put_table($manage, $rec);
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
            <a class="menu__item menu__item--active" href="teacher_manage.php">
                <i class="menu__icon fa fa-bar-chart"></i>
                <span class="menu__text">管理课程</span>
            </a>
            <a class="menu__item" href="teacher_new.php">
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