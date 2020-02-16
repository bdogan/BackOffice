<?php
use Migrations\AbstractMigration;

class CreateThemes extends AbstractMigration
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
        $table = $this->table('themes');
		$table
			->addColumn('name', 'string', [ 'limit' => 100, 'null' => false ])
			->addColumn('alias', 'string', [ 'limit' => 25, 'null' => false ])->addIndex([ 'alias' ], [ 'unique' => true, 'name' => 'theme_alias_index' ])
			->addColumn('is_active', 'boolean', [ 'null' => false ]);
        $table->create();
    }
}
