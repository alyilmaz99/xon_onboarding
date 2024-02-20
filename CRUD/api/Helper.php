<?php

class Helper
{
    public function getPost()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
