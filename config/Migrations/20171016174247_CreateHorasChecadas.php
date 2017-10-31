<?php
use Migrations\AbstractMigration;

class CreateHorasChecadas extends AbstractMigration
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
        $table = $this->table('horas_checadas');
        $table->addColumn('empleado_id', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('sucursal_id', 'integer', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('fecha_inicio', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('fecha_termino', 'date', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('hrs_checadas', 'float', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('hrs_editadas', 'float', [
            'default' => 0,
            'null' => false,
        ]);
        $table->create();
    }
}
