<?php
    require_once("../common/db.php");
    session_start();
    disconnect();
    header("Location:index.php");
?>