<?php
    
    //Funkcja pozwalająca dokumentowi korzystać z sesji
    session_start();
    
    //Jeżeli nie jest ustawiona zmienna "login" lub "haslo", w globalnej tablicy POST 
    if(!isset($_POST['login']) || (!isset($_POST['haslo'])))
    {
        //Przekierowanie użytkownika do pliku index.php
        header('Location: index.php');
        
        //Wstrzymanie dalszego wykonywania skryptu
        exit();
    }
    
    //Wyciąga dane, potrzebna do połączenia z bazą danych z pliku connect.php
    //reqiure - funkcja WYMAGA istnienia pliku | once - funkcja sprawdzi czy nie zostało to powtórzone w kodzie
    require_once "connect.php";
    
    //Połączenie z bazą danych, przy pomocy instancji, klasy MySQLi
    //@ wycisza kontrolę błędów
    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
    
    //connect_errno - atrybut obiektu $polaczenie
    //connect_errno = 0 oznacza, że ostatnia próba połączenia się z bazą zakończyła się sukcesem
    if($polaczenie->connect_errno!=0)
    {
        echo "Error: ".$polaczenie->connect_errno;
    }
    
    //Cały dalszy kod jest w części "else" ponieważ, albo mamy połączenie i jedziemy dalej, albo nie i tylko wyświetlamy błąd
    else
    {
        //Te zmienne, przechowują login i hasło, podane w formularzu, w pliku index.php
        $login = $_POST['login'];
        $haslo = $_POST['haslo'];
        
        //Funkcja przeprowadza sanityzację, tzn zamienia wszystki potencjalnie niebezpieczne znaki wpisane w formularzu, przez użytkownika, na encje HTML
        //ENT_QUOTES - zamienia na encje także cydzysłowia i apostrofy
        //UTF-8 - zestaw znaków używany na stronie
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        $haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");
        
        //Jeżeli rezultat zapytania to obiekt polaczenie i jego metoda o nazwie query (kwerenda - zapytanie do bazy)
        //Argumentem jest zapytanie do bazy danych
        //Ten if sprawdza cz w zapytaniu SQL nie ma błędów
        
        if($rezultat = @$polaczenie->query(
        
        //Funkacja "sprinf()" służy do pilnowania porządku 
        //"%s" mówi funkcji, że w tym miejscu pojawi się zmienna typu string. W ich miejsce wstawia kolejne argumenty podane po przecinku
        sprintf(
        
        //Zapytanie do bazy danych MySQL
        //Treść zapytania: Wybierz wszystkie kolumny, z tabeli uzytkownicy, w których user='%s' & pass='%s' przesłane metodą POST, z formularza
        //WAŻNE!! Całe zapytanie zapisujemy w cudzysłowie a zmnienne PHP, będące łańcuchami, w apostrofach
        "SELECT * FROM uzytkownicy WHERE user='%s' AND pass='%s'",
        
        //Funkcja chroniąca przed różnymi technikami wstrzykiwania SQL
        mysqli_real_escape_string($polaczenie, $login),
        mysqli_real_escape_string($polaczenie, $haslo))))
        {
            //Ta zmienna przechowuje ilość rekordów, pasujących do podanych danych
            $ilu_userow = $rezultat->num_rows;
            
            //Jeżeli użytkownik jest w bazie
            if($ilu_userow>0)
            {   
                //Flaga, że jesteśmy zalogowani
                $_SESSION['zalogowany'] = true;
            
                //Ta zmienna przechowuje wszystkie pobrane z bazy kolumny przy pomocy tablicy asocjacyjnej
                //Tablica asocjacyjna zamiast numerów używa nazw kolumn z tabeli w indeksach
                $wiersz = $rezultat->fetch_assoc();
                
                //$_SESSION - globalna tablica asocjacyjna zmiennych, dostępna dla wszystkich plików
                //Ta zmienna przechowuje informacje z tablicy $wiersz o indeksie 'id'
                $_SESSION['id'] = $wiersz['id'];
                $_SESSION['user'] = $wiersz['user'];
                $_SESSION['drewno'] = $wiersz['drewno'];
                $_SESSION['kamien'] = $wiersz['kamien'];
                $_SESSION['zboze'] = $wiersz['zboze'];
                $_SESSION['email'] = $wiersz['email'];
                $_SESSION['dnipremium'] = $wiersz['dnipremium'];
                
                //Usuwa zmienna $_SESSION['blad'] z sesji
                unset($_SESSION['blad']);
                
                //Czyści z pamięci serwera wszystkie rekordy zapisane w zmiennej $rezultat
                $rezultat->close();
                
                //Przekierowanie użytkownika do pliku gra.php
                header('Location: gra.php');
            }
            //Jeżeli użytkownika nie ma w bazie lub podano błędne dane
            else
            {
                //Informacja o błędzie, przechowana w zmiennej sesyjnej
                $_SESSION['blad'] = '<span style="color: red;">Nieprawidłowy login lub hasło!</span>';
                
                //Przekierowanie użytkownika do pliku index.php
                header('Location: index.php');
            }
        }
        
        //Metoda zamykająca połączenie
        $polaczenie->close();
    }
    
?>