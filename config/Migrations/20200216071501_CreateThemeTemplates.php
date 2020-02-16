<?php
use Migrations\AbstractMigration;

class CreateThemeTemplates extends AbstractMigration
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
        $table = $this->table('theme_templates');
        $table
			->addColumn('theme_id', 'integer', [ 'null' => false ])->addIndex([ 'theme_id' ], [ 'name' => 'theme_template_theme_id_index' ])
			->addColumn('type', 'string', [ 'length' => 45, 'null' => false ])->addIndex([ 'type' ], [ 'name' => 'theme_template_type_index' ])
			->addColumn('name', 'string', [ 'length' => 45, 'null' => false ])
			->addColumn('content', 'text', [ 'null' => true, 'default' => null ])
			->addColumn('created', 'datetime', [ 'null' => true, 'default' => null ])
			->addColumn('modified', 'datetime', [ 'null' => true, 'default' => null ])
			->addIndex([ 'theme_id', 'type' ], [ 'name' => 'theme_template_theme_type_index' ])
			->addIndex([ 'theme_id', 'type', 'name' ], [ 'unique' => true, 'name' => 'theme_template_theme_type_name' ]);
        $table->create();
    }
}
