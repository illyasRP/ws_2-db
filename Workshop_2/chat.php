<?php 
  //démarer la session
  session_start();
  if(!isset($_SESSION['users'])){
      // si l'utilisateur n'est pas connecté
     // redirection vers la page de connexion
     header("location:index.php");
  }
  $users = $_SESSION['users'] ;


$db = new PDO('mysql:host=localhost;dbname=ws_2', 'root', 'root');
$db->exec('SET NAMES "UTF8"');

$sql = 'SELECT * FROM `users`';

// On prépare la requête
$query = $db->prepare($sql);

// On exécute la requête
$query->execute();

// On stocke le résultat dans un tableau associatif
$result = $query->fetchAll();

// $action_done = isset($_POST['action']) && $_POST['action'] == 'changer';

require_once('close.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?=$users?> | CHAT</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="chat">
        <div class="button-email">
            <span> <?=$users?> </span>
            <a href="deconnexion.php" class="Deconnexion_btn">Déconnexion</a>
            <a href="creat.php" class="Deconnexion_btn">new ticket</a>
        </div>
        <!-- messages -->
        <div class="messages_box"> Chargement ...</div>
        <!-- Fin messages -->

        <?php 
           //envoi des messages
           if(isset($_POST['send'])){
               // recuperons le message
               $message = $_POST['message']; 
               //connexion à la base de donnée
               include("db_conn.php");
               //verifions si le champs n'est pas vide
               if(isset($message) && $message != ""){
                   //inserer le message dans la base de données
                   $req = mysqli_query($con , "INSERT INTO messages VALUES (NULL ,'$users','$message',NOW())");
                   //on actualise la page
                   header('location:chat.php');
                }else {
                    // si le message est vide , on actualise la page
                    header('location:chat.php');
                }
                
            }
            ?>
        <form action= "" class="send_message" method="POST">
             <textarea name="message" cols="30" rows="2" placeholder="Votre message"></textarea>
             <input type="submit" value="Envoyé" name="send">
        </form>
    </div>

    <div class="tickets">
        <table class="table">
            <thead>
            <th>userame</th>
            <th>email</th>
        </thead>
        <?php
                        // On boucle sur la variable result
                        foreach($result as $users){
                        ?>
        <tbody class="solo">
            <a class="btn btn-warning" href="chat.php?id=<?= $users['id_m'] ?>">
            <tr>
            <td><?= $users['uname'] ?></td>
            <td><?= $users['email'] ?></td>
            </tr>
        </a>
        </tbody>
   
        <?php
                        }
                        ?>

        </table>
    <script>
        // On actualise automatique le chat en utilisant AJAX
        var message_box = document.querySelector('.messages_box');
        setInterval(function(){
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    message_box.innerHTML = this.responseText;
                }
            };
            xhttp.open("GET","messages.php" , true); // récupération de la page message
            xhttp.send()
        },500) // Actualiser le chat tous les 500 ms
    </script>
</body>
</html>