<?php
session_start();

include "pdo.php";
require 'functions.php';

$_SESSION['flash_message'] = "Bonjour et Bienvenue Dans Votre Espace Privé <span style='font-size:20px;'>&#128521;</span>";

if (isset($_POST['valider'])) {

    $username = htmlentities($_POST["email"]);
    $password = htmlentities($_POST["pass"]);

    if ((isset($username) && strlen($username) > 0) && (isset($password) && strlen($password) > 0)) {
        $codeAfterHash = hash("md5", "$salt" . "$password");
        $stmt = $pdo->prepare("SELECT * FROM users WHERE name=:name");
        $stmt->execute([
            ':name' => $username
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            if ($codeAfterHash == $user['password']) {
                $_SESSION['user_id'] = $user['user_id'];

                header("location: app.php");
                return;
            } else {
                $_SESSION["message"] = "mot de passe incorrect";
                header("location: login.php");
                return;
            }
        } else {
            $_SESSION["message"] = "Email invalide, pour se connecter veuillez suivre ce lien <a class='link-redirection' href='./signUp.php'>SignUp</a>";
            header("location: login.php");
            return;
        }
    } else {
        $_SESSION["message"] = "le nom d'utilisateur et le mot de passe sont requis";
        header("location: login.php");
        return;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/main.css">
    <title>Se Connecter</title>
</head>

<body>
    <div class="container">
        <h1>Se Connecter</h1>
        <form class="form" method="POST">
            <div class="form-group">
                <?php if (isset($_SESSION['message'])) {
                    echo  "<div class='text-center alert alert-danger' role='alert'> {$_SESSION['message']} </div>";
                    unset($_SESSION['message']);
                } ?>
                <label for="focusInputEmail">Adresse Electronique</label>
                <input type="email" name="email" class="inputData" id="focusInputEmail" aria-describedby="emailHelp"
                    placeholder="Entez votre email">
            </div>
            <div class="form-group">
                <label for="focusInputPassW">Mot de Passe</label>
                <input type="password" name="pass" class="inputData" id="focusInputPassW" placeholder="Mot de passe">
            </div>
            <div class="container-btns login">

                <button type="submit" class="btnFirst" name="valider">Se Connecter</button>
                <div class="linkQuitter">

                    <a class="btnSecond" href="../index.php" name="quitter">quitter</a>
                </div>
            </div>


        </form>
    </div>

    <script src=" https://code.jquery.com/jquery-3.2.1.slim.min.js"
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