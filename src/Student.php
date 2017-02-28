<?php
    class Student
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $exec = $GLOBALS['DB']->prepare("INSERT INTO students (name) VALUES (:name);");
            $exec->execute([':name' => $this->getName()]);
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function addCourse($course)
        {
            $exec = $GLOBALS['DB']->prepare("INSERT INTO courses_students (student_id, course_id) VALUES (:student_id, :course_id);");
            $exec->execute([':student_id'=>$this->getId(), ':course_id'=>$course->getId()]);
        }

        function getCourses()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT courses.* FROM students JOIN courses_students ON (courses_students.student_id = students.id) JOIN courses ON (courses.id = courses_students.course_id) WHERE students.id = {$this->getId()};");
            $courses = [];
            foreach($returned_courses as $course) {
                $name = $course['name'];
                $id = $course['id'];
                $new_course = new Course($name, $id);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        static function getAll()
        {
            $returned_students = $GLOBALS['DB']->query("SELECT * FROM students;");
            $students = [];
            foreach($returned_students as $student)
            {
                $name = $student['name'];
                $id = $student['id'];
                $new_student = new Student($name, $id);
                array_push($students, $new_student);
            }
            return $students;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM students");
        }
    }

?>
