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
            $_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków";
        }
        
        //Jeżeli zmienna "nick" zawiera inne znaki niż litery lub cyfry
        //ctype_alnum - check type alphanumeric - funkcja zwraca "false" jeżeli jej argument zawiera znaki inne niż alfanumeryczne
        if(ctype_alnum($nick)==false)
        {
            //Flaga udanej walidacji przestawiona na "false"
            $wszystko_OK = false;
            
            //Zmienna sesyjna błędu, dotycząca rodzaju użytych znaków w nicku
            $_SESSION['e_nick'] = "Nick może składać się tylko z liter i cyfr (bez polskich ogonków)";
        }
        
        //-----------------------SPRAWDZANIE POPRAWNOŚCI EMAILA------------------------------------------
        
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
        
        
        
        
        
        
        
        
        //-----------------------SPRAWDZANIE POPRAWNOŚCI CAŁEGO FORMULARZA-------------------------------
        
        //Waruek sprawdzający czy formularz przeszedł wszystkie testy
        if($wszystko_OK==true)
        {
            //
            echo "Udana walidacja!";
            
            exit();
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
        
        Nickname: <br/><input type="text" name="nick" /><br/>
        
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
        
        E-mail: <br/><input type="text" name="email" /><br/>
        
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
        
        Twoje hasło: <br/><input type="password" name="haslo1" /><br/>
        
        Powtórz hasło: <br/><input type="password" name="haslo2" /><br/>
        
        <!--Znacznik "label" powoduje, że także kliknięcie "Akceptuję regulamin zaznacza checkboxa-->
        <label>
            <input type="checkbox"/> Akceptuję regulamin
        </label>
        
        <!--reCAPTCHA-->
        <div class="g-recaptcha" data-sitekey="6Ld3bwwUAAAAAO98R_Ck416C1mb1zVx6eZY1vJvz"></div>
        
        <br/>
        
        <!--Przycisk potwierdzający rejestrację-->
        <input type="submit" value="Zarejestruj się" />
    
    </form>
    
</body>
</html>
