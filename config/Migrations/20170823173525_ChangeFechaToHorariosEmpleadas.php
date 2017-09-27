<?php
use Migrations\AbstractMigration;

class ChangeFechaToHorariosEmpleadas extends AbstractMigration
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
        $table = $this->table('horarios_empleadas');
        $table->changeColumn('fecha', 'date', [
            'null' => false,
            'default' => 0
        ]);

        $table->changeColumn('fecha_actualizacion', 'date', [
            'null' => false,
            'default' => 0
        ]);
    }
}
