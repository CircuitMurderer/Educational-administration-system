<head>
    <title>教务系统-教师端-管理课程</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../css/student_index.css" rel="stylesheet" type="text/css">
</head>

<body>
<script>
    function lets_manage(cid)
    {
        document.getElementById('manage').value = cid;
        document.getElementById('manageForm').submit();
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

function put_row($row, $row_num)
{
    echo '<tr>';
    foreach ($row as $key => $value) {
        echo "<td class='text-left' onclick='lets_manage(\"{$row['cid']}\")' style='cursor: pointer'>" . $value . "</td>";
    }
    echo "<td class='text-left' onclick='lets_manage(\"{$row['cid']}\")' style='cursor: pointer'>" . $row_num['num'] . "</td>";
    echo '</tr>';
}

function put_head($row)
{
    $keys = array_keys($row);
    echo '<tr>';
    foreach ($keys as $key => $value) {
        echo "<th class='text-left'>" . mapped($value) . "</th>";
    }
    echo "<th class='text-left'>选课人数</th>";
    echo '</tr>';
}

function get_chose_num($cid, $lk)
{
    $qy = "SELECT COUNT(*) AS num FROM chose_course WHERE cid = '{$cid}'";
    $rec_num = $lk->query($qy);
    return $rec_num->fetch_array(MYSQLI_ASSOC);
}

function put_table($rec, $lk)
{
    echo "<form method='post' id='manageForm' action='manage_process.php'>";
    echo "<input type='hidden' name='manage' id='manage' value=''>";
    if ($row = $rec->fetch_array(MYSQLI_ASSOC)) {
        echo '<table class="table-fill">';
        echo '<thead>';
        put_head($row);
        echo '</thead>';
        echo '<tbody class="table-hover">';

        put_row($row, get_chose_num($row['cid'], $lk));
        while ($row = $rec->fetch_array(MYSQLI_ASSOC)) {
            put_row($row, get_chose_num($row['cid'], $lk));
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo "开设课程为空！请检查是否已经开课。";
    }
    echo "</form>";
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
                管理课程/Manage courses
            </div>
            <div class="search-input">
                点击相应课程进行管理。
            </div>
        </div>
        <div class="calendar">
            <?php
            $qy = <<<QUERY
                SELECT cid, c_name, credit
                FROM course
                WHERE tid = '{$uid}';
            QUERY;
            $rec = $linked->query($qy);
            put_table($rec, $linked);
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
            <a class="menu__item menu__item--active" href="#">
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