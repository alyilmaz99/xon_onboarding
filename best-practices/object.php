<?php
class Obje
{
    public $secret;
}
// dışardan bu şekild ebir veri aldığımızda zarar verebilir bizim kodumuza.

$serialized_data = 'O:4:"Obje":1:{s:6:"secret";s:15:"hatali kullanim";}';

$obj = unserialize($serialized_data);

echo $obj->secret;

// çözümü için ise bunun kontrolünü yapmamız gerekiyor öncelikle object olup olmadığını veya 0 dönüp dönmediğine bakarız.
