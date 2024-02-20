<?php

class Validator
{

    private static $instances = [];
    public $isValid = true;
    protected function __construct()
    {
    }

    public static function getInstance(): Validator
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    public function textValidation($text): array
    {
        $cleanText = trim($text);
        if (empty($cleanText)) {
            return $this->response(false, 'Please enter a value', $cleanText);
        } else if (!preg_match("/^[a-zA-Z-' ]*$/", $cleanText)) {
            return $this->response(false, 'Value must contain only letters', $cleanText);
        } else if (strlen($cleanText) < 3 || strlen($cleanText) > 25) {
            return $this->response(false, 'Value must be between 3 and 20 characters long', $cleanText);
        } else {
            return $this->response(true, null, $cleanText);
        }
    }

    public function emailValidation($email): array
    {
        $cleanEmail = trim($email);
        if (empty($cleanEmail)) {
            return $this->response(false, 'Please enter a value', $cleanEmail);
        } else if (!filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->response(false, 'Please enter a valid email', $cleanEmail);
        } else if (strlen($cleanEmail) > 320) {
            return $this->response(false, 'Please enter a valid email', $cleanEmail);
        } else {
            return $this->response(true, null, $cleanEmail);
        }
    }

    public function websiteValidation($website): array
    {
        $cleanWebsite = trim($website);
        $cleanWebsite = filter_var($website, FILTER_SANITIZE_URL);

        if (empty($cleanWebsite)) {
            return $this->response(false, 'Please enter a value', $cleanWebsite);
        } else if (!filter_var($cleanWebsite, FILTER_VALIDATE_URL)) {
            return $this->response(false, 'Please enter a valid website', $cleanWebsite);
        } else if (strlen($cleanWebsite) > 128) {
            return $this->response(false, 'Please shorten your website', $cleanWebsite);
        } else if (!preg_match(
            '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i',
            $cleanWebsite
        )) {
            return $this->response(false, "Please enter a valid email", $cleanWebsite);
        } else {
            return $this->response(true, '', $cleanWebsite);
        }
    }

    public function ageValidation($age): array
    {
        $cleanAge = trim($age);
        if (empty($cleanAge)) {
            return $this->response(false, 'Please enter a value', $cleanAge);
        } else if ($age <= 0 || $age >= 140) {
            return $this->response(false, 'Please enter a valid age', $cleanAge);
        } else if (!preg_match("/^[0-9]+$/", $cleanAge)) {
            return $this->response(false, 'Please enter a valid age', $cleanAge);
        } else {
            return $this->response(true, null, $cleanAge);
        }
    }
    public function phoneValidation($phone): array
    {
        $cleanPhone = trim($phone);
        if (empty($cleanPhone)) {
            return $this->response(false, 'Please enter a value', $cleanPhone);
        } else if ($phone <= 0 || $phone >= 99999999999) {
            return $this->response(false, 'Please enter a valid age', $cleanPhone);
        } else if (!preg_match("/^[0-9]+$/", $cleanPhone)) {
            return $this->response(false, 'Please enter a valid age', $cleanPhone);
        } else {
            return $this->response(true, null, $cleanPhone);
        }
    }

    public function response($isValid = false, $errorMessage = null, $data = ''): array
    {

        $isValid == false  ? $this->isValid = false : $this->isValid = true;
        return ['isValid' => $isValid, 'error' => $errorMessage, 'data' => htmlentities($data)];
    }
}
