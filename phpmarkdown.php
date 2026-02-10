<?php 

class PHPMarkdown {

    private $bold = false;
    private $italic = false;
    private $underline = false;
    private $strikethrough = false;
    private $highlight = false;
    private $listIndex = -1;



    function __construct(){

    }

    public function convertFileToHtml($file){
        $content = "";
        foreach (file($file) as $line) {
            if (trim($line) != '') {
                $content = $content . $this->convertLineToHtml($line);
            }
        }
        return $content;
    }


    public function convertLineToHtml($content){
        $content = $this->convertLineType($content);
        $content = $this->replaceFontStyleBold($content);
        $content = $this->replaceFontStyleItalic($content);
        $content = $this->replaceFontStyleUnderline($content);
        $content = $this->replaceFontStyleHighlight($content);
        $content = $this->replaceFontStyleStrikethrough($content);
        $content = $this->replaceHyperlink($content);
        echo "\n"; // for debugging 
        return $content;
    } 

    private function convertLineType($content){
        switch($content[0]){
            case "#":   
                $content = $this->convertToHeader($content);
                $content = ($this->listIndex != -1) ? "</ul>" . $content : $content;
                $this->listIndex = -1;
                return $content;
            case "*":
                if($content[1] != "*"){
                    $content = $this->convertToBulletlist($content, "*");
                    return $content;
                } else {
                    return "<p>$content</p>";
                }
            case "-":
                if ($content[1] == " ") {
                    $content = $this->convertToBulletlist($content, "-");
                    return $content;
                } elseif (($content[0] . $content[1] . $content[2]) == "---" && strlen(trim($content)) == 3) {
                    return "<hr>";
                } else {
                    return "<p>$content</p>";
                }
            case ">":
                $content = $this->convertToQuote($content);
                $content = ($this->listIndex != -1) ? "</ul>" . $content : $content;
                $this->listIndex = -1;
                return $content;
            default:
                if (preg_match('/^[0-9]+\.?/',$content)){
                    // convert to decimal list
                } elseif(str_contains($content, "-") && $content[0] == " "){
                    // check if it's a indented list 
                    for ($i = 0; $i < strlen($content); $i++){
                        if ($content[$i] == "-"){
                            $content = $this->convertToBulletlist($content, "-", $i);
                            return $content;
                        } elseif ($content[$i] == "*" && $content[$i + 1] != "*"){
                            $content = $this->convertToBulletlist($content, "*", $i);
                            return $content;
                        } elseif ($content[$i] != " "){
                            $i = strlen($content);
                        }
                    }
                } else {
                    // convert to <p>
                    $content = ($this->listIndex != -1) ? "</ul>" . $content : $content;
                    $this->listIndex = -1;
                    return "<p>$content</p>";
                }
        }
    }

    private function convertToHeader($content) {
        $headerCounter = 0;
        $headerReplace = "";
        for ($i = 0; $i < strlen($content); $i++){
            if ($content[$i] == "#"){
                $headerCounter++;
                $headerReplace = $headerReplace . "#";
            } else {
                $i = strlen($content);
            }
        }
        if ($headerCounter > 6){
            $headerCounter = 6;
        }
        return str_replace("$headerReplace ", "<h$headerCounter>", $content) . "</h$headerCounter>";
    }

    private function convertToBulletlist($content, $listType, $index = 0) {
        // remove spaces before hyphen
        $content = preg_replace('/-/', '', $content, 1);
        $content = preg_replace('/ /', '', $content, $index + 1);
        
        // Check if index is bigger or smaller than current index
        if ($this->listIndex < $index){
            $this->listIndex = $index;
            return "<ul><li>" . $content . "</li>";
        } elseif ($this->listIndex > $index){
            $this->listIndex = $index;
            return "</ul><li>" . $content . "</li>";
        } else {
            return "<li>" . $content . "</li>";
        }
    }

    private function convertToQuote($content){
        $content = preg_replace("/\>/", "", $content, 1);
        return "<blockquote>$content</blockquote>";
    }

    private function replaceFontStyleBold($content) {
        $boldCheck = false;
        while (!$boldCheck) {
            if(str_contains(haystack: $content, needle: "**")){
                switch($this->bold){
                    case false:
                        $content = preg_replace("/\*\*/", "<b>", $content, 1);
                        $this->bold = true;
                        break;
                    case true:
                        $content = preg_replace("/\*\*/", "</b>", $content, 1);
                        $this->bold = false;
                        break;
                }
            } else {
                $boldCheck = true;
            }
        }
        return $content;
    }

    private function replaceFontStyleItalic($content) {
        $italicCheck = false;
        while (!$italicCheck) {
            if(str_contains(haystack: $content, needle: "*")){
                switch($this->italic){
                    case false:
                        $content = preg_replace("/\*/", "<i>", $content, 1);
                        $this->italic = true;
                        break;
                    case true:
                        $content = preg_replace("/\*/", "</i>", $content, 1);
                        $this->italic = false;
                        break;
                }
            } else {
                $italicCheck = true;
            }
        }
        return $content;
    }

    private function replaceFontStyleUnderline($content) {
        $underlineCheck = false;
        while (!$underlineCheck) {
            if(str_contains(haystack: $content, needle: "_")){
                switch($this->underline){
                    case false:
                        $content = preg_replace("/\_/", "<u>", $content, 1);
                        $this->underline = true;
                        break;
                    case true:
                        $content = preg_replace("/\_/", "</u>", $content, 1);
                        $this->underline = false;
                        break;
                }
            } else {
                $underlineCheck = true;
            }
        }
        return $content;
    }

    private function replaceFontStyleHighlight($content) {
        $highlightCheck = false;
        while (!$highlightCheck) {
            if(str_contains(haystack: $content, needle: "==")){
                switch($this->highlight){
                    case false:
                        $content = preg_replace("/\==/", "<mark>", $content, 1);
                        $this->highlight = true;
                        break;
                    case true:
                        $content = preg_replace("/\==/", "</mark>", $content, 1);
                        $this->highlight = false;
                        break;
                }
            } else {
                $highlightCheck = true;
            }
        }
        return $content;
    }
    private function replaceFontStyleStrikethrough($content) {
        $strikethroughCheck = false;
        while (!$strikethroughCheck) {
            if(str_contains(haystack: $content, needle: "~~")){
                switch($this->strikethrough){
                    case false:
                        $content = preg_replace("/\~~/", "<strike>", $content, 1);
                        $this->strikethrough = true;
                        break;
                    case true:
                        $content = preg_replace("/\~~/", "</strike>", $content, 1);
                        $this->strikethrough = false;
                        break;
                }
            } else {
                $strikethroughCheck = true;
            }
        }
        return $content;
    }

    private function replaceHyperlink($content){

        return $content;
    }

}