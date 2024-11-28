<?php

session_start();

include_once __DIR__ . '/../config/MongoDb.php';

$mongo = MongoDB::getInstance();
