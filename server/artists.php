<?php
/**
 * Created by PhpStorm.
 * User: Stijn
 * Date: 1-7-2015
 * Time: 13:34
 */

start();

function start()
{

    if ($_SERVER['REQUEST_METHOD'] == "GET") {

        if (!empty($_GET["info"])) {

            $info = $_GET['info'];
            $UriInput = explode('/', $info);


            if ($UriInput[0] == 'fav') {

                if (isset($UriInput[1])) {


                    echo $UriInput[1];

                } else {
                    getData(true, null);
                }
            } elseif (is_numeric($UriInput[0])) {


                getData(null, $UriInput[0]);
            } else {

                echo "error 404";
                http_response_code(404);
            }

        } else {


            getData(false, null);
        }


    }
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (!empty($_GET["info"])) {

            $info = $_GET['info'];
            $UriInput = explode('/', $info);


            if ($UriInput[0] == 'fav') {

                if(isset($_POST['fav'])) {

                    if ($_POST['fav'] == true) {

                        FavoriteTarget($UriInput[1], true);
                        http_response_code(201);

                    }
                }
                else{

                    http_response_code(405);
                }

            } elseif (is_numeric($UriInput[0])) {

                http_response_code(405);

            } else {
                echo 'you came in the wrong place';


            }
        } else {


            if (isset($_POST['name']) && isset($_POST['url']) && isset($_POST['main_genre'])) {


                if ($_POST['name'] != "" && $_POST['url'] != "" && $_POST['main_genre'] != '') {
                    $name = $_POST['name'];
                    $url = $_POST['url'];
                    $main_genre = $_POST['main_genre'];

                    addData($name, $url, $main_genre);
                } else {

                    http_response_code(400);
                }


            } else {

                if (file_get_contents('php://input') != null) {

                    $json = file_get_contents('php://input');
                    $newObject = json_decode($json);
                    //$newObject = $json;
                    //echo $json;
                    // echo $newObject;


                    if ($newObject != null) {


                        if (property_exists($newObject, 'url') && property_exists($newObject, 'name') && property_exists($newObject, 'main_genre')) {

                            //echo $newObject->url;
                            //echo $newObject->name;
                            //echo $newObject->main_genre;

                            $name = $newObject->name;
                            $url = $newObject->url;
                            $main_genre = $newObject->main_genre;

                            if ($name != "" && $url != "" && $main_genre != "") {

                                addData($name, $url, $main_genre);
                            } else {
                                http_response_code(400);
                            }


                        } else {

                            http_response_code(400);
                        }
                    } else {

                        http_response_code(444);
                    }


                } else {
                    http_response_code(400);
                }
            }
        }


    }
    if ($_SERVER['REQUEST_METHOD'] == "DELETE") {

        if (!empty($_GET["info"])) {

            $info = $_GET['info'];
            $UriInput = explode('/', $info);


            if ($UriInput[0] == 'fav') {

                    if(is_numeric($UriInput[1])){

                        FavoriteTarget($UriInput[1], false);
                    }
                else {
                    print json_encode(["message" => "Can't delete collection."]);
                    http_response_code(405);

                }
            } elseif (is_numeric($UriInput[0])) {

                deleteItem($UriInput[0]);

            } else {

                http_response_code(400);
                print json_encode(["message" => "Does not exist."]);



            }
        }
        else {

            print json_encode(["message" => "Can't delete collection."]);
            http_response_code(405);

        }
    }
    if ($_SERVER['REQUEST_METHOD'] == "PUT") {

        if (!empty($_GET["info"])) {

            $info = $_GET['info'];
            $UriInput = explode('/', $info);


            if ($UriInput[0] == 'popular') {

                http_response_code(405);

            }
            elseif (is_numeric($UriInput[0])) {

                $input = @file_get_contents('php://input');
                $array = json_decode($input, true);


                if (isset($array)) {


                    if (empty($array['id']) || empty($array['name']) || empty($array['url']) || empty($array['main_genre'])) {

                        http_response_code(400);
                        header('Content-Type : application/json');
                        echo json_encode(array("message" => "error - no empty values allowed"));
                    }
                    else {


                        editData($array['name'], $array['url'], $array['main_genre'], $UriInput[0]);


                    }


                }

                else {
                    http_response_code(400);
                    $json = json_encode(array("message" => "Incorrect format or empty values"));
                    header("Content-Type: application/json");
                    print $json;
                    exit();
                }
            }

            else {
                http_response_code(400);
                $json = json_encode(array("message" => "method not allowed (must have an ID)"));
                header("Content-Type: application/json");
                print $json;
                exit();

            }


        }

        else {

            http_response_code(405);
            $json = json_encode(array("message" => "method not allowed"));
            header("Content-Type: application/json");
            print $json;
            exit();

        }

    }



}

function FavoriteTarget($id, $status){

  //  $servername = "localhost";
  //  $username = "root";
  //  $password ="";
  //  $database = "webservice";


    //include ('dbConnect.php');

     $servername = "sql.cmi.hro.nl";
     $database = "0875013";
     $username = "0875013";
     $password = "7c4e3ec2";

// Create connection
    $conn = new mysqli($servername,$username, $password, $database);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    if($status){

        $sql = "UPDATE artists
            SET fav=1
            WHERE id='$id'";

    }
    else {

        $sql = "UPDATE artists
            SET fav=0
            WHERE id='$id'";

    }

    $query = $conn->query($sql);

    if($query){
        header('Content-Type: application/json');
        print json_encode(["message" => "Resource deleted"]);
        http_response_code(204);

    }
    else{

        http_response_code(404);
    }

    $conn->close();


}

