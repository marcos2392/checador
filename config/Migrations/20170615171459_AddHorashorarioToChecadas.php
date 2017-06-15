<?php
use Migrations\AbstractMigration;

class AddHorashorarioToChecadas extends AbstractMigration
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
        $table->addColumn('hrs_dia', 'float', [
            'default' => null,
            'null' => true,
        ]);
        $table->update();
    }
}
