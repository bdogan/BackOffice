<?php
namespace BackOffice\Middleware;

use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\ServerRequest;
use Cake\Utility\Hash;
use Psr\Http\Message\ResponseInterface;

/**
 * Page middleware
 */
class PageMiddleware
{
	/**
	 * @var string BackOffice root path
	 */
	private $backOfficeRootPath = '_admin';

	/**
	 * @var array Pages
	 */
	private $pages;

	/**
	 * @var string Content Path
	 */
	private $contentPath = 'Content';

	/**
	 * @var \Closure RawRenderer function
	 */
	private $_rawRenderer;

	/**
	 * PageMiddleware constructor.
	 *
	 * @param $pages
	 */
	public function __construct($rootPath, $pages, $contentPath = 'Content') {
		$this->contentPath = $contentPath;
		$this->backOfficeRootPath = $rootPath;
    	$this->pages = $pages;
    }

	/**
     * Invoke method.
     *
     * @param \Cake\Http\ServerRequest $request The request.
     * @param \Psr\Http\Message\ResponseInterface $response The response.
     * @param callable $next Callback to invoke the next middleware.
     * @return \Psr\Http\Message\ResponseInterface A response
     */
    public function __invoke(ServerRequest $request, ResponseInterface $response, $next)
    {
		// Get active page
	    $activePage = $this->getActivePage($request);
	    if ($activePage) {

		    // Set into request
	        $request = $request->withParam('page', $activePage);

	    }
    	return $next($request, $response);
    }

	/**
	 * Controller beforeRender event
	 * @param \Cake\Event\Event $event
	 */
    public function onControllerBeforeRender( Event $event )
    {
    	/** @var \Cake\Controller\Controller $controller */
    	$controller = $event->subject;

    	// Set Content Path
	    //$controller->viewBuilder()->setTemplatePath($this->contentPath);

    	// Get active page
	    $page = $controller->request->getParam('page');

		//$controller->setPlugin(null);

	    // Set layout
	    $controller->viewBuilder()->setLayout(Hash::get($page, 'data.layout', 'default'));

	    // Set template
	    $controller->viewBuilder()->setTemplate(Hash::get($page, 'data.template', 'page'));
    }

	/**
	 * Returns active page
	 *
	 * @param ServerRequest $request
	 * @return array|bool|mixed
	 */
	private function getActivePage(ServerRequest $request)
	{
		$matchedRoute = $request->getParam('_matchedRoute');
		foreach ($this->pages as $key => $page) {
			if ($page['data']['slug'] === $matchedRoute) {
				return [ '_key' => $key ] + $page;
			}
		}
		return false;
	}
}
