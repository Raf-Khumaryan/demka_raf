<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}
?>