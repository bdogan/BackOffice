<?php
use Migrations\AbstractMigration;

class CreatePersistentLogins extends AbstractMigration
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
	    $table = $this->table('persistent_logins', [ 'id' => false, 'primary_key' => 'series' ]);
	    $table
		    ->addColumn('email', 'string', [ 'limit' => 64, 'null' => false ])
		    ->addColumn('series', 'string', [ 'limit' => 64, 'null' => false ])
		    ->addColumn('token', 'string', [ 'limit' => 64, 'null' => false ])
		    ->create();
    }
}
