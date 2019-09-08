<?php
namespace BackOffice\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\Http\Cookie\Cookie;
use Cake\I18n\Time;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Security;

/**
 * CookieAuth component
 *
 * @property-read \Cake\Controller\Component\AuthComponent $Auth
 */
class CookieAuthComponent extends Component
{

	/**
	 * Table locator
	 */
	use LocatorAwareTrait;

	/**
	 * @var array Components
	 */
	public $components = [ 'Auth' ];

	/**
	 * @var \Cake\Http\ServerRequest
	 */
	public $request;

	/**
	 * @var \Cake\Http\Response
	 */
	public $response;

	/**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
    	'persistentLoginsModel' => 'BackOffice.PersistentLogins',
	    'cookieKey' => 'UPLR'
    ];

	/**
	 * @param array $config
	 */
    public function initialize( array $config ) {
	    $controller = $this->_registry->getController();
	    $this->response =& $controller->response;
	    $this->request =& $controller->request;
    }

	/**
	 * Events supported by this component.
	 *
	 * @return array
	 */
	public function implementedEvents()
	{
		return [
			'BackOffice.Auth.afterLogin' => 'onAfterLogin',
			'Auth.logout' => 'onAfterLogout',
			'Controller.initialize' => 'check'
		];
	}

	/**
	 * @param \Cake\Event\Event $event
	 */
	public function check(Event $event)
	{
		// Check already logged in
		if ($this->Auth->user()) return;

		$cookieKey = $this->getConfig('cookieKey');
		$table = $this->getTableLocator()->get($this->getConfig('persistentLoginsModel'));
		$users = $this->getTableLocator()->get('BackOffice.Users');

		/** @var \Cake\Controller\Controller $controller */
		$controller = $event->getSubject();

		// Get cookie data
		$cookieData = $controller->request->getCookie($cookieKey);

		// Check data
		if ($cookieData && isset($cookieData['series']) && isset($cookieData['token']) && isset($cookieData['email'])) {

			/** @var \BackOffice\Model\Entity\PersistentLogin $persistentLogin */
			$persistentLogin = $table->find()->where($cookieData)->first();
			$user = $persistentLogin ? $users->find()->where([ 'email' => $persistentLogin['email'] ])->first() : null;

			if ($persistentLogin && $user) {
				// Set from cookie flag
				$user['__from_cookie'] = true;
				// Set as login
				$this->Auth->setUser($user);
				// Generate new token
				$persistentLogin->token = Security::hash(Security::randomString(48), 'sha256');
				$table->save($persistentLogin);
				// Response new cookie
				$cookie = new Cookie(
					$cookieKey,
					$persistentLogin->toArray(),
					new Time('+30 days'),
					$controller->request->getAttribute('webroot'),
					'',
					false,
					false
				);
				$controller->response = $controller->response->withCookie($cookie);
			} else {
				// Delete cookies
				$table->deleteAll([ 'email' => $cookieData['email'] ]);
				$controller->response = $this->response->withExpiredCookie($cookieKey);
			}

		}
	}

	/**
	 * @param \Cake\Event\Event $event
	 * @param $user
	 */
	public function onAfterLogout(Event $event, $user)
	{
		$cookieKey = $this->getConfig('cookieKey');
		$table = $this->getTableLocator()->get($this->getConfig('persistentLoginsModel'));

		/** @var \Cake\Controller\Component\AuthComponent $authComponent */
		$authComponent = $event->getSubject();
		/** @var \Cake\Controller\Controller $controller */
		$controller = $authComponent->getController();

		// Delete old data
		$oldCookieData = $controller->request->getCookie($cookieKey);
		// If set delete it
		if ($oldCookieData) {
			$table->deleteAll([ 'series' => $oldCookieData['series'] ]);
		}

		// Delete cookie
		$controller->response = $this->response->withExpiredCookie($cookieKey);
	}

	/**
	 * Successfully login set cookie if set
	 *
	 * @param \Cake\Event\Event $event
	 */
	public function onAfterLogin(Event $event)
	{
		$cookieKey = $this->getConfig('cookieKey');
		$table = $this->getTableLocator()->get($this->getConfig('persistentLoginsModel'));
		$rememberMe = $event->getData('remember_me');
		$user = $event->getData('user');

		/** @var \Cake\Controller\Controller $controller */
		$controller = $event->getSubject();

		// Delete old data
		$oldCookieData = $controller->request->getCookie($cookieKey);
		// If set delete it
		if ($oldCookieData) {
			$table->deleteAll([ 'series' => $oldCookieData['series'] ]);
		}

		// Check remember me parameter
		if (!$rememberMe) {
			$controller->response = $this->response->withExpiredCookie($cookieKey);
			return;
		}

		// Create variables
		$series = Security::hash($user['id'] . $controller->request->clientIp() . Security::randomString(24), 'sha256');
		$token = Security::hash(Security::randomString(48), 'sha256');
		$email = $user['email'];

		// Save cookie
		$entity = $table->newEntity(compact('email', 'series', 'token'));
		$table->save($entity);

		// Response
		$cookie = new Cookie(
			$cookieKey,
			$entity->toArray(),
			new Time('+30 days'),
			$controller->request->getAttribute('webroot'),
			'',
			false,
			false
		);
		$controller->response = $controller->response->withCookie($cookie);
	}



}
