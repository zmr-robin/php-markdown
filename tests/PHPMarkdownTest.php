<?php declare(strict_types=1);

require_once 'vendor/autoload.php';
require_once __DIR__ . '/../src/phpmarkdown.php';

use PHPUnit\Framework\TestCase;
final class PHPMarkdownTest extends TestCase
{
    private PHPMarkdown $pm;
    
    protected function setUp(): void
    {
        $this->pm = new PHPMarkdown();
    }
    public function testValidateHeader():void {
        
        // If all header (1-6) work.
        $headerHashtag = "";
        for ($i = 1; $i < 8; $i ++){
            $index = ($i >= 6) ? 6 : $i;
            $headerHashtag .= "#";
            $testString = "$headerHashtag Header $index";
            $expectedReturn = "<h$index>Header $index</h$index>";
            $this->assertSame(expected: $expectedReturn, actual: $this->pm->convertLineToHtml($testString), message: "Failed to convert h$index!");
        }

        // Check # without a space between text
        $headerHashtag = "";
        for ($i = 1; $i < 8; $i ++){
            $index = $i;
            $headerHashtag .= "#";
            $testString = "{$headerHashtag}Header $index";
            $expectedReturn = "<p>{$headerHashtag}Header $index</p>";
            $this->assertSame(expected: $expectedReturn, actual: $this->pm->convertLineToHtml($testString), message: "Failed to convert fake header!");
        }
        
        // Check if line only containing # converts to header (<h1>#</h1>) or paragraph (<p>#</p>)
        $this->assertSame(expected: "<p>#</p>", actual: $this->pm->convertLineToHtml("#"), message: "Failed to convert # to <p>#</p>");

    }

    public function testList():void {
        // !todo
    }




} 