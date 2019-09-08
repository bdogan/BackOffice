<?php
namespace BackOffice\Test\TestCase\Model\Table;

use BackOffice\Model\Table\PersistentLoginsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * BackOffice\Model\Table\PersistentLoginsTable Test Case
 */
class PersistentLoginsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \BackOffice\Model\Table\PersistentLoginsTable
     */
    public $PersistentLogins;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.BackOffice.PersistentLogins'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('PersistentLogins') ? [] : ['className' => PersistentLoginsTable::class];
        $this->PersistentLogins = TableRegistry::getTableLocator()->get('PersistentLogins', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PersistentLogins);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
