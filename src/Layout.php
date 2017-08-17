<?php
namespace Leray\Yaf;

use Yaf\Plugin_Abstract;
use Yaf\Request_Abstract;
use Yaf\Response_Abstract;

use Yaf\View\Simple;

class Layout extends Plugin_Abstract
{
	private $layoutDir;
    private $layoutFile;

    private $layout;


    private $vars;

    private static $myLayout;

    public function __construct($layoutDir, $layoutFile = 'default.phtml')
    {
        $this->layoutDir = $layoutDir;
        $this->layoutFile = $layoutFile;
    }

    public function __set($key, $value)
    {
        // $this->layout->assign($key, $value);
        $this->vars[$key] = $value;
    }

    public static function getInstance($layoutDir, $layoutFile = 'default.phtml')
    {
        return self::$myLayout ?: new self($layoutDir, $layoutFile);
    }

    public function dispatchLoopShutdown(Request_Abstract $request, Response_Abstract $response)
    {
    }

    public function postDispatch(Request_Abstract $request, Response_Abstract $response)
    {
    	$layout = new Simple($this->layoutDir);

        // 获取已响应内容
    	$body = $response->getBody();

    	$layout->content = $body;

        $layout->module = $request->getModuleName();
        $layout->controller = $request->getControllerName();
        $layout->action = $request->getActionName();


        $layout->assign($this->vars);

    	$output = $layout->render($this->layoutFile);
    	// 输出数据
    	$response->setBody($output);
	}
}