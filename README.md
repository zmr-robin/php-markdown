# PHPMarkdown

> Lightweight PHP Markdown to HTML Parser

PHPMarkdown is a simple yet powerful PHP class that converts Markdown syntax into HTML.

## Features

- Converts Markdown headers
- Supports bold, italic, <u>underline</u>, strikethrough, and ==highlight==
- Handles unordered and numbered lists
- Converts blockquotes
- Converts hyperlinks
- Converts images
- Maintains nested lists and mixed content formatting
- Easy to extend with additional Markdown syntax

## Installation / Usage

### 1. Include the class in your project
```php
require_once 'PHPMarkdown.php';
$parser = new PHPMarkdown();
```
### 2. Convert a Markdown file to HTML
```php
$file = file('example.md');
$html = $parser->convertFileToHtml($file);
echo $html;
```
### 3. Or convert a single line
```php
$htmlLine = $parser->convertLineToHtml("**Bold text** and *italic*");
echo $htmlLine; 
// Outputs: <b>Bold text</b> and <i>italic</i>
```

## Example

Markdown Input
```markdown
# Header 1
* Item 1
* Item 2
> Quote example
**Bold text** and *italic text* [Google](https://www.google.com)
```

HTML Output
```html
<h1>Header 1</h1>
<ul>
  <li>Item 1</li>
  <li>Item 2</li>
</ul>
<blockquote>Quote example</blockquote>
<b>Bold text</b> and <i>italic text</i> <a href='https://www.google.com'>Google</a>
```

```php
    private function replaceHyperlink($content){
        
        $contentHyperlinkText = "";
        $contentHyperlink = "";
        $contentImageUrl = "";
        $contentImageAltTag = "";
        for ($i = 0; $i < strlen($content); $i++){
            if($content[$i] == "[" && $content[($i - 1)] != "!"){
                for ($x = ($i + 1); $x < strlen($content); $x++){
                    if($content[$x] != "]"){
                        $contentHyperlinkText .= $content[$x];
                    } elseif($content[$x] == "]" && $content[($x + 1)] == "(") {
                        for ($y = ($x + 2); $y < strlen($content); $y++){
                            if($content[$y] != ")"){
                                $contentHyperlink .= $content[$y];
                            } else {
                                $content = str_replace("[$contentHyperlinkText]($contentHyperlink)", "<a href='$contentHyperlink'>$contentHyperlinkText</a>", $content);
                            }
                        }
                    } 
                }
            } elseif ($content[$i] == "[" && $content[($i - 1)] == "!") {
                for ($x = ($i + 1); $x < strlen($content); $x++){
                    if($content[$x] != "]"){
                        $contentImageAltTag .= $content[$x];
                    } elseif($content[$x] == "]" && $content[($x + 1)] == "(") {
                        for ($y = ($x + 2); $y < strlen($content); $y++){
                            if($content[$y] != ")"){
                                $contentImageUrl .= $content[$y];
                            } else {
                                $content = str_replace("![$contentImageAltTag]($contentImageUrl)", "<img src='$contentImageUrl' alt='$contentImageAltTag'/>", $content);
                            }
                        }
                    } 
                }
            }
            $contentHyperlink = "";
            $contentHyperlinkText = "";
            $contentImageAltTag = "";
            $contentImageUrl = "";
        }
        return $content;
    }
``

## Try it for yourself 

Visit the [live demo](https://zmro.dev/md/).
