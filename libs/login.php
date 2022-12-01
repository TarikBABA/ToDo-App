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
                $_SESSION['name'] = $user['name'];
                header("location: app.php");
                return;
            } else {
                $_SESSION["message"] = "mot de passe incorrect";
                header("location: login.php");
                return;
            }
        } else {
            $_SESSION["message"] = "Email invalide, pour s'enregistrer veuillez suivre ce lien <a class='link-redirection' href='./signUp.php'>SignUp</a>";
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
<html lang="fr">

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
        <div class="underline"></div>
        <div class="form-group">
            <p>
                <?php if (isset($_SESSION['message'])) {
                    echo  "<div class='error' role='alert'> {$_SESSION['message']} </div>";
                    unset($_SESSION['message']);
                } ?>
            </p>
        </div>
        <form class="form" method="POST">
            <div class="form-group">

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
</body>

</html>