<?php
    include '../scripts/auth.php';

    $data = getUserMeta($_REQUEST['id'], $_REQUEST['type'], '');

    if ($_SESSION['login'] === $data['Email'] && $_POST['action'] == 'refresh') {
        setUserMeta($_REQUEST['id'], $_REQUEST['type'], $_POST['props']);
    }
    $flag = $_SESSION['login'] == $data['Email'];

    if($_REQUEST['type'] == 'teacher') {
        $subjects = getSubjects();
        $teachers_subjects = getTeacherSubjects($_REQUEST['id']);
    }

    $data = getUserMeta($_REQUEST['id'], $_REQUEST['type'], '');
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>repetitori</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="../css/index.css" rel="stylesheet" type="text/css">
    <link href="../css/user.css" rel="stylesheet" type="text/css">
</head>
    <body>
        <div id="Main">
            <div class="NavBar">
                <a href="main.php">To main</a>
                <div>
                    <?=$data['Email']?>
                </div>
            </div>
            <div class="InfoWrapper">
                <form action="user.php?id=<?=$_REQUEST['id']?>&type=<?=$_REQUEST['type']?>" method="POST">
                <div class="Info">
                    <input <?=!$flag ? 'readonly' : ''?> name="props[name]" value="<?=$data['Name']?>" placeholder="name"/>
                    <input <?=!$flag ? 'readonly' : ''?>  name="props[mobile]" value="<?=$data['Phone']?>" placeholder="mobile"/>
                    <textarea <?=!$flag ? 'readonly' : ''?>  name="props[about]" placeholder="About me"><?=$data['About']?></textarea>
                    <?=$flag ?'<button name="action" value="refresh" type="submit">Change info</button>': ''?>
                </div>
                    <div class="Info">
                        <?php
                            $courses = getMyCources($data['Id'], $_SESSION['type']);
                            foreach ($courses as $row) {
                                if($_SESSION['type'] == 'student') {
                                    echo  "<div><a href='user.php?id={$row['Teacher_Id']}&type=teacher'>
                                            {$row['Email']}
                                        </a>{$row['Subject']}</div>";
                                } else {
                                    echo  "<div><a href='user.php?id={$row['Student_Id']}&type=student'>
                                            {$row['Email']}
                                        </a>{$row['Subject']}</div>";
                                }
                            }
                        ?>
                    </div>
                    <?php
                    if ($_REQUEST['type'] == 'teacher') {
                        echo '<div class="InfoSubjects">';
                        $isRead = !$flag ? 'readonly' : '';
                        foreach ($subjects as $row) {
                            $isTeachersLesson = false;
                            foreach ($teachers_subjects as $subject) {
                                if($subject['Lesson_Id'] == $row['Id']) {
                                    $isTeachersLesson = true;
                                }
                            }
                            $isChecked = $isTeachersLesson ? 'checked' : '';
                            if ($isChecked == 'checked') {
                                echo "
                            <input {$isRead} id='{$row['Id']}' name='props[subjects][{$row['Id']}]' value='{$row['Id']}' {$isChecked} type='checkbox'>
                            <label for='{$row['Id']}'>
                                {$row['Subject']}_{$row['Age']}
                            </label>";
                            } else if($flag) {
                                echo "
                            <input {$isRead} id='{$row['Id']}' name='props[subjects][{$row['Id']}]' value='{$row['Id']}' {$isChecked} type='checkbox'>
                            <label for='{$row['Id']}'>
                                {$row['Subject']}_{$row['Age']}
                            </label>";
                            }
                        }
                        echo "</div>";
                    }
                    ?>
                </form>
            </div>
        </div>
    </body>
</html>

