<?php

use Widget\Notice;

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 访问计数
 *
 * @package VisitCounter
 * @author bmqy
 * @version 1.0.0
 * @link https://www.bmqy.net
 */

class VisitCounter_Plugin implements Typecho_Plugin_Interface
{
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'updateVisitCount');
    }

    public static function deactivate()
    {
        // 禁用时不需要执行操作
        // 防止禁用
        $options = Typecho_Widget::widget('Widget_Options');
        $config = $options->plugin('VisitCounter');
        $config->activated = 1;
        $config->to($options)->save();
        Notice::alloc()->set(_t('此插件为系统级插件，不能被禁用！！！'), 'notice');
    }

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        // 配置页面，如果有需要的话
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
        // 用户个人配置页面，如果有需要的话
    }

    public static function updateVisitCount()
    {
        $request = Typecho_Request::getInstance();

        // 获取url参数中的域名
        $domain = $request->get('referrer', '');

        if (empty($domain)) {
            return false;
        }
        // 更新域名访问计数
        $db = Typecho_Db::get();
        $row = $db->fetchRow($db->select('visitCount')->from('table.contents')->where('title = ?', $domain));
        $currentVisitCount = $row['visitCount'];
        $newVisitCount = $currentVisitCount + 1;

        $db->query($db->update('table.contents')->rows(array('visitCount' => $newVisitCount))->where('title = ?', $domain));
    }
}
