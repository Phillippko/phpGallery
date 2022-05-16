<?php
$db = new mysqli('localhost:3306', 'root', '', 'phillippko');

function checkUserProps($login, $password, $type) {
    global $db;
    $response = $db -> query("
        SELECT *
        FROM Authorization
        WHERE login = '{$login}' AND password = '{$password}' AND type = '${type}'
    ");
    if ($response->num_rows) {
       return true;
    }
    return false;
}

function registerUser($email, $password, $type) {
    global $db;
    if (!$db -> query("INSERT INTO `Authorization`(`Login`, `Password`, `Type`) VALUES ('{$email}', '{$password}', '{$type}')")) {
        return false;
    }

    switch ($type) {
        case 'student':
            return $db -> query("INSERT INTO `Student`(`Name`, `Email`, `Phone`, `About`) VALUES ('', '{$email}', '', '')");
        case 'teacher':
            return $db -> query("INSERT INTO `Teacher`(`Name`, `Email`, `Phone`, `Document`, `About`) VALUES ('', '{$email}', '', '', '')");
    }
}

function getUserMeta($id, $type, $login) {
    global $db;
    $response = '';
    if ($id != '') {
        switch ($type) {
            case 'student':
                $result = $db -> query("SELECT * FROM Student WHERE Id = {$id}");
                foreach($result as $row){
                    global $response;
                    $response = $row;
                }
                return $response;

            case 'teacher':
                $result = $db -> query("SELECT * FROM Teacher WHERE Id = {$id}");
                foreach($result as $row){
                    global $response;
                    $response = $row;
                }
                return $response;
        }
    }
    if ($login != '') {
        switch ($type) {
            case 'student':
                $result = $db -> query("SELECT * FROM Student WHERE Email = '{$login}'");
                foreach($result as $row){
                    global $response;
                    $response = $row;
                }
                return $response;

            case 'teacher':
                $result = $db -> query("SELECT * FROM Teacher WHERE Email = '{$login}'");
                foreach($result as $row){
                    global $response;
                    $response = $row;
                }
                return $response;
        }
    }
}

function setUserMeta($id, $type, $props) {
    global $db;

    $response = getUserMeta($id, $type, '');

    switch ($type) {
        case 'student':
            return $db->query("UPDATE Student SET Name = '{$props['name']}', Phone = '{$props['mobile']}', About = '{$props['about']}' WHERE email = '{$response['Email']}'");
        case 'teacher':
            $db->query("DELETE FROM `teacher_lesson` WHERE Teacher_Id = {$id}");
            foreach ($props['subjects'] as $subject) {
                $db->query("INSERT INTO `teacher_lesson`(`Teacher_id`, `Lesson_Id`) VALUES ({$id}, {$subject})");
            }
            return $db->query("UPDATE Teacher SET Name = '{$props['name']}', Phone = '{$props['mobile']}', About = '{$props['about']}', Document = '{$props['document']}' WHERE email = '{$response['Email']}'");
    }
}


function getTeachersBySubject($id) {
    global $db;
    return $db->query("SELECT * FROM Teacher_Lesson JOIN Teacher ON Teacher_Lesson.Teacher_Id = Teacher.Id WHERE Lesson_Id = {$id}");
}

function getTeacherSubjects($id) {
    global $db;
    return $db -> query("SELECT Lesson_Id FROM Teacher_Lesson WHERE Teacher_Id = {$id}");
}

function getSubjects() {
    global $db;
    return $db->query("SELECT * FROM Lessons");
}

function addCourse($id, $type, $courses) {
    global $db;
    foreach ($courses as $course => $teachers) {
        foreach ($teachers as $teacher => $value) {
            echo "{$teacher}, {$id}, {$course}";
            $db -> query("INSERT INTO `teacher_student`(`Teacher_Id`, `Student_Id`, `Lesson_Id`) VALUES ({$teacher}, {$id}, {$course})");
        }
    }
}

function getMyCources($id, $type) {
    global $db;
    switch($type) {
        case 'student':
            return $db->query("SELECT * FROM Teacher_Student JOIN Lessons ON Teacher_Student.Lesson_Id = Lessons.Id JOIN Teacher ON Teacher.Id = Teacher_Student.Teacher_Id WHERE Student_Id = {$id}");
        case 'teacher':
            return $db->query("SELECT * FROM Teacher_Student JOIN Lessons ON Teacher_Student.Lesson_Id = Lessons.Id JOIN Student ON Student.Id = Teacher_Student.Student_Id WHERE Teacher_Id = {$id}");
    }
}

?>