<?php 
    //Connexion à la base de données
    $con = mysqli_connect("localhost","root","root","Ws_2");
    //gérer les accents et autres caractères français
    $req= mysqli_query($con , "SET NAMES UTF8");
    if(!$con){
        //si la connexion échoue , afficher :
        echo "Connexion échouée";
    }


?>