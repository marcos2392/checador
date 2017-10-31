<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HorasChecadasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HorasChecadasTable Test Case
 */
class HorasChecadasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HorasChecadasTable
     */
    public $HorasChecadas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.horas_checadas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('HorasChecadas') ? [] : ['className' => 'App\Model\Table\HorasChecadasTable'];
        $this->HorasChecadas = TableRegistry::get('HorasChecadas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->HorasChecadas);

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
