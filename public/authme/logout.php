<?php
session_start();
require_once __DIR__ . '/../negocio/AutenticacionService.php';

$auth = new AutenticacionService();
$auth->logout();

header("Location: login.php");
exit();
