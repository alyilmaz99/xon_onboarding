<?php 

$varAli = "Ali";
echo "Merhaba \n";
echo "My name is $varAli \n ";

$intVar = 1;
$floatVar = 1.908;
$stringVar = "string \n";
$boolVar = false;

$num1 =1;
$num2 = 3;
$num3 = 1.90;
$bool = false;

$checker = $num1 + $bool;

echo $checker . "\n";

echo var_dump($checker);


$aritmatic = $num1 + $num2;
$aritmatic2 = $num1 % $num2;
$aritmatic3 = $num1 * $num3;
$aritmatic4 = $bool * $num1;
$aritmatic5 = $bool / $num2 ;

echo $aritmatic . "\n";
echo $aritmatic2 . "\n";
echo $aritmatic3 . "\n";
echo $aritmatic4 . "\n";
echo $aritmatic5 . "\n";

$stringVar2 = "Ali";
$stringVar3 = "Yilmaz";


echo $stringVar2 . " ". $stringVar3 . "\n";

echo "This is string : " . $stringVar2 . " " . $stringVar3 . "\n";

$logic1 = $num1 > $num2;
var_dump($logic1);

echo "\0" . "\n";
echo "Logic 1 : " . ($logic1 ? "true" : "false") . "\n";


$userAge = readline('Enter a age: ');

switch ($userAge) {
    case $userAge >= 40:
        echo "senior \n";
        break;
        case $userAge >= 18 :
            echo "adult \n ";
            break;
    default:
        echo "minor \n";
        break;
}
if($userAge == 24){
    echo "my age too \n";
}

if("ali" == "ali"){
    echo "same string" . "\n";
}

$multiplicationTable = readline('Enter a number: ');
for($i = 0 ; $i < 10 ; $i ++){
    echo "$multiplicationTable * $i = ". ($multiplicationTable*$i) . "\n";
}
$factorial =1;
$numberFac = $multiplicationTable;
while($multiplicationTable >= 1){
   $factorial = $factorial * $multiplicationTable;
   echo $factorial. "\n";
   $multiplicationTable--;
}
echo "the factorial of $numberFac is $factorial \n";


function calculateArea(int $number1 , int $number2 ){
    return $number1 * $number2;
}

echo calculateArea(3,5) . "\n";

function calculateAreaForGivenNumber(){
    $square = readline('Rectangle is square (Y/N): ');
    if(strtolower($square) == "y"){
        $edge = readline('Enter a edge: ');
        echo $edge *$edge ."\n";

    }else if(strtolower($square) == "n"){
        $edge = readline('Enter a edge1: ');
        $edge2 = readline('Enter a edge2: ');
        echo $edge *$edge2 . "\n";

    }else{
        echo "Please Enter again! \n";
    }
}

calculateAreaForGivenNumber();

$names = "ali furkan ogun";
$array = explode(' ',$names);
foreach ($array as $value) {
  
    echo $value . "\n";
}

echo is_array($array) . "\n";
var_dump($array);
echo "String of names: ". implode(" ",$array) . "\n";

$stringLorem = "Lorem ipsum dolor sit amet consectetur adipiscing elit nec, interdum lectus urna posuere orci et vel morbi";
$chars = preg_split('//',$stringLorem,-1,PREG_SPLIT_NO_EMPTY);
print_r($chars);
$chars2 = preg_split('/ /', $stringLorem, -1, PREG_SPLIT_OFFSET_CAPTURE);

print_r($chars2);
unset($chars2);
unset($chars);

array_pop($array);
print_r($array);
array_push($array, 'ogun');
print_r($array);

echo count($array). "\n";

$ogunKey = array_search('ogun', $array);
echo "ogun key from array of names: " . $ogunKey  . "\n";

$array2 = array("isim" => "ali" , "yas" => 12, "boy" => 180 , "egitim" => "ilkokul");
print_r(array_values($array2));
print_r(array_keys($array2));

echo  array_key_exists('isim',$array2) ? "key mevcut \n" : 'key mevcut degil! \n';
asort($array2);

print_r($array2);
krsort($array);
print_r($array);

$arr = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];

var_dump(array_filter($arr, function($k) {
    return $k == 'b';
}, ARRAY_FILTER_USE_KEY));

var_dump(array_filter($arr, function($v, $k) {
    return $k == 'b' || $v == 4;
}, ARRAY_FILTER_USE_BOTH));


$stringWithName = "Benim adim ali yilmaz";

echo strlen($stringWithName) . "\n";

$substring = substr($stringWithName, 11, 3);
echo "Name is ". $substring . "\n";

$position = strpos($stringWithName, "ali");
echo "Position of ali " . $position . "\n";

$occurrence = strstr($stringWithName, "ali");
echo "Occurrence : ". $occurrence. "\n";
$replaced = str_replace("ali", "ogun", $stringWithName);
echo "Change ali to ogun : " .$replaced . "\n"; 

$lowercase = strtolower($stringWithName);
echo "LoweCase: " . $lowercase . "\n"; 

$uppercase = strtoupper($stringWithName);
echo "Upper Case : " . $uppercase . "\n"; 

if (preg_match("/ali/", $stringWithName)) {
    echo "string contains 'ali'. \n";
} else {
    echo "ali cant found in string.\n";
}

$replaced = preg_replace("/ali/", "ogun", $stringWithName);
echo $replaced . "\n";

$words = preg_split("/\s+/", $stringWithName);
print_r($words);

$reversed = strrev($stringWithName);
echo "reversed : " .$reversed . "\n";