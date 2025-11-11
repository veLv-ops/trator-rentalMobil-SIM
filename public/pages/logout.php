<?php
session_start();
session_destroy();
header('Location: /trator/home');
exit();
?>