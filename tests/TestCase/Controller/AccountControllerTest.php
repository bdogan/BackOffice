<?php
namespace BackOffice\Test\TestCase\Controller;

use BackOffice\Controller\AccountController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * BackOffice\Controller\AccountController Test Case
 *
 * @uses \BackOffice\Controller\AccountController
 */
class AccountControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.BackOffice.Account'
    ];

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
