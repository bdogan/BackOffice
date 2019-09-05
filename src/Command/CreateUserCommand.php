<?php
namespace BackOffice\Command;

use BackOffice\Model\Entity\User;
use BackOffice\Model\Table\UsersTable;
use BackOffice\Type\UserRole;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Utility\Security;

/**
 * CreateUser command.
 *
 * @property-read UsersTable Users
 */
class CreateUserCommand extends Command
{

	public function initialize() {
		parent::initialize();
		$this->loadModel('Users');
	}

	/**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);
		$parser
			->addArgument('name', [
				'help' => 'User name',
				'required' => true
			])
			->addArgument('email', [
				'help' => 'User email address',
				'required' => true
			])
			->addArgument('password', [
				'help' => 'User password',
				'required' => false
			])
			->addOption('role', [
				'help' => 'User role admin, user',
				'required' => false
			]);
        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {

    	// Get password
    	if ($args->hasArgument('password')) {
		    $password = $args->getArgument('password');
	    } else {
		    $password = Security::randomString(8);
	    }

    	// Password hasher
    	$hasher = new DefaultPasswordHasher();

    	// Create user entity
		$user = new User();
		$user->name = $args->getArgument('name');
    	$user->email = $args->getArgument('email');
    	$user->password = $hasher->hash($password);
    	$user->role = $args->hasOption('role') ? $args->getOption('role') : UserRole::ADMIN[0];

    	// Save user
    	$this->Users->saveOrFail($user);

	    $userData = $user->toArray() + [ 'password' => $password ];
    	$io->helper('Table')->output([ array_keys($userData), $userData ]);
    }
}
