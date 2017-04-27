<?php
use Migrations\AbstractMigration;

class AddFolioToSucursales extends AbstractMigration
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
        $table = $this->table('sucursales');
        $table->addColumn('folio', 'integer', [
            'limit' => 11,
            'null' => false,
        ]);
        $table->update();
    }
}
