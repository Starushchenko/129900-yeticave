<?php
session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user = $_SESSION['user'];
} else {
    $is_auth = false;
    $user = null;
}