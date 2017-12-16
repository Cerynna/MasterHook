<?php
require __DIR__ . '/vendor/autoload.php';


$controller = new MasterHook\Controller();

$intents = $controller->getIntent();



echo "<pre>";


print_r($controller->checkIntent($intents,'hero', "arthur")) ;

echo "<pre>";