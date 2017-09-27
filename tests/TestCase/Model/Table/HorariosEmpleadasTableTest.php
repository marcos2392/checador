<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HorariosEmpleadasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HorariosEmpleadasTable Test Case
 */
class HorariosEmpleadasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HorariosEmpleadasTable
     */
    public $HorariosEmpleadas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.horarios_empleadas',
        'app.sucursales',
        'app.empleados',
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
        $config = TableRegistry::exists('HorariosEmpleadas') ? [] : ['className' => 'App\Model\Table\HorariosEmpleadasTable'];
        $this->HorariosEmpleadas = TableRegistry::get('HorariosEmpleadas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HorariosEmpleadas);

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
