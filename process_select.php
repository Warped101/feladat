<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "training_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["pass"];
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    //lekerdezzuk az admin jogosultsagot, es ha az van akkor listazzuk a tablat
    $sql = "SELECT username, pass, admin FROM users WHERE username='$username'; ";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $admine=$row["admin"];
        $jelszofromdb=$row["pass"];


      
        $isPasswordCorrect = password_verify($password, $jelszofromdb);
        if ($isPasswordCorrect === TRUE) {
           
            if ($admine == "1") {
                
                
                $sql = "SELECT training_name, count(training_id) as darab FROM jelenkezesek, trainings where training_id = trainings.id group by training_name; ";
                $result = $conn->query($sql);
                
                print "<h1>Jelentkezések száma az egyes képzésekre:</h1>";
                if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    print  $row["training_name"]. " - " . $row["darab"]. "</br>";
                    }
                }
            }
        }



 
    }
    
}



$conn->close();
?>

