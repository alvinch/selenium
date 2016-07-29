<?php

abstract class AbstractPage 
{
    public $selenium;

    public function __construct($selenium) 
    {
        $this->selenium = $selenium;
    }
}

