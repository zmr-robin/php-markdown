<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html>";

require_once("phpmarkdown.php");

$pm = new PHPMarkdown();

echo $pm->convertFileToHtml("./test.md");