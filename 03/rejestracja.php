<?php
    
    //Funkcja pozwalająca dokumentowi korzystać z sesji
    session_start();
    
    //Jeżeli zmienna istnieje, oznacza to, że formularz rejestracji został wyslany i należy poddać go walidacji
    if(isset($_POST['nick']))
    {
        //Flaga udanej walidacji
        $wszystko_OK = true;
        
        //-----------------------SPRAWDZANIE POPRAWNOŚCI NICKA------------------------------------------
        
        //Zmienna przechowująca podany w formularzu, przez użytkownika, nick 
        $nick = $_POST['nick'];
        
        //Jeżeli długość zmiennej "$nick" jest większa niż 20 lub mniejsza niż 3 znaki to flagę ustawiamy na false
        //strlen - string length - długość łańcucha - funkcja sprawdzająca długość łańcucha
        if((strlen($nick)<3) || (strlen($nick)>20))
        {
            //Flaga udanej walidacji przestawiona na "false"
            $wszystko_OK = false;
            
            //Zmienna sesyjna błędu, dotycząca liczby znaków w nicku
            $_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków!";
        }
        
        //Jeżeli zmienna "nick" zawiera inne znaki niż litery lub cyfry
        //ctype_alnum - check type alphanumeric - funkcja zwraca "false" jeżeli jej argument zawiera znaki inne niż alfanumeryczne
        if(ctype_alnum($nick)==false)
        {
            //Flaga udanej walidacji przestawiona na "false"
            $wszystko_OK = false;
            
            //Zmienna sesyjna błędu, dotycząca rodzaju użytych znaków w nicku
            $_SESSION['e_nick'] = "Nick może składać się tylko z liter i cyfr (bez polskich ogonków)!";
        }
        
        //-----------------------SPRAWDZANIE POPRAWNOŚCI EMAILA------------------------------------------
        
        //Zmienna przechowująca podany w formularzu, przez użytkownika, adres e-mail
        $email = $_POST['email'];
        
        //filter_var(zmienna, filtr) - funkcja filtrująca zmienną przy pomocy filtru podanego w drugim argumencie
        //Zmienna "$emailB" jest tworzona po przepuszczeniu zmiennej "$email" przez funkcję filtrującą
        //FILTER_SANITIZE_EMAIL jest to filtr usuwający wszystkie znaki niedozwolone w adresach e-mail
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        //Jeżeli adres e-mail po przefiltrowaniu z niedozwolonych znaków ma niepoprawną składnię lub jest różny od  podanego przez użytkownika
        if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB != $email))
        {
            //Flaga udanej walidacji przestawiona na "false"
            $wszystko_OK = false;
            
            //Zmienna sesyjna błędu w adresie email
            $_SESSION['e_email'] = "Podaj poprawny adres e-mail!";
        }
        
        //-----------------------SPRAWDZANIE POPRAWNOŚCI HASŁA------------------------------------------
        
        //Zmienne przechowujące podane w formularzu, przez użytkownika, hasło
        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];
        
        //Jeżeli zmienna "$hasło1" ma mniej niż 8 lub więcej niż 20 znaków
        //Sprawdzam tylko "$haslo1" ponieważ "$haslo2" musi być takie samo
        if((strlen($haslo1)<8) || (strlen($haslo1)>20))
        {
            //Flaga udanej walidacji przestawiona na "false"
            $wszystko_OK = false;
            
            //Zmienna sesyjna błędu, dotycząca liczby znaków w haśle
            $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków!";
        }
        
        //Jeżeli zmienna "$haslo1" nie jest równa zmiennej "$haslo2"
        if($haslo1!=$haslo2)
        {
            //Flaga udanej walidacji przestawiona na "false"
            $wszystko_OK = false;
            
            //Zmienna sesyjna błędu, dotycząca identyczności haseł
            $_SESSION['e_haslo'] = "Podane hasła muszą być identyczne!";
        }
        
        //-----------------------------------HASHOWANIE HASŁA--------------------------------------------
        
        //Zmienna przechowująca hasło po zahashowaniu
        //Funkacja hashująca, "password_hash" jako argumenty przyjmuje co hashujemy i w jaki sposób
        //Stała PASSWORD_DEFAULT oznacza "Użyj najsilniejszego algorytmu jaki jest dostępny"
        //Hashowanie hasła eliminuje potrzebę jego sanityzacji przed włożeniem do bazy danych
        $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
        
        //---------------------------SPRAWDZENIE AKCEPTACJI REGULAMINU-----------------------------------
        
        //Jeżeli NIE jest ustawiona zmienna "$_POST['regulamin]"
        //Checkbox ma tę właściwość, że albo zwraca wartość "on" albo zmienna nie istnieje
        if(!isset($_POST['regulamin']))
        {
            //Flaga udanej walidacji przestawiona na "false"
            $wszystko_OK = false;
            
            //Zmienna sesyjna błędu, dotycząca zaznaczenia checkboxa
            $_SESSION['e_regulamin'] = "Potwierdź akceptację regulamin!";
        }
        
        //------------------------"BOT OR NOT" CZYLI SPRAWDZANIE CAPTCHY---------------------------------
        
        //Secret key reCAPTCHA
        $sekret = "6Ld3bwwUAAAAAFw0QEbzbg0JeNI31Qo-gNSNepam";
        
        //Funkcja "file_get_contents()" zapisuje w zmiennej całą zawartość pliku o podnej ścieżce
        //Łączymy się z serwerami Google ponieważ to one weryfikują capche
        $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
        
        //Odpowiedź od Google przychodzi w formacie "JSON - JavaScript Object Notation"
        //Funkcja "json_decode" dekoduje przesłaną odpowiedź
        $odpowiedz = json_decode($sprawdz);
        
        if($odpowiedz->success==false)
        {
            //Flaga udanej walidacji przestawiona na "false"
            $wszystko_OK = false;
            
            //Zmienna sesyjna błędu, dotycząca zaznaczenia captchy
            $_SESSION['e_bot'] = "Potwierdź, że nie jesteś botem!";
        }
        
        //-------------------------ZAPAMIĘTYWANIE DANYCH W FORMULARU-------------------------------
        
        $_SESSION['fr_nick'] = $nick;
        $_SESSION['fr_email'] = $email;
        $_SESSION['fr_haslo1'] = $haslo1;
        $_SESSION['fr_haslo2'] = $haslo2;
        
        //Jeżeli istnieje zmienna "$_POST['regulamin']"
        if(isset($_POST['regulamin']))
        {
            //Stwórz zmienną sesyjną "$_SESSION['fr_regulamin']" i nadaj jej wartość "true"
            $_SESSION['fr_regulamin'] = true;
        }
        
        
        //------------------SPRAWDZANIE CZY UŻYTKOWNIKA NIE MA W BAZIE DANYCH----------------------
        
        //Wyciąga dane, potrzebna do połączenia z bazą danych z pliku connect.php
        //reqiure - funkcja WYMAGA istnienia pliku | once - funkcja sprawdzi czy nie zostało to powtórzone w kodzie
        require_once "connect.php";
        
        //Funkcja "mysqli_report" ustawia sposób raportowania błędów
        //Stała "MYSQLI_REPORT_STRICT" informuje PHP, że chcemy wyjątki zamiast ostrzeżeń
        mysqli_report(MYSQLI_REPORT_STRICT);
        
        //Spróbuj
        try
        {
            //Połączenie z bazą danych, przy pomocy instancji, klasy MySQLi
            $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
            
            //Jeżeli wystąpił błąd połączenia inny niż 0
            //connect_errno - atrybut obiektu $polaczenie
            //connect_errno = 0 oznacza, że ostatnia próba połączenia się z bazą zakończyła się sukcesem
            if($polaczenie->connect_errno!=0)
            {
                //Rzuć nowe wyjątki
                //Funkcja "msqli_connect_errno()" wyrzuca odpowiedni do sytuacji opis
                throw new Exception(mysqli_connect_errno());
            }
            
            //Brak błędów połączenia
            else
            {   
                //------------SPRAWDZENIE ISTNIENIA ADRESU EMAIL W BAZIE DANYCH--------------------------
                
                //Zmienna przechowuje zapytanie do bazy danych
                $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
                
                //Jeżeli jako wynik zapytania otrzymamy "false"
                if(!$rezultat)
                {   
                    //Rzuć nowym wyjątkiem i pokaż na ekranie automatycznie wygenerowany opis błędu
                    throw new Exception($polaczenie->error);
                }
                
                //Zmienna przechowująca liczbę wierszy zawierających taki rekord "email" jak podany w formularzu
                $ile_takich_maili = $rezultat->num_rows;
                
                if($ile_takich_maili>0)
                {
                    //Flaga udanej walidacji przestawiona na "false"
                    $wszystko_OK = false;
                    
                    //Zmienna sesyjna błędu, dotycząca istnienia konta przypisanego do adresu
                    $_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu e-mail!";
                }
                
                //------------SPRAWDZENIE ISTNIENIA NAZWY UŻYTKOWNIKA W BAZIE DANYCH--------------------------
                
                //Zmienna przechowuje zapytanie do bazy danych
                $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nick'");
                
                //Jeżeli jako wynik zapytania otrzymamy "false"
                if(!$rezultat)
                {   
                    //Rzuć nowym wyjątkiem i pokaż na ekranie automatycznie wygenerowany opis błędu
                    throw new Exception($polaczenie->error);
                }
                
                //Zmienna przechowująca liczbę wierszy zawierających taki rekord "email" jak podany w formularzu
                $ilu_takich_uzytkownikow = $rezultat->num_rows;
                
                if($ilu_takich_uzytkownikow>0)
                {
                    //Flaga udanej walidacji przestawiona na "false"
                    $wszystko_OK = false;
                    
                    //Zmienna sesyjna błędu, dotycząca istnienia konta przypisanego do adresu
                    $_SESSION['e_nick'] = "Istnieje już gracz o takim nicku. Wybierz inny!";
                }
                
                //------------------------DODAWANIE NOWEGO UŻYTKOWNIKA DO BAZY-------------------------------
                
                //Waruek sprawdzający czy formularz przeszedł wszystkie testy
                if($wszystko_OK==true)
                {
                    //Jeżeli dodanie nowego użytkownika się udało
                    if($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$haslo_hash', '$email', 100, 100, 100, 14)"))
                    {
                        //Flaga udanej rejestracji
                        $_SESSION['udana_rejestracja']=true;
                        
                        //Przekierowanie użytkownika do pliku "witamy.php"
                        header('Location: witamy.php');
                        
                    }
                    
                    //Jeżeli dodanie nowego użytkownika się nie udało
                    else
                    {
                        //Rzuć nowym wyjątkiem i pokaż na ekranie automatycznie wygenerowany opis błędu
                        throw new Exception($polaczenie->error);
                    }
                    
                }
                
                
                //Metoda zamykająca połączenie
                $polaczenie->close();
            }
        }
        
        //Złap wyjątek
        catch(Exception $e)
        {
            echo '<span style="color: red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
            echo '<br/>Informacja developerska: '.$e;
        }
          
    }
    
