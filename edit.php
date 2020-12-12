<?php
require_once "pdo.php";
session_start();
if (isset($_POST['delete'])) {
    unset($_SESSION['success']);
    header('Location: index.php');
    return;
}
if (
    isset($_POST['make']) && isset($_POST['year'])
    && isset($_POST['model']) && isset($_POST['mileage']) && isset($_POST['autos_id'])
) {

    // Data validation
    if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?autos_id=" . $_POST['autos_id']);
        return;
    }

    if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = 'year and mileage must numeric';
        header("Location: edit.php?autos_id=" . $_POST['autos_id']);
        return;
    }

    $sql = "UPDATE autos SET make = :make,
            mileage = :mileage, model = :model, year = :year
            WHERE autos_id = :autos_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':mileage' => $_POST['mileage'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':autos_id' => $_POST['autos_id']
    ));
    $_SESSION['success'] = 'Record updated';
    header('Location: index.php');
    return;
}

// Guardian: Make sure that user_id is present
if (!isset($_GET['autos_id'])) {
    $_SESSION['error'] = "Missing user_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header('Location: index.php');
    return;
}

// Flash pattern
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . $_SESSION['error'] . "</p>\n";
    unset($_SESSION['error']);
}

$m = htmlentities($row['make']);
$y = htmlentities($row['year']);
$mo = htmlentities($row['model']);
$mile = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HAJER GAFSI</title>
</head>

<body>
    <h1>Edit auto</h1>
    <form method="post">
        <p>Make:
            <input type="text" name="make" value="<?= $m ?>"></p>
        <p>Year:
            <input type="text" name="year" value="<?= $y ?>"></p>
        <p>Model:
            <input type="text" name="model" value="<?= $mo ?>"></p>
        <p>Mileage:
            <input type="text" name="mileage" value="<?= $mile ?>"></p>
        <input type="hidden" name="autos_id" value="<?= $autos_id ?>">
        <p><input type="submit" value="Save" />
            <input type="submit" name="cancel" value="Cancel"></p>
    </form>

</body>

</html>