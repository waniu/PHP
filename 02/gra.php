<?php
    //Funkcja pozwalająca dokumentowi korzystać z sesji
    session_start();
    
    //Jeżeli zmienna sesyjna (flaga) nie jest ustawiona (nie istnieje) 
    if(!isset($_SESSION['zalogowany']))
    {
        //Przekierowanie użytkownika do pliku index.php
        header('Location: index.php');
        
        //Wstrzymanie dalszego wykonywania skryptu
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

<?php
    
    //Wyświetlanie zmiennych, zapisanych w globalnej tablicy asocjacyjnej, w odpowiednich miejscach
    echo "<p>Witaj ".$_SESSION['user'].'! [ <a href="logout.php">Wyloguj się!</a> ]</p>';
    echo "<p><b>Drewno</b>: ".$_SESSION['drewno'];
    echo " | <b>Kamień</b>: ".$_SESSION['kamien'];
    echo " | <b>Zboże</b>: ".$_SESSION['zboze']."</p>";
    echo "<p><b>E-mail</b>: ".$_SESSION['email'];
    echo "<br/><b>Dni premium</b>: ".$_SESSION['dnipremium']."</p>";
?>

</body>
</html>