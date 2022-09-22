<?php
session_start();

include "./pdo.php";

require_once 'functions.php';


if (isset($_POST["register"])) {

    if (isset($_POST["email"]) && isset($_POST["pass"]) && isset($_POST["confirmPass"])) {

        if (!empty($_POST["email"]) && !empty($_POST["pass"]) && !empty($_POST["confirmPass"])) {

            $username = $_POST['email'];
            $stmt = $pdo->prepare("SELECT * FROM users WHERE name=?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if (!preg_match(" /^.+@.+\.[a-zA-Z]{2,}$/ ", $_POST["email"])) {
                $message =  "L'adresse E-mail est invalide";
            } elseif ($_POST["pass"] !== $_POST["confirmPass"]) {
                $_SESSION["error"] = "Veuillez renseignez le même mot de passe dans les champs correspondants";
                header("location: signUp.php");
                return;
            } elseif ($user) {
                $_SESSION["error"] = "Email déja utilisé, pour se connecter veuillez suivre ce lien <a class='btn btn-info btn-sm' href='./login.php'>Login</a>";
                header("location: signUp.php");
                return;
            } else {
                $codeVerify = htmlentities($_POST["pass"]);
                $emailVerify = htmlentities($_POST["email"]);

                // $codeVerify = ($_POST["pass"]);
                // $emailVerify = ($_POST["email"]);

                $sql = "INSERT INTO users (name,password) VALUE (:name, :password)";
                $codeHash = hash("md5", "$salt" . "$codeVerify");

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    ":name" => $emailVerify,
                    ":password" => $codeHash
                ]);

                header("Location: ../index.php");
                unset($_SESSION);
                return;
            }
        } else {
            $_SESSION["error"] = "l'email et le mot de passe sont requis, Merci de remplir tout les champs";
            header("location: signUp.php");
            return;
        }
    }
}


if (isset($_POST['quitter'])) {
    header('location: ../index.php');
    session_destroy();
    return;
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/main.css">
    <title>S'enregistrer</title>
</head>

<body>
    <div class="container signUp">
        <h2>Enregistrez Vous</h2>
        <div class="form-group">
            <?php if (isset($_SESSION['error'])) {
                $message = $_SESSION['error'];
                echo "<div class='alert alert-danger' role='alert'>$message</div>";
                unset($_SESSION['error']);
            } ?>
            </p>
        </div>
        <form class="form" action="./signUp.php" method="POST">
            <div class="form-group">
                <label for="focusInputEmail"> Adresse Electronique</label>
                <input type="email" name="email" class="inputData" id="focusInputEmail" aria-describedby="emailHelp"
                    placeholder=" Entez votre email">
            </div>
            <div class="form-group">
                <label for="focusInputPassW"> Mot de Passe</label>
                <input type="password" name="pass" class="inputData" id="focusInputPassW" placeholder=" Mot de passe">
                <small>pour votre sécurité veuillez utiliser un mot de passe
                    fort</small>
            </div>
            <div class="form-group">
                <label for="focusInputPassW2"> Confirmer le mot de passe :</label>
                <input type="password" name="confirmPass" class="inputData" placeholder=" Confirmez mot de passe"
                    id="focusInputPassW2">
            </div>
            <div class="container-btns">
                <button type="submit" class="btnFirst" name="register">S'enregistrer</button>
                <button type="submit" class="btnSecond espace" href="../index.php" name="quitter">Annuler</button>
            </div>
        </form>
    </div>
</body>

</html>