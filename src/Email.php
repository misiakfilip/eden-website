<?php
session_start();

function generate_csrf_token() {
    return bin2hex(random_bytes(32));
}

function set_csrf_token() {
    $csrf_token = generate_csrf_token();
    $_SESSION['csrf_token'] = $csrf_token;
    return $csrf_token;
}

function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Generowanie tokena CSRF
$csrf_token = set_csrf_token();

// Walidacja tokena CSRF po wysłaniu formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // if (!isset($_POST['csrf_token']) || $_SESSION['csrf_token'] !== $_POST['csrf_token']) {
    //     // Token CSRF nieprawidłowy - odrzuć żądanie
    //     die("Błąd CSRF!");
    // }

    // Przetwarzanie formularza
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $number = htmlspecialchars($_POST['number']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Sprawdź poprawność adresu e-mail
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adres e-mail jest poprawny.";

        // Adres e-mail, na który zostanie wysłana wiadomość
        //$to = "edenloranc@gmail.com";
        $to = "filip3314@gmail.com";

        // Temat wiadomości
        $subject = "Nowa wiadomość od $name $surname";

        // Treść wiadomości
        $content = "Imię: $name\n";
        $content .= "Nazwisko: $surname\n";
        $content .= "Numer kontaktowy: $number\n";
        $content .= "Email: $email\n\n";
        $content .= "Treść wiadomości:\n$message";

        // Nagłówki
        $headers = "From: $name $surname <$email>";

        // Wysłanie wiadomości e-mail
        if (mail($to, $subject, $content, $headers)) {
            // Przekierowanie po wysłaniu wiadomości
            header("Location: Kontakt.html?success=true");
            exit;
        } else {
            header("Location: Kontakt.html?success=false");
            exit;
        }
    } else {
        header("Location: Kontakt.html?success=false");
            exit;
    }
}
?>
