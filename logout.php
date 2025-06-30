<?php
session_start();
session_destroy(); // Encerra todas as sessões ativas

// Redireciona para a página principal
header("Location: pg-principal.php");
exit;
?>

