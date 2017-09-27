<?php
use Migrations\AbstractMigration;

class CreateVariables extends AbstractMigration
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
        $table = $this->table('variables');
        $table->addColumn('nombre', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('fecha_actualizacion', 'date', [
            'default' => null,
            'null' => true,
        ]);
        $table->create();
    }
}
