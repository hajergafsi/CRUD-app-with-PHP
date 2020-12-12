<?php
require_once('pdo.php');
session_start();
$_SESSION['error'] = '';
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
$email_char = '@';
if (isset($_SESSION['name']) && isset($_SESSION['pass'])) {

    if (strlen($_SESSION['name']) < 1 || strlen($_SESSION['pass']) < 1) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        return;
    } else if (strpos($_SESSION['name'], $email_char) == 0) {

        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    } else {
        $check = hash('md5', $salt . $_SESSION['pass']);
        if ($check == $stored_hash) {
            unset($_SESSION['pass']);
            // Redirect the browser to game.php
            header("Location: index.php");
            return;
        } else {
            error_log("Login fail " . $_SESSION['name'] . " $check");
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
        }
    }
}


?>
<!DOCTYPE html>
<html>

<head>
    <title>Hajer Gafsi - Autos Database</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
        integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <h1>Welcome to Autos Database</h1>
        <div class="container">
            <h2>Automobiles</h2>
            <?php
            if (isset($_SESSION['name'])) {
                if (isset($_SESSION['success'])) {
                    echo ('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
                    unset($_SESSION['success']);
                }

                echo '<table border="1"><tr>';
                echo '<tr> <th>Year</th> <th>Make</th><th>Mileage</th><th>Model</th><th>Action</th></tr>';
                $stmt = $pdo->query('SELECT * FROM autos');
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<td>' . $row['year'] . '</td><td>' . $row['make'] . '</td><td>' . $row['mileage'] . '</td><td>'
                        . $row['model'] . '</td><td>' . '<a href="edit.php?autos_id=' .  $row['autos_id'] . '">Edit</a>/<a href="delete.php?autos_id=' .  $row['autos_id'] . '">Delete</a>' . '</td></tr>';
                }

                echo '</table><br>';
                echo ' <a href="add.php"> Add New Entry </a> | <br> ';
                echo ' <a href="logout.php">Logout</a> ';
            } else {
                echo  '<p><a href="login.php">Please log in</a></p>';
                echo '<p>Attempt to <a href="add.php">add data</a> without logging in</p>';
            }
            ?>
        </div>




</body>