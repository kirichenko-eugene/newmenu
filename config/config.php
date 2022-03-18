<?php

require_once 'SessionShell.php';
require_once 'DatabaseShell.php';

//SESSIONS
$session = new SessionShell();

//PATH
$site = 'https://majorelle.goodcity.com.ru/';
$importLink = 'python3 /var/www/majorelle/MenuExport/MenuExport.py';
$dir = 'img/small/';
$bdir = 'img/big/';
$cdir = 'img/categories/';
$adir = 'img/actions/';
$pdir = 'img/properties/';
$pathUrl = mb_strrchr(dirname(__FILE__), '/', true);
$dirImg = $pathUrl . '/' . $dir;
$bdirImg = $pathUrl . '/' . $bdir;
$dirCategory = $pathUrl . '/' . $cdir;
$dirAction = $pathUrl . '/' . $adir;
$dirProperty = $pathUrl . '/' . $pdir;
