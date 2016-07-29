<?php

class HomePage extends AbstractPage {

	/**
	 *
	 * @var LoginFormComponent
	 */
	private $formLogin;

	public function __construct($selenium)
	{
		parent::__construct($selenium);
		$this->formLogin = new LoginFormComponent($selenium);
	}

	/**
	 * Open the login panel
	 */
	public function clickPanelLogin()
	{
		$this->formLogin->clickPanelLogin();
	}

	public function login($data)
	{
		$this->formLogin->setAllFields($data);
		$this->formLogin->submit();
	}
}
