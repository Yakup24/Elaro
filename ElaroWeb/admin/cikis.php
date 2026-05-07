<?php
session_start();
session_destroy();
header("Location: /E-Ticaret-Projesi/index.php");
exit;
