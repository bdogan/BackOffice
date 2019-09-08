<?php
namespace BackOffice\Test\TestCase\Controller\Component;

use BackOffice\Controller\Component\CookieAuthComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * BackOffice\Controller\Component\CookieAuthComponent Test Case
 */
class CookieAuthComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \BackOffice\Controller\Component\CookieAuthComponent
     */
    public $CookieAuth;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->CookieAuth = new CookieAuthComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CookieAuth);

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
