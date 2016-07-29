<?php

class Selenium extends PHPUnit_Extensions_Selenium2TestCase
{
    
    public static $configuration;

    /**
     * @var array
     */
    public static $truncatedDB;

    const MILLISECONDS = 1000;

    public function __call($command, $arguments) {
        usleep(self::$configuration->durationDelay * 1000);
        return parent::__call($command, $arguments);
    }



    protected static function restoreDatabaseConfig()
    {
        rename(self::$configuration->temporaryLocalXml, self::$configuration->localXml);
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
    }

    protected function tearDown()
    {
        parent::tearDown();   
    }

    protected function setUp()
    {
        parent::setUp();
        

        $this->setBrowser(self::$configuration->defaultBrowser);
        $this->setBrowserUrl(Navigator::BASE_URL);
        $this->setSeleniumServerRequestsTimeout(65);
    }

    public function setUpPage()
    {
        parent::setUpPage();
        $this->timeouts()->implicitWait(self::$configuration->durationWait);
    }


    public function goToPage($url)
    {
        $this->url(Navigator::BASE_URL . $url);
    }

    public function verifyTextInBody($text)
    {
        $this->verifyTextInElement($text, $this->byCssSelector('body'));
    }
    public function verifyTextInElement($text, $element)
    {
        $this->assertContains($text, $element->text());
    }

    public function clearAndSetValue($element, $value)
    {
        $element->clear();
        $element->value($value);
    }

    public function initLoginData()
    {
        $data = Data::getLoginUsername();
        return array(
            LoginFormComponent::CSS_INPUT_USERNAME => $data['username'],
            LoginFormComponent::CSS_INPUT_PASSWORD => $data['password']
            );
    }

    public function onNotSuccessfulTest(Exception $e)
    {
        if ( ! $e instanceof PHPUnit_Framework_IncompleteTestError && 
            ! $e instanceof PHPUnit_Framework_SkippedTestError &&
            self::$configuration->captureScreenshotOnFailure) {
            if ($this->getSessionId() !== FALSE) {
                try {
                    $screenshot = $this->currentScreenshot();
                    $fileName = date('Y-m-d_H-i-s') . '.png';
                    $storedPath = self::$configuration->screenShotPath . '/' . $fileName;
                    file_put_contents($storedPath, $screenshot);
                    $errorMessage = 'Sreenshot stored in ' . $storedPath . ' and can be accessed from ' . self::$configuration->screenShotUrl . '/' . $fileName . "\n";
                    echo $errorMessage;
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }
        parent::onNotSuccessfulTest($e);
    }

    public function isElementExist($selector)
    {
        try {
            $element = $this->byCssSelector($selector);
        } catch (Exception $e) {
            return false;
        }
        return $element;
    }

    /**
     * Wait until $callback return TRUE.
     * 
     * @param mixed $callback
     * @param array $param callback param
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function waitFor($callback, $param = array(), $message = "")
    {
        $timeout = self::$configuration->durationTimeout;
        $delay = 100;
        $startTime = microtime(TRUE) * self::MILLISECONDS;
        $duration = 0;

        do {
            $wait = TRUE;
            if (call_user_func_array($callback, $param) === TRUE) {
                $wait = FALSE;
            } else {
                usleep($delay);
            }
            $duration = microtime(TRUE) * self::MILLISECONDS - $startTime;
        } while ($wait && $duration <= $timeout);

        if ($wait) {
            throw new PHPUnit_Framework_AssertionFailedError("Had been waiting in $duration ms" . (($message !== "") ? " for $message" : ''));
        }
    }

    /**
     * @param string $text
     */
    public function waitForTextPresent($text)
    {
        $this->waitForTextPresentInElement($text, $this->byCssSelector('body'));
    }

    /**
     * @param string $text
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $element
     */
    public function waitForTextPresentInElement($text, $element)
    {
        $callback = function ($selenium, $text, $element) {
            try {
                $selenium->verifyTextPresentInElement($text, $element);
            } catch (Exception $exc) {
                return FALSE;
            }
            return TRUE;
        };
        $this->waitFor($callback, array($this, $text, $element), $text);
    }

    /**
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $element
     */
    public function waitForElementVisible($element)
    {
        $callback = function ($element) {
            return ($element->displayed());
        };
        $this->waitFor($callback, array($element), "element visible");
    }
}