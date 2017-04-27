<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ChecadasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ChecadasTable Test Case
 */
class ChecadasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ChecadasTable
     */
    public $Checadas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.checadas',
        'app.empleados',
        'app.sucursales',
        'app.usuarios'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Checadas') ? [] : ['className' => 'App\Model\Table\ChecadasTable'];
        $this->Checadas = TableRegistry::get('Checadas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Checadas);

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
