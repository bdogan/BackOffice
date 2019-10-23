<?php
namespace BackOffice\Test\TestCase\Model\Table;

use BackOffice\Model\Table\MembersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * BackOffice\Model\Table\MembersTable Test Case
 */
class MembersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \BackOffice\Model\Table\MembersTable
     */
    public $Members;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.BackOffice.Members'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Members') ? [] : ['className' => MembersTable::class];
        $this->Members = TableRegistry::getTableLocator()->get('Members', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Members);

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
