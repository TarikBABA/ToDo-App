<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Veuillez vous connecter");
}

if (!isset($_SESSION["user_id"])) {
    die("Veuillez vous connecter");
}

require_once("pdo.php");


if (isset($_POST["edit"]) && isset($_POST['tacheModif'])) {

    if (!empty($_POST['tacheModif'])) {

        if ($_SESSION["title"] == $_POST['tacheModif']) {
            header("Location: app.php");
            return;
        } else {

            // $sql = "UPDATE `tasks` SET `title` = :tacheModif WHERE `tasks`.`task_id` = :task_id";
            // $stmt = $pdo->prepare($sql);
            // $stmt->execute([
            //     ":tacheModif" => htmlentities($_POST['tacheModif']),
            //     ":task_id" => $_SESSION["task_id"],
            // ]);

            // $sql = "UPDATE `tasks` SET `title` = :tacheModif WHERE `tasks`.`task_id` = :task_id";

            $sql = "UPDATE `tasks` SET `title` = :tacheModif, `Date_De_Modification` = CURRENT_TIMESTAMP WHERE `tasks`.`task_id` = :task_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":tacheModif" => htmlentities($_POST['tacheModif']),
                ":task_id" => $_SESSION["task_id"],
            ]);


            $_SESSION["success"] = "tâche modifiée ! <span style='font-size:20px;'>&#128077;</span>";
            header("Location: app.php");
            return;
        }
    } else {

        $_SESSION['error'] = "Champs vide ! <span style='font-size:20px;'>&#128530;</span>";
        // header("Location: editApp.php?todos_id=" . $_REQUEST['id']);
        header("Location: editApp.php");
        return;
    }
}

$stmt = $pdo->prepare("SELECT title FROM tasks WHERE user_id = :user_id");
$stmt->execute([":user_id" => $_SESSION["user_id"]]);
$rows = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="./CSS/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editer</title>
</head>

<body>
    <div class="bgdc pt-5">
        <form class="form" method="POST">
            <div class="title text-center p-2">
                <h2>Éditer Une Tâche</h2>
            </div>
            <p>
                <?php if (isset($_SESSION["error"])) {
                    $message = $_SESSION["error"];
                    echo  "<div class='text-center alert alert-warning' role='alert'> $message </div>";
                    unset($_SESSION["error"]);
                } ?>
            </p>
            <div class="input-group m-3">

                <input type="text" name="tacheModif" class="form-control" value="<?= $_SESSION["title"] ?>">
                <div class="input-group-append">
                    <input class="btn btn-info" name="edit" type="submit" value="Éditer"></input>
                </div>
            </div>
        </form>
        <div class=" text-center">
            <a href="./app.php" class="btn btn-outline-dark">Annuler</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>