function getData($popular, $id){


    $collection = true;

    $artists = array(

    );


    $servername = "sql.cmi.hro.nl";
    $database = "0875013";
    $username = "0875013";
    $password = "7c4e3ec2";


  //  $servername = "localhost";
   // $username = "root";
   // $password ="";
   // $database = "webservice";

    //include ('dbConnect.php');
// Create connection
    $conn = new mysqli($servername,$username, $password, $database);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if(isset($popular)){

        //echo 'popular is not null';
        //echo '<br>';
        if ($popular){
            //echo 'is popular';
            $sql = "SELECT * FROM artists WHERE fav = 1";

        }
        else{
            //echo 'is not popular';
            $sql = "SELECT * FROM artists";
        }

    }
    else{
        if($id != null){

            $sql = "SELECT * FROM artists WHERE id = $id";
        }
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0){
        while($row = $result->fetch_assoc()){

            if ($id == null) {

                $collection = true;

                $artist = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'main_genre' => $row['main_genre'],
                    'fav' => $row['fav'],
                    'url' => $row['url']
                );
            }
            else{

                $collection = false;

                $stars =  ($row['total_votes'] > 0) ? round($row['total_stars']/$row['total_votes'], 1): 0;

                $artist = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'main_genre' => $row['main_genre'],
                    'fav' => $row['fav'],
                    'url' => $row['url']
                    );


            }


            array_push($artists, $artist);
        }
    }
    else{
        http_response_code(404);
        exit;

    }



    $conn->close();

    if($collection == false) {


            echo json_encode($artist);

        }
    if($collection == true){

        echo json_encode($artists);
    }
       }

function addData($name, $url, $main_genre){

    /*$servername = "localhost";
    $username = "root";
    $password ="";
    $database = "webservice";*/

        $servername = "sql.cmi.hro.nl";
        $database = "0875013";
        $username = "0875013";
        $password = "7c4e3ec2";

    //include ('dbConnect.php');

// Create connection
    $conn = new mysqli($servername,$username, $password, $database);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO artists (name, url, main_genre)
            VALUES ('$name', '$url', '$main_genre')";


    $query = $conn->query($sql);


    if($query) // will return true if succefull else it will return false
    {
        http_response_code(201);
        $sql = "SELECT * FROM artists WHERE name = '$name'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0){
            while($row = $result->fetch_assoc()) {

                $stars =  ($row['total_votes'] > 0) ? round($row['total_stars']/$row['total_votes'], 1): 0;


                header("Content-Type: application/json");
                echo json_encode(array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'main_genre' => $row['main_genre'],
                    'url' => $row['url']
                    /*'rating' => array(
                        'total_stars' => $row['total_stars'],
                        'total_votes' => $row['total_votes'],
                        'stars' => $stars
                    ),*/


                ));

            }

        }






// code here
    }
    else{
        http_response_code(400);

        echo'something went wrong';
        echo $conn->error;
    }

    $conn->close();


}

function editData($name, $url, $main_genre, $id){


    $id = (int)$id;
    $servername = "sql.cmi.hro.nl";
    $database = "0875013";
    $username = "0875013";
    $password = "7c4e3ec2";

    /*$servername = "localhost";
    $username = "root";
    $password ="";
    $database = "webservice";*/


    // Create connection
    $conn = new mysqli($servername,$username, $password, $database);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }



    $sql = "UPDATE artists
            SET name='$name',url='$url', main_genre='$main_genre'
            WHERE id='$id'";

    $query = $conn->query($sql);


    if($query){

        http_response_code(200);

        $sql = "SELECT * FROM artists WHERE id = '$id'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0){
            while($row = $result->fetch_assoc()) {

                $stars =  ($row['total_votes'] > 0) ? round($row['total_stars']/$row['total_votes'], 1): 0;


                header("Content-Type: application/json");
                echo json_encode(array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'main_genre' => $row['main_genre'],
                    'url' => $row['url']
                    /*'rating' => array(
                        'total_stars' => $row['total_stars'],
                        'total_votes' => $row['total_votes'],
                        'stars' => $stars
                    ),*/

                ));

            }

        }



    }
    else{

        http_response_code(400);
    }

}

function deleteItem($id){

   /* $servername = "localhost";
    $username = "root";
    $password ="";
    $database = "webservice";*/


    //include ('dbConnect.php');

     $servername = "sql.cmi.hro.nl";
     $database = "0875013";
     $username = "0875013";
     $password = "7c4e3ec2";

// Create connection
    $conn = new mysqli($servername,$username, $password, $database);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM artists
            WHERE id='$id'";


    $query = $conn->query($sql);

    if($query){
        header('Content-Type: application/json');
        print json_encode(["message" => "Resource deleted"]);
        http_response_code(204);

    }
    else{

        http_response_code(404);
    }

    $conn->close();

}

?>