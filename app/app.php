<?php
    date_default_timezone_set("America/Los_Angeles");
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Student.php";
    require_once __DIR__."/../src/Course.php";
    require_once __DIR__."/../src/phpflickr-master/phpFlickr.php";


    $app = new Silex\Application();
    $server = 'mysql:host=localhost:8889;dbname=school';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    $app->register(new Silex\Provider\TwigServiceProvider(), ["twig.path" => __DIR__."/../views"]);
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();
    $app['debug'] = true;


    $app->get('/', function() use($app) {
        $students = Student::getAll();
        $courses = Course::getAll();
        $api_key = 'e4d50be66420e011afd6d560e5d7746e';

        $word_url = 'http://randomword.setgetgo.com/get.php';
        $word_response = file_get_contents($word_url);

        $tag = $word_response;
        $perPage = 1;
        $url = 'https://api.flickr.com/services/rest/?method=flickr.photos.search';
        $url.= '&api_key='.$api_key;
        $url.= '&tags='.$tag;
        $url.= '&per_page='.$perPage;
        $url.= '&format=json';
        $url.= '&nojsoncallback=1';

        $response = json_decode(file_get_contents($url));
        $photo_array = $response->photos->photo;


        foreach($photo_array as $single_photo){

            $farm_id = $single_photo->farm;
            $server_id = $single_photo->server;
            $photo_id = $single_photo->id;
            $secret_id = $single_photo->secret;
            $size = 'm';

            $title = $single_photo->title;

            $photo_url = 'https://farm'.$farm_id.'.staticflickr.com/'.$server_id.'/'.$photo_id.'_'.$secret_id.'_'.$size.'.'.'jpg';

            print "<img title='".$title."'alt='".$word_response."' src='".$photo_url."' />";
        }
        return $app["twig"]->render("root.html.twig", ['students'=>$students,'courses'=>$courses]);
    });

    $app->post('/addstudent', function() use($app) {
        $new_student = new Student($_POST['name']);
        $new_student->save();
        return $app->redirect("/");
    });

    $app->post('/addcourse', function() use($app) {
        $new_course = new Course($_POST['name']);
        $new_course->save();
        return $app->redirect("/");
    });

    $app->get('/student/{id}', function($id) use($app) {
        $student = Student::find($id);
        $courses = Course::getAll();
        return $app['twig']->render("student.html.twig", ['student'=>$student,'courses'=>$courses]);
    });

    $app->patch('/student/{id}/edit', function($id) use($app) {
        $student = Student::find($id);
        $student->update($_POST['name']);
        $student->dropCourses();
        $course_number = count(Course::getAll());
        for($i=1;$i<=$course_number;$i++){
            if(!empty($_POST[$i])) {
                $new_course = Course::find($i);
                $student->addCourse($new_course);
            }
        }
        $courses = Course::getAll();

        return $app->redirect("/student/".$id);
    });

    $app->get('/course/{id}', function($id) use($app) {
         $course = Course::find($id);
         $students = Student::getAll();
        return $app['twig']->render("course.html.twig", ['students'=>$students,'course'=>$course]);
    });

    $app->patch('/course/{id}/edit', function($id) use($app) {
        $course = Course::find($id);
        $course->update($_POST['name']);
        $course->dropStudents();
        $student_number = count(Student::getAll());
        for($i=1;$i<=$student_number;$i++){
            if(!empty($_POST[$i])) {
                $new_student = Student::find($i);
                $course->addStudent($new_student);
            }
        }
        $students = Student::getAll();

        return $app->redirect("/course/".$id);
    });

    return $app;
?>
