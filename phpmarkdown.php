<?php 

class PHPMarkdown {

    private $bold = false;
    private $italic = false;
    private $listStar = false;
    private $listHyphen = false;
    private $listIndex = -1;


    function __construct(){

    }

    public function convertFileToHtml(){

    }

    public function convertLineToHtml($content){
        $content = $this->convertLineType($content);
        $content = $this->replaceFontStyleBold($content);
        $content = $this->replaceFontStyleItalic($content);
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
                }
                break;
            case "-":
                $content = $this->convertToBulletlist($content, "-");
                return $content;
            case ">":
                // convert to highlight 
                // !todo 
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
        //echo "$index";
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
}