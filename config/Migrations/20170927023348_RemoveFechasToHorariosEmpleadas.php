<?php
use Migrations\AbstractMigration;

class RemoveFechasToHorariosEmpleadas extends AbstractMigration
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
        $table->removeColumn('fecha');
        $table->removeColumn('fecha_actualizacion');
        $table->update();
    }
}
