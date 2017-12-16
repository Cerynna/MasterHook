<?php
/**
 * Created by PhpStorm.
 * User: cerynna
 * Date: 15/12/17
 * Time: 22:21
 */

$json = json_decode(file_get_contents('hero.json')) ;


print_r( json_encode($json) );

