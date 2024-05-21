<?php
// auth.php

session_start();

// Tempo de inatividade em segundos (e.g., 1800 segundos = 30 minutos)
$timeout_duration = 1800;

// Verifica se o usuário está inativo
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

// Atualiza a última atividade
$_SESSION['last_activity'] = time();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_nome'])) {
    header("Location: login.php");
    exit();
}
?>