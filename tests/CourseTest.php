<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Student.php";
    require_once "src/Course.php";
    $server = 'mysql:host=localhost:8889;dbname=school_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CourseTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Student::deleteAll();
            Course::deleteAll();
        }

        function testGetName()
        {
            //Arrange
            $name = "Amanda Bynes's Robot History";
            $id = 2;
            $test_course = new Course($name, $id);

            //Act
            $result = $test_course->getName();

            //Assert
            $this->assertEquals($result, $name);
        }

        function testGetId()
        {
            //Arrange
            $name = "Amanda Bynes's Robot History";
            $id = 2;
            $test_course = new Course($name, $id);

            //Act
            $result = $test_course->getId();

            //Assert
            $this->assertEquals($result, $id);

        }

        function testSave()
        {
            //Arrange
            $name = "Amanda Bynes's Robot History";
            $id = 2;
            $test_course = new Course($name, $id);

            //Act
            $test_course->save();
            $result = Course::getAll();

            //Assert
            $this->assertEquals($result, [$test_course]);
        }

        function testGetAll()
        {

                //Arrange
                $name = "Amanda Bynes's Robot History";
                $id = 2;
                $test_course = new Course($name, $id);
                $name2 = "Bong Toss: Applied Physiiicks";
                $id2 = 3;
                $test_course2 = new Course($name2, $id2);

                //Act
                $test_course->save();
                $test_course2->save();
                $result = Course::getAll();

                //Assert
                $this->assertEquals($result, [$test_course, $test_course2]);
        }

        function testGetStudents()
        {
            //Arrange
            $course_name = "Amanda Bynes's Robot History";
            $id = null;
            $test_course = new Course($course_name, $id);
            $student_name = "amanda";
            $test_student1 = new Student($student_name, $id);
            $student_name2 = "barry";
            $test_student2 = new Student($student_name2, $id);

            //Act
            $test_course->save();
            $test_student1->save();
            $test_student2->save();
            $test_course->addStudent($test_student1);
            $test_course->addStudent($test_student2);
            $result = $test_course->getStudents();

            //Assert
            $this->assertEquals([$test_student1, $test_student2], $result);
        }

    }

    ?>
