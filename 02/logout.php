<?php
    //Funkcja pozwalająca dokumentowi korzystać z sesji
    session_start();
    
    //Funkcja niszcząca wszystkie zmienne sesyjne
    session_unset();
    
    //Przekierowanie użytkownika do pliku index.php
    header('Location: index.php');
?>