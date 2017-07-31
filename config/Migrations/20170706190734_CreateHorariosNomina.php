<?php
use Migrations\AbstractMigration;

class CreateHorariosNomina extends AbstractMigration
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
        $table = $this->table('horarios_nomina');
        $table->addColumn('entrada_real', 'time', [
            'default' => null,
            'null' => true,
        ]);

        $table->addColumn('salida_real', 'time', [
            'default' => null,
            'null' => true,
        ]);

        $table->addColumn('entrada_nomina', 'time', [
            'default' => null,
            'null' => true,
        ]);

        $table->addColumn('salida_nomina', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }
}
