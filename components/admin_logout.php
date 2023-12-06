<?php
// Inkluderer tilkoblingsfilen
include 'connect.php';

// Starter en sesjon, fjerner alle sesjonsvariabler og avslutter sesjonen
session_start();
session_unset();
session_destroy();

// Omdirigerer til admin påloggingssiden
header('location:../admin/admin_login.php');
