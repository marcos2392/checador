<?php
use Migrations\AbstractMigration;

class CreateHorariosEmpleadas extends AbstractMigration
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
        $table->addColumn('fecha', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('fecha_actualizacion', 'datetime', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('sucursal_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('empleado_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('descanso', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => true,
        ]);
        $table->addColumn('lunes_entrada', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('lunes_salida', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('martes_entrada', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('martes_salida', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('miercoles_entrada', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('miercoles_salida', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('jueves_entrada', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('jueves_salida', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('viernes_entrada', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('viernes_salida', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('sabado_entrada', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('sabado_salida', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('domingo_entrada', 'time', [
            'default' => null,
            'null' => true,
        ]);
        $table->addColumn('domingo_salida', 'time', [
            'default' => null,
            'null' => true,
        ]);

        $table->create();
    }
}
