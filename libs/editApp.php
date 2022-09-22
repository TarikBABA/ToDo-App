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
        header("Location: editApp.php");
        return;
    }
}

$stmt = $pdo->prepare("SELECT title FROM tasks WHERE user_id = :user_id");
$stmt->execute([":user_id" => $_SESSION["user_id"]]);
$rows = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/main.css">
    <title>Editer</title>
</head>

<body>
    <div class="container">
        <form class="form-edit form-app" method="POST">
            <div>
                <h2>Éditer Une Tâche</h2>
            </div>
            <div>

                <p>
                    <?php if (isset($_SESSION["error"])) {
                        $message = $_SESSION["error"];
                        echo  "<div class='error-in-app' role='alert'> $message </div>";
                        unset($_SESSION["error"]);
                    } ?>
                </p>
            </div>
            <div class="form-group-edit">

                <input type="text" name="tacheModif" class="inputEditTask" value="<?= $_SESSION["title"] ?>">

                <input class="btnFirst" name="edit" type="submit" value="Éditer"></input>

            </div>
        </form>
        <div class="redirection-app">
            <a href="./app.php" class="btnSecond">Annuler</a>
        </div>
    </div>
</body>

</html>