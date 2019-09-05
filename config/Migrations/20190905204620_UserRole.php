<?php
use Migrations\AbstractMigration;

class UserRole extends AbstractMigration
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
		    ->addColumn('role', 'string', [ 'limit' => 50, 'null' => true, 'default' => \BackOffice\UserRole::USER[0] ])
		    ->update();
    }
}
