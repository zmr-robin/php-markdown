<?php 

echo "<!DOCTYPE html>";

require_once("phpmarkdown.php");

$pm = new PHPMarkdown();

$testCase0 = "# Hallo Welt";
$testCase1 = "Das **ist** ein Test. Es *scheint zu funktionieren.*";
$testCase2 = "## Header 2";
$testCase3 = "- Test 1";
$testCase4 = "  - Test 2";
$testCase5 = "  - Lorem Ipsum Text amk";
$testCase6 = "- Test 3";
$testCase7 = "#### Header 4";

echo $pm->convertLineToHtml($testCase0);
echo $pm->convertLineToHtml($testCase1);
echo $pm->convertLineToHtml($testCase2);
echo $pm->convertLineToHtml($testCase3);
echo $pm->convertLineToHtml($testCase4);
echo $pm->convertLineToHtml($testCase5);
echo $pm->convertLineToHtml($testCase6);
echo $pm->convertLineToHtml($testCase7);

