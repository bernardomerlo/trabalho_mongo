<?php

include_once '../start/init.php';

if (isset($_GET["id"])) {
    $cliente = $_SERVER["REMOTE_ADDR"];

    $mongo->delete("cortes", ["cliente" => $cliente]);
    header("Location: ../index.php");
}
