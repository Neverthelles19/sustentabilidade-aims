<?php
// logout.php

// Inicia a sessão
session_start();

// Destrói a sessão
session_unset();
session_destroy();

// Redireciona para a página de login
header("Location: login.php");
exit;
?>