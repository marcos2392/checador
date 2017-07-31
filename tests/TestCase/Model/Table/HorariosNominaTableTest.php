<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HorariosNominaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HorariosNominaTable Test Case
 */
class HorariosNominaTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HorariosNominaTable
     */
    public $HorariosNomina;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.horarios_nomina'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('HorariosNomina') ? [] : ['className' => 'App\Model\Table\HorariosNominaTable'];
        $this->HorariosNomina = TableRegistry::get('HorariosNomina', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HorariosNomina);

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
