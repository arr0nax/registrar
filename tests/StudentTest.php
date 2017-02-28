<?php
/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "src/Course.php";
require_once "src/Student.php";
$server = 'mysql:host=localhost:8889;dbname=school_test';
$username = 'root';
$password = 'root';
$DB = new PDO($server, $username, $password);

class StudentTest extends PHPUnit_Framework_TestCase
{

    protected function tearDown()
    {
        Course::deleteAll();
        Student::deleteAll();
    }

    function testGetName()
    {
        //Arrange
        $name = "Amanda Bynes";
        $id = 2;
        $test_student = new Student($name, $id);

        //Act
        $result = $test_student->getName();

        //Assert
        $this->assertEquals($result, $name);
    }

    function testGetId()
    {
        //Arrange
        $name = "Amanda Bynes";
        $id = 2;
        $test_student = new Student($name, $id);

        //Act
        $result = $test_student->getId();

        //Assert
        $this->assertEquals($result, $id);

    }

    function testSave()
    {
        //Arrange
        $name = "Amanda Bynes";
        $id = 2;
        $test_student = new Student($name, $id);

        //Act
        $test_student->save();
        $result = Student::getAll();

        //Assert
        $this->assertEquals($result, [$test_student]);
    }

    function testGetCourses()
    {
        //Arrange
        $course_name = "Amanda Bynes's Robot History";
        $id = null;
        $test_course = new Course($course_name, $id);
        $name2 = "Bong Toss: Applied Physiiicks";
        $id2 = 3;
        $test_course2 = new Course($name2, $id2);
        $student_name = "amanda";
        $test_student1 = new Student($student_name, $id);

        //Act
        $test_course->save();
        $test_student1->save();
        $test_course2->save();
        $test_student1->addCourse($test_course);
        $test_student1->addCourse($test_course2);
        $result = $test_student1->getCourses();

        //Assert
        $this->assertEquals([$test_course, $test_course2], $result);
    }

    function testDelete()
    {
        //Arrange
        $name = "Amanda Bynes";
        $id = null;
        $test_student = new Student($name, $id);
        $name2 = "Barry";
        $id2 = null;
        $test_student2 = new Student($name, $id);

        //Act
        $test_student->save();
        $test_student2->save();
        $test_student->delete();

        $result = Student::getAll();

        //Assert
        $this->assertEquals($result, [$test_student2]);
    }

    function testUpdate()
    {
        //Arrange
        $name = "Amanda Bynes's Robot History";
        $id = 2;
        $test_student = new Student($name, $id);
        $name2 = "Barry";

        //Act
        $test_student->save();
        $test_student->update($name2);
        $test_student->setName($name2);
        $result = Student::getAll();

        //Assert
        $this->assertEquals($result, [$test_student]);
    }
}



?>
