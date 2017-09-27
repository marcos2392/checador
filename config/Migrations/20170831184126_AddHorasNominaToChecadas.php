<?php
use Migrations\AbstractMigration;

class AddHorasNominaToChecadas extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('checadas');
        $table->addColumn('hrs_nomina', 'float', [
            'default' => 0,
            'null' => false,
        ]);
        $table->update();
    }
}
