<?php

use Behat\Behat\Event\ScenarioEvent,
    Behat\Behat\Event\SuiteEvent;
use Behat\Gherkin\Node\OutlineNode;
use Behat\Mink\Behat\Context\BaseMinkContext;
use Behat\Mink\Mink,
    Behat\Mink\Session,
    Behat\Mink\Driver\SeleniumDriver;
use Selenium\Client as SeleniumClient;

class FeatureContext extends Behat\MinkExtension\Context\MinkContext
{
    /**
     *
     *
     * @When /^I search for "([^"]*)"$/
     */
    public function iSearchFor( $key ) {
        $this->fillField( "s", $key );
        $this->pressButton( "Search" );
    }

    /**
     *
     *
     * @Given /^I am on blogs page$/
     */
    public function iAmOnBlogsPage() {
        $this->visit( "/blog" );
    }

     /** @AfterScenario */
    public function after($event)
    {
        $jobId = $this->getSessionId($event);
        $result = false;
        $passed = $event->getResult();

        if ($passed === 0) {
            $result = true;
        }

        $this->modifySauceJob(
            sprintf(
                '{"passed": %s}',
                $result
            ),
            $jobId
        );
    }
    

    public function getSessionId($event) 
    {
        $scenario = $event instanceof ScenarioEvent ? $event->getScenario() : $event->getOutline();
        $context = $event->getContext();
        $url = $context->getSession()->getDriver()->getWebDriverSession()->getUrl();
        $parts = explode('/', $url);
        $sessionId = array_pop($parts);
        return $sessionId;
    }

    public function modifySauceJob($payload, $session_id) {
        $username = getenv("SAUCE_USERNAME");
        $access_key = getenv("SAUCE_ACCESS_KEY");
        $ch = curl_init(
            sprintf(
                'https://%s:%s@saucelabs.com/rest/v1/%s/jobs/%s',
                $username,
                $access_key,
                $username,
                $session_id
            )
        );
        $length = strlen($payload);
        $fh = fopen('php://memory', 'rw');
        fwrite($fh, $payload);
        rewind($fh);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_INFILE, $fh);
        curl_setopt($ch, CURLOPT_INFILESIZE, $length);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

}







