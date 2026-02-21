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

## Installation


### 1. Install package using composer
```bash
composer require zmr-robin/php-markdown
```


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

## Try it for yourself 

Visit the [live demo](https://zmro.dev/md/).
