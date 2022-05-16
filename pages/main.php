<?php
include '../scripts/auth.php';

$data = getUserMeta('', $_SESSION['type'], $_SESSION['login']);

$flag = $_SESSION['login'] == $data['Email'];

if ($_SESSION['login'] === $data['Email'] && $_POST['action'] == 'refresh') {
    setUserMeta($_REQUEST['id'], $_REQUEST['type'], $_POST['props']);
}

if ($_SESSION['login'] === $data['Email'] && $_POST['action'] == 'add_course') {
    addCourse($data['Id'], $_SESSION['type'], $_POST['subjects']);
}

$subjects = getSubjects();
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>repetitori</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="../css/index.css" rel="stylesheet" type="text/css">
    <link href="../css/main.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="Main">
    <div class="NavBar">
        <a href="user.php?id=<?=$data['Id']?>&type=<?=$_SESSION['type']?>">To profile</a>
        <div>
            <?=$_SESSION['login']?>
        </div>
        <div>
            <form action="main.php" method="POST">
                <button name="action" value="logout" type="submit">Log out</button>
            </form>
        </div>
    </div>
    <div class="MainWrapper">
        <div class="WorkField">
            <form action="main.php" method="POST">
                <?php
                    if ($_REQUEST['action'] == 'filter') {
                        foreach ($_REQUEST['subjects'] as $subject) {
                            $teachers = getTeachersBySubject($subject);
                            foreach ($teachers as $teacher) {
                                echo "<div class='Teacher'><a href='user.php?id={$teacher['Id']}&type=teacher'>
                                    {$teacher['Email']}
                                    {$teacher['Name']}
                                        </a>";
                                $subject_teacher_new = getTeachersBySubject($teacher['Id']);
                                foreach ($subjects as $row) {
                                    $isTeachersLesson = false;
                                    if ($subject == $teacher['Lesson_Id'] and $row['Id'] == $subject) {
                                        echo "
                                        <input id='{$row['Id']}{$teacher['Id']}' name='subjects[{$row['Id']}][{$teacher['Id']}]' value='{$row['Id']}' type='checkbox'>
                                            <label for='{$row['Id']}{$teacher['Id']}'>
                                            {$row['Subject']}_{$row['Age']}
                                        </label>";
                                    }
                                }
                                echo '</div>';
                            }
                        }
                    }
                ?>
                <?= $_SESSION['type'] == 'student' ? '<button name="action" type="submit" value="add_course">Add courses</button>' : ''?>
            </form>
        </div>
        <div class="Filter">
            <form action="main.php" method="GET">
                <?php
                    foreach ($subjects as $row) {
                        echo "
                            <input id='{$row['Id']}' name='subjects[{$row['Id']}]' value='{$row['Id']}' type='checkbox'>
                            <label for='{$row['Id']}'>
                                {$row['Subject']}_{$row['Age']}
                            </label>";
                    }
                ?>
                <button name="action" type="submit" value="filter">filter</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