?>

<!DOCUMENT HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE-edge,chrome=1"/>
    <title>Osadnicy - załóż darmowe konto!</title>
    
    <!--Skrypt generowany przez google podczas rejestrowania recaptcha-->
    <script src='https://www.google.com/recaptcha/api.js'></script>
    
    <!--Wewnętrzny CSS na potrzeby tego projektu-->
    <style>
        
        .error
        {
            color: red;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        
    </style>
    
</head>
<body>
    
    <!--Formularz rejestraci jest przetwarzany w tym samym pliku dlatego brak tutaj atrybutu "action"-->
    <form method="POST">
        
        Nickname: <br/><input type="text" name="nick" value="<?php
            
            //Jeżeli jest ustawiona zmienna sesyjna "$_SESSION['fr_nick']"
            if(isset($_SESSION['fr_nick']))
            {
                //Pokaż ją na ekranie
                echo $_SESSION['fr_nick'];
                
                //Usuń zmienną z sesji
                unset($_SESSION['fr_nick']);
            }
            
        ?>"/><br/>
        
        <?php
            
            //Jeżeli jest ustawiona zmienna sesyjna błedu "e_nick"
            if(isset($_SESSION['e_nick']))
            {
                //Wyświetlenie treści błędu na ekranie
               echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
               
               //Czyszczenie zmiennej sesyjnej aby informacja o błędzie nie wyświetlała się naniesieniu poprawek 
               unset($_SESSION['e_nick']);
            }
            
        ?>
        
        E-mail: <br/><input type="text" name="email" value="<?php
            
            //Jeżeli jest ustawiona zmienna sesyjna "$_SESSION['fr_email']"
            if(isset($_SESSION['fr_email']))
            {
                //Pokaż ją na ekranie
                echo $_SESSION['fr_email'];
                
                //Usuń zmienną z sesji
                unset($_SESSION['fr_email']);
            }
            
        ?>"/><br/>
        
        
        <?php
            
            //Jeżeli jest ustawiona zmienna sesyjna błedu "e_email"
            if(isset($_SESSION['e_email']))
            {
                //Wyświetlenie treści błędu na ekranie
               echo '<div class="error">'.$_SESSION['e_email'].'</div>';
               
               //Czyszczenie zmiennej sesyjnej aby informacja o błędzie nie wyświetlała się naniesieniu poprawek 
               unset($_SESSION['e_email']);
            }
            
        ?>
        
        Twoje hasło: <br/><input type="password" name="haslo1" value="<?php
            
            //Jeżeli jest ustawiona zmienna sesyjna "$_SESSION['fr_haslo1']"
            if(isset($_SESSION['fr_haslo1']))
            {
                //Pokaż ją na ekranie
                echo $_SESSION['fr_haslo1'];
                
                //Usuń zmienną z sesji
                unset($_SESSION['fr_haslo1']);
            }
            
        ?>"/><br/>
        
        <?php
            
            //Jeżeli jest ustawiona zmienna sesyjna błedu "e_haslo"
            if(isset($_SESSION['e_haslo']))
            {
                //Wyświetlenie treści błędu na ekranie
               echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
               
               //Czyszczenie zmiennej sesyjnej aby informacja o błędzie nie wyświetlała się naniesieniu poprawek 
               unset($_SESSION['e_haslo']);
            }
            
        ?>
        
        Powtórz hasło: <br/><input type="password" name="haslo2" value="<?php
            
            //Jeżeli jest ustawiona zmienna sesyjna "$_SESSION['haslo2']"
            if(isset($_SESSION['fr_haslo2']))
            {
                //Pokaż ją na ekranie
                echo $_SESSION['fr_haslo2'];
                
                //Usuń zmienną z sesji
                unset($_SESSION['fr_haslo2']);
            }
            
        ?>"/><br/>
        
        <!--Znacznik "label" powoduje, że także kliknięcie napisu "Akceptuję regulamin" zaznacza checkboxa-->
        <label>
            <input type="checkbox" name="regulamin" <?php
            
            //Jeżeli istnieje zmienna sesyjna "$_SESSION['fr_regulamin']"
            if(isset($_SESSION['fr_regulamin']))
            {
                //"Checked" w checkboxie oznacza, że jest on zaznaczony
                echo "checked";
                
                ////Usuń zmienną z sesji
                unset($_SESSION['fr_regulamin']);
                
            }
            
            ?>/> Akceptuję regulamin
        </label>
        
        <?php
            
            //Jeżeli jest ustawiona zmienna sesyjna błedu "e_regulamin"
            if(isset($_SESSION['e_regulamin']))
            {
                //Wyświetlenie treści błędu na ekranie
               echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
               
               //Czyszczenie zmiennej sesyjnej aby informacja o błędzie nie wyświetlała się naniesieniu poprawek 
               unset($_SESSION['e_regulamin']);
            }
            
        ?>
        
        <!--reCAPTCHA-->
        <div class="g-recaptcha" data-sitekey="6Ld3bwwUAAAAAO98R_Ck416C1mb1zVx6eZY1vJvz"></div>
        
        <?php
            
            //Jeżeli jest ustawiona zmienna sesyjna błedu "e_bot"
            if(isset($_SESSION['e_bot']))
            {
                //Wyświetlenie treści błędu na ekranie
               echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
               
               //Czyszczenie zmiennej sesyjnej aby informacja o błędzie nie wyświetlała się naniesieniu poprawek 
               unset($_SESSION['e_bot']);
            }
            
        ?>
        
        <br/>
        
        <!--Przycisk potwierdzający rejestrację-->
        <input type="submit" value="Zarejestruj się" />
    
    </form>
    
</body>
</html>