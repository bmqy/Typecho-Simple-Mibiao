<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}
/**
 * Typecho RestfulApi 插件, bmqy魔改以适配米表
 *
 * @package RestfulApi
 * @author MoeFront Studio
 * @version 1.2.0
 * @link https://moefront.github.io
 */
class RestfulApi_Plugin implements Typecho_Plugin_Interface
{
    const ACTION_CLASS = 'RestfulApi_Action';

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        \Typecho\Plugin::factory('index.php')->begin = __CLASS__ . '::addApiRoute';
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {
        // 禁用时不需要执行操作
        // 防止禁用
        $options = Typecho_Widget::widget('Widget_Options');
        $config = $options->plugin('RestfulApi');
        $config->activated = 1;
        $config->to($options)->save();
        Notice::alloc()->set(_t('此插件为系统级插件，不能被禁用！！！'), 'notice');
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {}

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {}

    public static function addApiRoute(){
        $routes = call_user_func(array(self::ACTION_CLASS, 'getRoutes'));
        foreach ($routes as $route) {
            Helper::addRoute($route['name'], $route['uri'], self::ACTION_CLASS, $route['action']);
        }
    }
}
