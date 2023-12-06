<?php

// Databaseinformasjon
$db_host = 'localhost';
$db_name = 'blog_db';
$db_user = 'Dawid';
$db_password = 'Admin';

try {
    // Oppretter en PDO-tilkobling til databasen
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);

    // Setter PDO-attributter for å få feilmeldinger ved problemer
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Ved feil i tilkoblingen, skriv ut feilmeldingen og avslutt
    echo "Connection failed: " . $e->getMessage();
    die();
}
