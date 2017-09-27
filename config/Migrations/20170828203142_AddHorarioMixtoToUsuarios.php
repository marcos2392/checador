<?php
use Migrations\AbstractMigration;

class AddHorarioMixtoToUsuarios extends AbstractMigration
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
        $table = $this->table('usuarios');
        $table->addColumn('horario_mixto', 'boolean', [
            'default' => 0,
            'null' => false,
        ]);
        $table->update();
    }
}
