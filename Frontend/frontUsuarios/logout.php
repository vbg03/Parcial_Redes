<?php
session_start();
session_unset(); // Borra todo
session_destroy(); // Cierra sesión
header("Location: login.php");
exit;
?>
