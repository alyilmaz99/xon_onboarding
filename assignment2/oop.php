<?php
include_once 'Printer.php';
date_default_timezone_set("America/New_York");

class Book {

    public string $title;
    public string $author;
    public string $date;
    
   public function __set($name, $value){
        switch($name){
            case $name == 'title':
                $this->title = $value;
                break;
            case $name =='author':
                $this->author = $value;
                break;
            case $name =='date':
                $this->date = $value;
                break;
            default:
                break;
        }
   }
   public function __get($name){
        switch($name){
            case $name == "title":
                return $this->title;
            case $name == "author":
                return $this->author;
            case $name == "date":
                return $this->date;
            default:
            break;
        }
   }

}

class Ebook extends Book {
    public string $fileSize;
    public function __set($name,$value){
        parent::__set($name, $value);
        switch($name){
            case 'fileSize':
                $this->fileSize = $value;
                break;
            default:
                break;
        }
    }
    public function __get($name){
        switch($name){
            case 'fileSize':
               return $this->fileSize;
            default:
            return parent::__get($name);
        }
    }

}

$book = new Book();
$book->__set('title','ali');
$book->__set('author','Ali Yilmaz');
$book->__set('date', date("Y/m/d")." ".date("h:i:sa"));
echo "Title : " . $book->__get('title') . "\n";
echo "Date : ". $book->__get('date') . "\n";


$ebook = new Ebook();
$ebook->__set('title', 'Ebook Title');
$ebook->__set('author', 'Ebook Author');
$ebook->__set('date', date("Y/m/d")." ".date("h:i:sa"));
$ebook->__set('fileSize', '10 MB');

echo "Title : " . $ebook->__get('title') . "\n";
echo "Date : " . $ebook->__get('date') . "\n";
echo "File Size: " . $ebook->__get('fileSize') . "\n";


trait TBook {
    public $title;
    public $author;
    public $date;

}
trait TEBook{
    public $fileSize;
}

class SecondBook {
    use TBook;
    use TEBook;
    public function __set($name, $value){
        switch($name){
            case $name == 'title':
                $this->title = $value;
                break;
            case $name =='author':
                $this->author = $value;
                break;
            case $name =='date':
                $this->date = $value;
                break;
            case 'fileSize':
                $this->fileSize = $value;
                break;
            default:
                break;
        }
   }
   public function __get($name){
    switch($name){
        case "title":
            return $this->title;
        case 'fileSize':
                return $this->fileSize;
        case "author":
            return $this->author;
        case "date":
            return $this->date;
        default:
        break;
    }
}
}

$secondClass = new SecondBook();
$secondClass->__set('title', 'KITAP ISMI');
$secondClass->__set('author', 'YAZAR ISMI');
$secondClass->__set('date', date("Y/m/d"));
$secondClass->__set('fileSize',"123");
$printer = Printer::getInstance();
$printer->printerYellowumsu("======================SECOND==================");
$printer->printerRedimsi( "Title : " . $secondClass->__get('title'));
$printer->printerRedimsi("Author : " . $secondClass->__get('author'));
$printer->printerRedimsi("FileSize: " .  $secondClass->__get('fileSize'));
$printer->printerRedimsi("Date : " . $secondClass->__get('date'));


