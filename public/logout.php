<?php
require_once __DIR__ . '/../src/Auth.php';
Auth::start();
Csrf::check();
Auth::logout();
header('Location: login.php');
