<?php
namespace BackOffice\Test\TestCase\Model\Table;

use BackOffice\Model\Table\PagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * BackOffice\Model\Table\PagesTable Test Case
 */
class PagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \BackOffice\Model\Table\PagesTable
     */
    public $Pages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.BackOffice.Pages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Pages') ? [] : ['className' => PagesTable::class];
        $this->Pages = TableRegistry::getTableLocator()->get('Pages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Pages);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
