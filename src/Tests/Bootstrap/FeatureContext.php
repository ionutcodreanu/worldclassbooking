<?php

namespace WorldClassBooker\Tests\Bootstrap;

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
{
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    private $schedulePage;
    private $clubId;
    private $trainer;
    private $classType;
    private $clubName;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     * @param $username
     * @param $password
     * @param $schedulePage
     * @param $trainer
     * @param $classType
     * @param $clubName
     */
    public function __construct(
        $username,
        $password,
        $schedulePage,
        $trainer,
        $classType,
        $clubName
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->schedulePage = $schedulePage;
        $this->trainer = $trainer;
        $this->classType = $classType;
        $this->clubName = $clubName;
    }

    /**
     * Fill email field
     *
     * @Given /^I complete email field$/
     */
    public function iCompleteEmailField()
    {
        $this->fillField('email', (string)$this->username);
    }

    /**
     * Fill password field
     *
     * @Given /^I complete password field$/
     */
    public function iCompletePasswordField()
    {
        $this->fillField('pincode', (string)$this->password);
//        sleep(30);
    }

    /**
     * Fill email field
     *
     * @Given /^I submit login form$/
     */
    public function iSubmitLoginForm()
    {
        $formId = '.form-signin';
        $form = $this->getSession()->getPage()->find('css', $formId);
        if (!$form) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'form', 'id', $formId);
        }
        $form->submit();
    }

    /**
     * @Given /^I am on the members page$/
     */
    public function iAmOnTheMembersPage()
    {
        $this->visit('/');
    }

    /**
     * @Given /^I go to the schedule page$/
     */
    public function iGoToTheSchedulePage()
    {
        $this->visit($this->schedulePage);
    }

    /**
     * @Given /^I select the preferred club$/
     */
    public function iSelectThePreferredClub()
    {
        $clubSelectorOption = $this->getSession()->getPage()->find(
            'named',
            [
                'option',
                $this->clubName
            ]
        );
        if (!$clubSelectorOption) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'form', 'id', $clubSelectorOption);
        }
        $value = $clubSelectorOption->getValue();
        $this->clubId = $value;
        $clubSelectorOption->getParent()->setValue($value);
    }

    /**
     * @Given /^I select the class type$/
     */
    public function iSelectTheClassType()
    {
        $classTypeOption = $this->getSession()->getPage()->find(
            'named',
            [
                'option',
                $this->classType
            ]
        );
        if (!$classTypeOption) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'form', 'id', $clubSelector);
        }
        $value = $classTypeOption->getValue();
        $classTypeOption->getParent()->setValue($value);
    }

    /**
     * @Given /^I book tomorrow class with preferred instructor$/
     */
    public function iBookTomorrowClassWithPreferredInstructor()
    {
        $nextDayContainer = $this->getSession()->getPage()->findAll('xpath',
            '//*[@id="schedule-carousel"]/ul/li[2]/div[2]');
        if (!$nextDayContainer) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'div', 'xpath', $nextDayContainer);
        }
        $nextDay = $nextDayContainer[0];
        $spanTrainer = $nextDay->find(
            'named',
            ['content', $this->trainer]
        );
        if (!$spanTrainer) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'span', 'content', $spanTrainer);
        }
        /** @var NodeElement $container */
        $container = $spanTrainer->getParent()->getParent();
        $bookLink = $container->findLink('REZERVA');
        if (!$bookLink) {
            throw new ElementNotFoundException($this->getSession()->getDriver(), 'link', 'css', $bookLink);
        }

        $dataTarget = $bookLink->getAttribute('data-target');
        $classId = substr($dataTarget, 7);
        $this->visit("_book_class.php?id={$classId}&clubid={$this->clubId}");
    }
}
