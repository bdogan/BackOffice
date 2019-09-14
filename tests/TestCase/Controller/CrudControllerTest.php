<?php
namespace BackOffice\Test\TestCase\Controller;

use BackOffice\Controller\CrudController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * BackOffice\Controller\CrudController Test Case
 *
 * @uses \BackOffice\Controller\CrudController
 */
class CrudControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.BackOffice.Crud'
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
