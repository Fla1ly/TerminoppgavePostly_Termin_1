<?php
// Inkluderer databasekoblingen
include 'connect.php';

// Starter en sesjon
session_start();

// Fjerner alle sesjonsvariabler
session_unset();

// Avslutter sesjonen
session_destroy();

// Omdirigerer til hjemmesiden
header('location:../home.php');
