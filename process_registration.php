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
    $varos = $_POST["varos"];
    $pass2 = $_POST["confirm_password"];
    
    if (strcmp($password,$pass2<>0)) { print "<h1>A két jelszó nem egyezik</h1>";
    } else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    //felhasznalo letezesenek vizsgalata
    $sql = "SELECT username FROM users WHERE username='$username'; ";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        print "<h1>Már van ilyen felhasználó!</h1>";
    } else {
        $sql = "INSERT INTO users (username, pass) VALUES ('$username', '$hashedPassword')";

        if ($conn->query($sql) === TRUE) {
            } else {
            echo "Hiba a regisztráció során: " . $conn->error;
            }
        

    //jelentkezes tarolasa  
    $sql = "SELECT id FROM users WHERE username='$username'; ";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $userid=$row["id"];
        $sql = "INSERT INTO jelenkezesek (training_id, user_id) VALUES ('$varos', '$userid')";
        if ($conn->query($sql) === TRUE) {
            print "<h1>Sikeres regisztráció és jelentkezés.</h1>";
        } else {
            echo "Hiba a alkalom tárolása során: " . $conn->error;
            }

    } else {
        print "<h1>Adatbázishiba. Értesítse a fejlesztőt.</h1>";
        
        } 
    }
}
}



$conn->close();
?>

