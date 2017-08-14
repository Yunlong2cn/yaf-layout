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

    public function __construct($layoutDir, $layoutFile = 'default.phtml')
    {
        $this->layoutDir = $layoutDir;
        $this->layoutFile = $layoutFile;

        $this->layout = new Simple($this->layoutDir);
    }

    public function __set($key, $value)
    {
        $this->layout->assign($key, $value);
    }

    

    public function dispatchLoopShutdown(Request_Abstract $request, Response_Abstract $response)
    {
    }

    public function postDispatch(Request_Abstract $request, Response_Abstract $response)
    {
    	// 获取已响应内容
    	$body = $response->getBody();

    	$this->layout->content = $body;

        $this->layout->module = $request->getModuleName();
        $this->layout->controller = $request->getControllerName();
        $this->layout->action = $request->getActionName();

    	$output = $this->layout->render($this->layoutFile);
    	// 输出数据
    	$response->setBody($output);
	}
}