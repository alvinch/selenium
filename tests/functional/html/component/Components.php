<?php

abstract class Components {

    public $selenium;

    public function __construct($selenium)
    {
        $this->selenium = $selenium;
    }
}

