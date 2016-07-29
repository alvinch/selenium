<?php

class HomePageTest extends Selenium
{
    protected $loginData;

    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->loginData = $this->initLoginData();
    }   

    public function setUpPage()
    {
        parent::setUpPage();
       
    }

    public function test_LoginWithValidData_ShouldScucess()
    {   
        $this->goToPage(Navigator::HomePage);
        $loginPage  = new HomePage($this);
        $loginPage->clickPanelLogin();
        $loginPage->login($this->loginData);
    }
}