<?php

namespace zeus\mvc;

use zeus\http\Request;
use zeus\http\Response;
use zeus\sandbox\ConfigManager;

class View implements \ArrayAccess
{
    private static $hook = [];
    private static $config;

    private $tpl_path;
    private $tpl_args = [];

    private $request;
    private $response;

    /**
     * Hook 方法注入
     * @access public
     * @param string|array $method 方法名
     * @param mixed $callback callable
     * @return void
     */
    public static function hook($method, $callback = null)
    {
        if (is_array($method)) {
            self::$hook = array_merge(self::$hook, $method);
        } else {
            self::$hook[$method] = $callback;
        }
    }


    public function __construct(Request $request, Response $response, $tpl_path)
    {
        if (!isset(self::$config)) {
            self::$config = ConfigManager::config("view");
        }

        $this->request = $request;
        $this->response = $response;

        $this->template($tpl_path);
    }

    public function __call($method, $args)
    {
        if (array_key_exists($method, self::$hook)) {
            return call_user_func_array(self::$hook[$method], $args);
        }
    }

    public function __get($key)
    {
        if (isset($this->tpl_args[$key])) {
            return $this->tpl_args[$key];
        }
        return null;
    }

    public function __set($key, $val)
    {
        $this->tpl_args[$key] = $val;
    }

    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }


    /**
     * @param $template
     * @return string
     */
    public function fetch()
    {
        if (is_file($this->tpl_path)) {
            extract($this->tpl_args, EXTR_OVERWRITE);

            include $this->tpl_path;
        }
        $content = ob_get_contents();
        ob_clean();
        return $content;
    }

    public function display($content_type = "text/html", $code = 200)
    {
        $this->response->setCode($code)->setBody($this->fetch())->setHeader("Content-Type", $content_type)->send();
    }

    //模板内部include
    protected function template($template)
    {
        $this->tpl_path = realpath(self::$config['view.template_path'] . DS . self::$config['view.template_lang_dir'] . DS . $template . self::$config['view.template_extension']);
    }
}