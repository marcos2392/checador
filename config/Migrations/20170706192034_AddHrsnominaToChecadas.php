<?php
use Migrations\AbstractMigration;

class AddHrsnominaToChecadas extends AbstractMigration
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
        $table->addColumn('entrada_nomina', 'time', [
            'default' => null,
            'null' => true,
        ]);

        $table->addColumn('salida_nomina', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
