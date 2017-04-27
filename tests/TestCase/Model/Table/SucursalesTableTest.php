<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SucursalesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SucursalesTable Test Case
 */
class SucursalesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SucursalesTable
     */
    public $Sucursales;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sucursales'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Sucursales') ? [] : ['className' => 'App\Model\Table\SucursalesTable'];
        $this->Sucursales = TableRegistry::get('Sucursales', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Sucursales);

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
