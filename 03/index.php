<?php
    //Funkcja pozwalająca dokumentowi korzystać z sesji
    session_start();
    
    //Jeżeli istnieje zmienna sesyjna (flaga) 'zalogowany' i jest ustawiona na 'true'
    if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
    {
        //Przekierowanie użytkownika do pliku "gra.php"
        header('Location: gra.php');
        
        //Funkcja kończąca przetwarzanie pliku "index.php" w tym miejscu
        exit();
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

    Tylko martwi ujrzeli koniec wojny - Platon
    <br/><br/>
    
    <!--Link do rejestrcji-->
    <a href="rejestracja.php">Rejestracja - załóż darmowe konto!</a>
    <br/><br/>
    
    <!--Formularz przesyłający login i hasło, do pliku "zaloguj.php", metodą post-->
    <form action="zaloguj.php" method="post">
    
    Login: <br/> <input type="text" name="login"/><br/>
    Haslo: <br/> <input type="password" name="haslo"/><br/><br/>
    <input type="submit" value="Zaloguj się"/>
    
    </form>
    
    <?php
        
        //Jeżeli zmienna "$_SESSION['blad']" istnieje (isset = ustawiona)
        if(isset($_SESSION['blad']))
        {
            //Pokaż na ekranie treść zmiennej $_S...
            echo $_SESSION['blad'];
        }
        
        unset($_SESSION['blad']);
    ?>
    
</body>
</html>