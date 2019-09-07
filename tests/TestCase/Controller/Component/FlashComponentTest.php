<?php
namespace BackOffice\Test\TestCase\Controller\Component;

use BackOffice\Controller\Component\FlashComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * BackOffice\Controller\Component\FlashComponent Test Case
 */
class FlashComponentTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \BackOffice\Controller\Component\FlashComponent
     */
    public $Flash;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Flash = new FlashComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Flash);

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
