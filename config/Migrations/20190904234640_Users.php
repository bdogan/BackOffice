<?php
use Migrations\AbstractMigration;

class Users extends AbstractMigration
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
	    $table = $this->table('users');
	    $table
		    ->addColumn('name', 'string', [ 'limit' => 50, 'null' => false ])
		    ->addColumn('email', 'string', [ 'limit' => 50, 'null' => false ])
		    ->addColumn('password', 'string', [ 'limit' => 255, 'null' => false ])
		    ->addColumn('last_login_ip', 'string', [ 'null' => true, 'default' => null ])
		    ->addColumn('last_login', 'datetime', [ 'null' => true, 'default' => null ])
		    ->addColumn('created', 'datetime', [ 'null' => true, 'default' => null ])
		    ->addColumn('modified', 'datetime', [ 'null' => true, 'default' => null ])
		    ->create();
    }
}
