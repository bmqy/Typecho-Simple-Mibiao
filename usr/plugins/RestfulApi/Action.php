<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

class RestfulApi_Action extends Typecho_Widget implements Widget_Interface_Do
{
    /**
     * @var Typecho_Config
     */
    private $config;

    /**
     * @var Typecho_Db
     */
    private $db;

    /**
     * @var Widget_Options
     */
    private $options;

    /**
     * @var array
     */
    private $httpParams;

    public function __construct($request, $response, $params = null)
    {
        parent::__construct($request, $response, $params);

        $this->db = Typecho_Db::get();
        $this->options = $this->widget('Widget_Options');
    }

    /**
     * 获取路由参数
     *
     * @return array
     */
    public static function getRoutes()
    {
        $routes = array();
        $reflectClass = new ReflectionClass(__CLASS__);
        $prefix = defined('__TYPECHO_RESTFUL_PREFIX__') ? __TYPECHO_RESTFUL_PREFIX__ : '/api/';

        foreach ($reflectClass->getMethods(ReflectionMethod::IS_PUBLIC) as $reflectMethod) {
            $methodName = $reflectMethod->getName();

            preg_match('/(.*)Action$/', $methodName, $matches);
            if (isset($matches[1])) {
                array_push($routes, array(
                    'action' => $matches[0],
                    'name' => 'rest_' . $matches[1],
                    'shortName' => $matches[1],
                    'uri' => $prefix . $matches[1],
                    'description' => trim(str_replace(
                        array('/', '*'),
                        '',
                        substr($reflectMethod->getDocComment(), 0, strpos($reflectMethod->getDocComment(), '@'))
                    )),
                ));
            }
        }
        return $routes;
    }

    public function execute()
    {
        $this->sendCORS();
        $this->parseRequest();
    }

    public function action()
    {}

    /**
     * 发送跨域 HEADER
     *
     * @return void
     */
    private function sendCORS()
    {
        $httpOrigin = $this->request->getServer('HTTP_ORIGIN');
        $allowedHttpOrigins = explode("\n", str_replace("\r", "", "*"));

        if (!$httpOrigin) {
            return;
        }

        if (in_array($httpOrigin, $allowedHttpOrigins)) {
            $this->response->setHeader('Access-Control-Allow-Origin', $httpOrigin);
        }

        if (strtolower($this->request->getServer('REQUEST_METHOD')) == 'options') {
            Typecho_Response::setStatus(204);
            $this->response->setHeader('Access-Control-Allow-Headers', 'Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type');
            $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            exit;
        }
    }

    /**
     * 解析请求参数
     *
     * @return void
     */
    private function parseRequest()
    {
        if ($this->request->isPost()) {
            $data = file_get_contents('php://input');
            $data = json_decode($data, true);
            if (json_last_error() != JSON_ERROR_NONE) {
                $this->throwError('Parse JSON error');
            }
            $this->httpParams = $data;
        }
    }

    /**
     * 获取 GET/POST 参数
     *
     * @param string $key 目标参数的 key
     * @param mixed $default 返回的默认值
     * @return mixed
     */
    private function getParams($key, $default = null)
    {
        if ($this->request->isGet()) {
            return $this->request->get($key, $default);
        }
        if (!isset($this->httpParams[$key])) {
            return $default;
        }
        return $this->httpParams[$key];
    }

    /**
     * 以 JSON 格式返回错误
     *
     * @param string $message 错误信息
     * @param integer $status HTTP 状态码
     * @return void
     */
    private function throwError($message = 'unknown', $status = 400)
    {
        $this->response->setStatus($status);
        $this->response->throwJson(array(
            'status' => 'error',
            'message' => $message,
            'data' => null,
        ));
    }

    /**
     * 以 JSON 格式响应请求的信息
     *
     * @param mixed $data 要返回给用户的内容
     * @return void
     */
    private function throwData($data)
    {
        $this->response->throwJson(array(
            'status' => 'success',
            'message' => '',
            'data' => $data,
        ));
    }

    /**
     * 锁定 API 请求方式
     *
     * @param string $method 请求方式 (get/post)
     * @return void
     */
    private function lockMethod($method)
    {
        $method = strtolower($method);
        if (strtolower($this->request->getServer('REQUEST_METHOD')) != $method) {
            $this->throwError('method not allowed', 405);
        }
    }

    /**
     * show errors when accessing a disabled API
     *
     * @param string $route
     * @return void
     */
    private function checkState($route)
    {
        if ($route != 'post') {
            $this->throwError('This API has been disabled.', 403);
        }
    }

    /**
     * 获取域名详情接口
     *
     * @return void
     */
    public function postAction()
    {
        $this->lockMethod('get');
        $this->checkState('post');


        $slug = $this->getParams('slug', '');
        $cid = $this->getParams('cid', '');

        $select = $this->db
            ->select('title','cid', 'type', 'created', 'status', 'visitCount', 'description')
            ->from('table.contents');

        if (is_numeric($cid)) {
            $select->where('cid = ?', $cid);
        } else {
            $select->where('slug = ?', $slug);
        }

        $result = $this->db->fetchRow($select);

        if (!empty($result) && count($result) != 0) {
            $this->throwData($result);
        } else {
            $this->throwError('域名不存在！', 404);
        }
    }
}
