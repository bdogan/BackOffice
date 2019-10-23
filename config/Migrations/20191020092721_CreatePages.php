<?php
use Migrations\AbstractMigration;

class CreatePages extends AbstractMigration
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
        $table = $this->table('pages');
        $table
	        ->addColumn('name', 'string', [ 'limit' => 250, 'null' => false ])
	        ->addColumn('template', 'string', [ 'limit' => 100, 'null' => false, 'default' => 'page.ctp' ])
	        ->addColumn('body', 'text', [ 'null' => true, 'default' => null ])
	        ->addColumn('published_after', 'datetime', [ 'null' => true, 'default' => null ])
	        /** SEO */
	        ->addColumn('slug', 'string', [ 'limit' => 100, 'null' => false ])
	        ->addColumn('title', 'string', [ 'limit' => 100, 'null' => false ])
	        ->addColumn('canonical', 'string', [ 'limit' => 100, 'null' => false ])
	        ->addColumn('description', 'string', [ 'limit' => 250, 'null' => true, 'default' => '' ])
	        /** SYSTEM */
	        ->addColumn('type', 'string', [ 'null' => false, 'default' => 'simple' ])
	        ->addColumn('is_system_default', 'boolean', [ 'null' => false, 'default' => false ])
	        ->addColumn('created', 'datetime', [ 'null' => true, 'default' => null ])
	        ->addColumn('modified', 'datetime', [ 'null' => true, 'default' => null ]);
        $table->create();
    }
}
