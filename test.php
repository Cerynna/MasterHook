<?php
require __DIR__ . '/vendor/autoload.php';


$controller = new MasterHook\Controller();

echo "<pre>";
print_r($controller->getIntent());

echo "<pre>";