<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    die("<div style='text-align: center; position: absolute;left: 50%;top: 50%;transform:translate(-50%,-50%);'> <span style='font-size:100px;'> &#x26d4;</span> <p> Accés refusé ! <br> <br> veuillez <a href=\"./login.php\">vous connectez</a> ou <a href=\"./signUp.php\">enregistrez vous</a>  <br> <br> pour utiliser cette application</p></div>");
}

require_once 'pdo.php';
require_once 'functions.php';


if (isset($_POST["ajouter"])) {
    if (!empty($_POST["addTache"])) {
        if (strlen($_POST["addTache"]) > 2) {
            $sql = "INSERT INTO tasks (title, user_id) VALUES (:new, :user_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':new', htmlentities($_POST["addTache"]));
            $stmt->bindValue(':user_id', $_SESSION["user_id"]);
            $inserted = $stmt->execute();
            $_SESSION["success"] = "Tâche Ajoutée <span style='font-size:20px;'>&#128077;</span>";
            header("location: app.php");
            return;
        }
    } else {
        $_SESSION["warning"] = "Veuillir remplir ce champ <span style='font-size:20px;'>&#128530;</span>";
        header("location: app.php");
        return;
    }
}

if (isset($_POST["id"]) && isset($_POST["action"])) {
    if ($_POST["action"] == "delete") {
        $sql = "DELETE FROM tasks WHERE task_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $_POST["id"]);
        $delete = $stmt->execute();
        $_SESSION["warning"] = "Tâche Supprimée";
        header("location: app.php");
        return;
    }
}

if (isset($_POST["edit"])) {
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['task_id'] = $_POST['task_id'];
    header("Location: editApp.php");
}


$sql = "SELECT * FROM tasks WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":user_id" => $_SESSION["user_id"]]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="./CSS/main.css">
    <script src="https://kit.fontawesome.com/daed954e81.js" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo_App</title>
</head>

<body>
    <div class="seDeconecter">
        <a type="submit" class="btnSecond" href="./logOut.php" name="quitter">Se deconnecter</a>
    </div>
    <div class="container">
        <h1 class="">To Do App <br> of <?php echo $_SESSION["user_id"] ?></h1>
        <div class="underline"></div>
        <form class="form-app" action="app.php" method="POST">
            <div>
                <p>

                    <?php if (isset($_SESSION['flash_message'])) {
                        $message = $_SESSION['flash_message'];
                        echo  "<div class='success' role='alert'> $message </div>";
                        unset($_SESSION['flash_message']);
                    } ?>
                    <?php if (isset($_SESSION['success'])) {
                        echo  "<div class='success' role='alert'> {$_SESSION['success']}  </div>";
                        unset($_SESSION['success']);
                    } ?>
                    <?php if (isset($_SESSION["success"])) {
                        $message = $_SESSION["success"];
                        echo  "<div class='success' role='alert'> $message </div>";
                        unset($_SESSION["success"]);
                    } ?>
                    <?php if (isset($_SESSION["warning"])) {
                        $message = $_SESSION["warning"];
                        echo  "<div class='error-in-app' role='alert'> $message </div>";
                        unset($_SESSION["warning"]);
                    } ?>
                </p>
            </div>
            <div class="form-group">
                <!-- <div class="inputNewTask"> -->
                <input type="text" class="inputData" placeholder="Ajoute une tâche" name="addTache">
                <!-- </div> -->
                <!-- <div class="btn-form"> -->
                <button class="btnFirst" name="ajouter" type="submit">Ajouter</button>
                <!-- </div> -->
            </div>
        </form>
        <table border="true">
            <tbody>
                <?php
                foreach ($rows as $row) {
                    $tache = ' 
                <tr>
                
                <div class="container-task">
                <p class="align-self-end h6 ml-1">' . $row['title'] . '</p>
                            
                <div class="listBtn">
                <form action="app.php" method="POST">
                <input type="text" name="action" value="delete" hidden>
                <input type="text" value="' . $row['task_id'] . '" name="id" hidden>
                <button class="btn-delete" name="delete" title="Supprimer"><i class="fa-solid fa-trash"></i></button>
                </form>
                            
                <form action="app.php" method="POST">
                <input type="text" name="action" value="edit" hidden>
                <input type="text" value="' . $row['title'] . '" name="title"  hidden>
                <input type="text" value="' . $row['task_id'] . '" name="task_id"  hidden>
                <button name="edit" class="btn-edit" title="Editer"><i class="fa-solid fa-pen-to-square"></i></button>
                </form>
                </div>
                </div>  
                <tr/>
                ';
                    echo "<div class='separateur'></div> $tache ";
                } ?>
            </tbody>
        </table>

    </div>
</body>

</html>