<?php

class LoginFormComponent extends FormComponents {
    
    const CSS_INPUT_USERNAME = "username";
    const CSS_INPUT_PASSWORD = "password";
    const CSS_BUTTON_SUBMIT = "#loginform > div:nth-child(1) > div.col-xs-5 > input.btn.btn-default.btn-blue.login-btn";
    
    const CSS_PANEL_LOGIN = "#loginform > a";

    public function fieldConfiguration()
    {
        return array(
            self::CSS_INPUT_USERNAME => array(
                'input' => self::INPUT_TEXT,
                'type' => array('id', self::CSS_INPUT_USERNAME),
            ),
            self::CSS_INPUT_PASSWORD => array(
                'input' => self::INPUT_TEXT,
                'type' => array('id', self::CSS_INPUT_PASSWORD),
            )

        );
    }

    public function submitElement()
    {
        return array('css selector', self::CSS_BUTTON_SUBMIT);
    }

    public function clickPanelLogin()
    {
        $this->selenium->byCssSelector(self::CSS_PANEL_LOGIN)->click();
    }
    
}

