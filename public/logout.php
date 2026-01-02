<?php
require_once __DIR__ . '/../src/Service/AuthService.php';
AuthService::logout();
header("Location: index.php");
exit;
?>