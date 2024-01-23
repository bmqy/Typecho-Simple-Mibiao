<?php

namespace Widget\Options;

use Typecho\Db\Exception;
use Typecho\I18n\GetText;
use Typecho\Widget\Helper\Form;
use Widget\ActionInterface;
use Widget\Base\Options;
use Widget\Notice;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * 基本设置组件
 *
 * @author qining
 * @category typecho
 * @package Widget
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 */
class Extend extends Options implements ActionInterface
{
    /**
     * 检查是否在语言列表中
     *
     * @param string $lang
     * @return bool
     */
    public function checkLang(string $lang): bool
    {
        $langs = self::getLangs();
        return isset($langs[$lang]);
    }

    /**
     * 获取语言列表
     *
     * @return array
     */
    public static function getLangs(): array
    {
        $dir = defined('__TYPECHO_LANG_DIR__') ? __TYPECHO_LANG_DIR__ : __TYPECHO_ROOT_DIR__ . '/usr/langs';
        $files = glob($dir . '/*.mo');
        $langs = ['zh_CN' => '简体中文'];

        if (!empty($files)) {
            foreach ($files as $file) {
                $getText = new GetText($file, false);
                [$name] = explode('.', basename($file));
                $title = $getText->translate('lang', $count);
                $langs[$name] = $count > - 1 ? $title : $name;
            }

            ksort($langs);
        }

        return $langs;
    }

    /**
     * 过滤掉可执行的后缀名
     *
     * @param string $ext
     * @return boolean
     */
    public function removeShell(string $ext): bool
    {
        return !preg_match("/^(php|php4|php5|sh|asp|jsp|rb|py|pl|dll|exe|bat)$/i", $ext);
    }

    /**
     * 执行更新动作
     *
     * @throws Exception
     */
    public function updateExtendSettings()
    {
        /** 验证格式 */
        if ($this->form()->validate()) {
            $this->response->goBack();
        }

        $settings = $this->request->from(
            'logo',
            'favicon',
            'statisticalCode',
            'cpsInfo',
        );
        foreach ($settings as $name => $value) {
            $this->update(['value' => $value], $this->db->sql()->where('name = ?', $name));
        }

        Notice::alloc()->set(_t("设置已经保存"), 'success');
        $this->response->goBack();
    }

    /**
     * 输出表单结构
     *
     * @return Form
     */
    public function form(): Form
    {
        /** 构建表格 */
        $form = new Form($this->security->getIndex('/action/options-extend'), Form::POST_METHOD);

        /** favicon */
        $logo = new Form\Element\Text('logo', null, $this->options->logo, _t('Logo'), _t('站点 LOGO 地址'));
        $logo->input->setAttribute('class', 'w-100');
        $form->addInput($logo->addRule('url', _t('请填写一个合法的URL地址')));
        /** Favicon */
        $favicon = new Form\Element\Text('favicon', null, $this->options->favicon, _t('Favicon'), _t('站点 Favicon 地址'));
        $favicon->input->setAttribute('class', 'w-100');
        $form->addInput($favicon->addRule('url', _t('请填写一个合法的URL地址')));
        /** 统计代码 */
        $statisticalCode = new Form\Element\Textarea('statisticalCode', null, $this->options->statisticalCode, _t('统计代码'), _t('请粘贴你的统计代码，包括script标签。'));
        $statisticalCode->input->setAttribute('class', 'w-100');
        $form->addInput($statisticalCode);
        /** 空间服务商 */
        $cpsInfo = new Form\Element\Textarea('cpsInfo', null, $this->options->cpsInfo, _t('空间服务商'), _t('请填入你的空间服务商信息，也可以是邀请文案。'));
        $cpsInfo->input->setAttribute('class', 'w-100');
        $form->addInput($cpsInfo);

        /** 提交按钮 */
        $submit = new Form\Element\Submit('submit', null, _t('保存设置'));
        $submit->input->setAttribute('class', 'btn primary');
        $form->addItem($submit);

        return $form;
    }

    /**
     * 绑定动作
     */
    public function action()
    {
        $this->user->pass('administrator');
        $this->security->protect();
        $this->on($this->request->isPost())->updateExtendSettings();
        $this->response->redirect($this->options->adminUrl);
    }
}
