<?php
namespace BackOffice\Test\TestCase\View\Helper;

use BackOffice\View\Helper\FormHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * BackOffice\View\Helper\FormHelper Test Case
 */
class FormHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \BackOffice\View\Helper\FormHelper
     */
    public $Form;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->Form = new FormHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Form);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
