<?php
require_once 'pdo.php';
session_start();
if (!isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}
if (isset($_POST['logout'])) {
    header("Location: index.php");
    return;
}
if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']) && isset($_POST['model'])) {
    $_SESSION['make'] = $_POST['make'];
    $_SESSION['year'] = $_POST['year'];
    $_SESSION['mileage'] = $_POST['mileage'];
    $_SESSION['model'] = $_POST['model'];
    header("Location: add.php");
    return;
}
if (isset($_SESSION['make']) && isset($_SESSION['year']) && isset($_SESSION['mileage']) && isset($_SESSION['model'])) {

    if (((strlen($_SESSION['make']) < 1) || (strlen($_SESSION['model']) < 1))) {
        $_SESSION['error'] = 'All fields are required !';
        unset($_SESSION['make']);
    } else if (!is_numeric($_SESSION['year']) || !is_numeric($_SESSION['mileage'])) {
        $_SESSION['error'] = 'year and mileage must numeric';
        unset($_SESSION['make']);
    } else {
        $sql = "INSERT INTO autos (make,model,year,mileage) VALUES (:make,:model,:y,:mileage)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':make' => $_SESSION['make'],
            ':model' => $_SESSION['model'],
            ':y' => $_SESSION['year'],
            ':mileage' => $_SESSION['mileage']
        ));
        unset($_SESSION['make']);
        $_SESSION['success'] = "Record added";
        header("Location: index.php");
        return;
    }
}


?>
<!DOCTYPE html>
<html>

<head>
    <title>Hajer Gafsi 's Automobile Tracker</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
        integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>

<body>
    <div class="container">
        <h1>Tracking Autos for <a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                data-cfemail="680009020d1a281109"><?php echo htmlentities($_SESSION['name']); ?></a></h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <form method="post">
            <p>Make:
                <input type="text" name="make" size="60" /></p>
            <p>Model:
                <input type="text" name="model" /></p>
            <p>Year:
                <input type="text" name="year" /></p>
            <p>Mileage:
                <input type="text" name="mileage" /></p>

            <input type="submit" value="Add" name="Add" />
            <input type="submit" name="logout" value="Cancel" />
        </form>

    </div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>

</html>