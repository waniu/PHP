<?php
    //Funkcja pozwalająca dokumentowi korzystać z sesji
    session_start();
    
    //Jeżeli nie istnieje zmienna sesyjna (flaga) 'udana_rejestracja'
    if(!isset($_SESSION['udana_rejestracja'])) 
    {
        //Przekierowanie użytkownika do pliku "index.php"
        header('Location: index.php');
        
        //Funkcja kończąca przetwarzanie pliku "witamy.php" w tym miejscu
        exit();
    }
    
    //Jeżeli zmienna sesyjna "udana_rejestracja" istnieje i użytkownik dostał się tutaj z formularza rejestracji
    else
    {
        unset($_SESSION['udana_rejestracja']);
    }
?>
<!DOCUMENT HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE-edge,chrome=1"/>
    <title>Osadnicy - gra przeglądarkowa</title>
</head>
<body>

    Dziękujemy za rejestrację w serwisie! Możesz już zalogować się na swoje konto!
    <br/><br/>
    
    <!--Link do panelu logowania-->
    <a href="index.php">Zaloguj się na swoje konto!</a>
    <br/><br/>
    
</body>
</html>