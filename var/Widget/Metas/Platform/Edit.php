<?php

namespace Widget\Metas\Platform;

use Typecho\Common;
use Typecho\Db\Exception;
use Typecho\Widget\Helper\Form;
use Widget\Base\Metas;
use Widget\ActionInterface;
use Widget\Notice;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * 平台编辑组件
 *
 * @author qining
 * @category typecho
 * @package Widget
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 */
class Edit extends Metas implements ActionInterface
{
    /**
     * 入口函数
     */
    public function execute()
    {
        /** 编辑以上权限 */
        $this->user->pass('editor');
    }

    /**
     * 判断平台是否存在
     *
     * @param integer $mid 平台主键
     * @return boolean
     * @throws Exception
     */
    public function platformExists(int $mid): bool
    {
        $platform = $this->db->fetchRow($this->db->select()
            ->from('table.metas')
            ->where('type = ?', 'platform')
            ->where('mid = ?', $mid)->limit(1));

        return (bool)$platform;
    }

    /**
     * 判断平台名称是否存在
     *
     * @param string $name 平台名称
     * @return boolean
     * @throws Exception
     */
    public function nameExists(string $name): bool
    {
        $select = $this->db->select()
            ->from('table.metas')
            ->where('type = ?', 'platform')
            ->where('name = ?', $name)
            ->limit(1);

        if ($this->request->mid) {
            $select->where('mid <> ?', $this->request->filter('int')->mid);
        }

        $platform = $this->db->fetchRow($select);
        return !$platform;
    }

    /**
     * 判断平台名转换到缩略名后是否合法
     *
     * @param string $name 平台名
     * @return boolean
     */
    public function nameToSlug(string $name): bool
    {
        if (empty($this->request->slug)) {
            $slug = Common::slugName($name);
            if (empty($slug) || !$this->slugExists($name)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 判断平台缩略名是否存在
     *
     * @param string $slug 缩略名
     * @return boolean
     * @throws Exception
     */
    public function slugExists(string $slug): bool
    {
        $select = $this->db->select()
            ->from('table.metas')
            ->where('type = ?', 'platform')
            ->where('slug = ?', Common::slugName($slug))
            ->limit(1);

        if ($this->request->mid) {
            $select->where('mid <> ?', $this->request->mid);
        }

        $platform = $this->db->fetchRow($select);
        return !$platform;
    }

    /**
     * 插入平台
     *
     * @throws Exception
     */
    public function insertPlatform()
    {
        if ($this->form('insert')->validate()) {
            $this->response->goBack();
        }

        /** 取出数据 */
        $platform = $this->request->from('name', 'slug');
        $platform['type'] = 'platform';
        $platform['slug'] = Common::slugName(empty($platform['slug']) ? $platform['name'] : $platform['slug']);

        /** 插入数据 */
        $platform['mid'] = $this->insert($platform);
        $this->push($platform);

        /** 设置高亮 */
        Notice::alloc()->highlight($this->theId);

        /** 提示信息 */
        Notice::alloc()->set(
            _t('平台 <a href="%s">%s</a> 已经被增加', $this->permalink, $this->name),
            'success'
        );

        /** 转向原页 */
        $this->response->redirect(Common::url('manage-platforms.php', $this->options->adminUrl));
    }

    /**
     * 生成表单
     *
     * @param string|null $action 表单动作
     * @return Form
     * @throws Exception
     */
    public function form(?string $action = null): Form
    {
        /** 构建表格 */
        $form = new Form($this->security->getIndex('/action/metas-platform-edit'), Form::POST_METHOD);

        /** 平台名称 */
        $name = new Form\Element\Text(
            'name',
            null,
            null,
            _t('平台名称') . ' *',
            _t('这是平台在站点中显示的名称.如 "阿里云".')
        );
        $form->addInput($name);

        /** 平台缩略名 */
        $slug = new Form\Element\Text(
            'slug',
            null,
            null,
            _t('平台缩略名'),
            _t('平台缩略名用于创建友好的链接形式, 如果留空则默认使用平台名称.')
        );
        $form->addInput($slug);

        /** 平台动作 */
        $do = new Form\Element\Hidden('do');
        $form->addInput($do);

        /** 平台主键 */
        $mid = new Form\Element\Hidden('mid');
        $form->addInput($mid);

        /** 提交按钮 */
        $submit = new Form\Element\Submit();
        $submit->input->setAttribute('class', 'btn primary');
        $form->addItem($submit);

        if (isset($this->request->mid) && 'insert' != $action) {
            /** 更新模式 */
            $meta = $this->db->fetchRow($this->select()
                ->where('mid = ?', $this->request->mid)
                ->where('type = ?', 'platform')->limit(1));

            if (!$meta) {
                $this->response->redirect(Common::url('manage-platforms.php', $this->options->adminUrl));
            }

            $name->value($meta['name']);
            $slug->value($meta['slug']);
            $do->value('update');
            $mid->value($meta['mid']);
            $submit->value(_t('编辑平台'));
            $_action = 'update';
        } else {
            $do->value('insert');
            $submit->value(_t('增加平台'));
            $_action = 'insert';
        }

        if (empty($action)) {
            $action = $_action;
        }

        /** 给表单增加规则 */
        if ('insert' == $action || 'update' == $action) {
            $name->addRule('required', _t('必须填写平台名称'));
            $name->addRule([$this, 'nameExists'], _t('平台名称已经存在'));
            $name->addRule([$this, 'nameToSlug'], _t('平台名称无法被转换为缩略名'));
            $name->addRule('xssCheck', _t('请不要平台名称中使用特殊字符'));
            $slug->addRule([$this, 'slugExists'], _t('缩略名已经存在'));
            $slug->addRule('xssCheck', _t('请不要在缩略名中使用特殊字符'));
        }

        if ('update' == $action) {
            $mid->addRule('required', _t('平台主键不存在'));
            $mid->addRule([$this, 'platformExists'], _t('平台不存在'));
        }

        return $form;
    }

    /**
     * 更新平台
     *
     * @throws Exception
     */
    public function updatePlatform()
    {
        if ($this->form('update')->validate()) {
            $this->response->goBack();
        }

        /** 取出数据 */
        $platform = $this->request->from('name', 'slug', 'mid');
        $platform['type'] = 'platform';
        $platform['slug'] = Common::slugName(empty($platform['slug']) ? $platform['name'] : $platform['slug']);

        /** 更新数据 */
        $this->update($platform, $this->db->sql()->where('mid = ?', $this->request->filter('int')->mid));
        $this->push($platform);

        /** 设置高亮 */
        Notice::alloc()->highlight($this->theId);

        /** 提示信息 */
        Notice::alloc()->set(
            _t('平台 <a href="%s">%s</a> 已经被更新', $this->permalink, $this->name),
            'success'
        );

        /** 转向原页 */
        $this->response->redirect(Common::url('manage-platforms.php', $this->options->adminUrl));
    }

    /**
     * 删除平台
     *
     * @throws Exception
     */
    public function deletePlatform()
    {
        $platforms = $this->request->filter('int')->getArray('mid');
        $deleteCount = 0;

        if ($platforms && is_array($platforms)) {
            foreach ($platforms as $platform) {
                if ($this->delete($this->db->sql()->where('mid = ?', $platform))) {
                    $this->db->query($this->db->delete('table.relationships')->where('mid = ?', $platform));
                    $deleteCount++;
                }
            }
        }

        /** 提示信息 */
        Notice::alloc()->set(
            $deleteCount > 0 ? _t('平台已经删除') : _t('没有平台被删除'),
            $deleteCount > 0 ? 'success' : 'notice'
        );

        /** 转向原页 */
        $this->response->redirect(Common::url('manage-platforms.php', $this->options->adminUrl));
    }

    /**
     * 合并平台
     *
     * @throws Exception
     */
    public function mergePlatform()
    {
        if (empty($this->request->merge)) {
            Notice::alloc()->set(_t('请填写需要合并到的平台'), 'notice');
            $this->response->goBack();
        }

        $merge = $this->scanPlatforms($this->request->merge);
        if (empty($merge)) {
            Notice::alloc()->set(_t('合并到的平台名不合法'), 'error');
            $this->response->goBack();
        }

        $platforms = $this->request->filter('int')->getArray('mid');

        if ($platforms) {
            $this->merge($merge, 'platform', $platforms);

            /** 提示信息 */
            Notice::alloc()->set(_t('平台已经合并'), 'success');
        } else {
            Notice::alloc()->set(_t('没有选择任何平台'), 'notice');
        }

        /** 转向原页 */
        $this->response->redirect(Common::url('manage-platforms.php', $this->options->adminUrl));
    }

    /**
     * 刷新平台
     *
     * @access public
     * @return void
     * @throws Exception
     */
    public function refreshPlatform()
    {
        $platforms = $this->request->filter('int')->getArray('mid');
        if ($platforms) {
            foreach ($platforms as $platform) {
                $this->refreshCountByTypeAndStatus($platform, 'post', 'publish');
            }

            // 自动清理平台
            $this->clearPlatforms();

            Notice::alloc()->set(_t('平台刷新已经完成'), 'success');
        } else {
            Notice::alloc()->set(_t('没有选择任何平台'), 'notice');
        }

        /** 转向原页 */
        $this->response->goBack();
    }

    /**
     * 入口函数,绑定事件
     *
     * @access public
     * @return void
     * @throws Exception
     */
    public function action()
    {
        $this->security->protect();
        $this->on($this->request->is('do=insert'))->insertPlatform();
        $this->on($this->request->is('do=update'))->updatePlatform();
        $this->on($this->request->is('do=delete'))->deletePlatform();
        $this->on($this->request->is('do=merge'))->mergePlatform();
        $this->on($this->request->is('do=refresh'))->refreshPlatform();
        $this->response->redirect($this->options->adminUrl);
    }
}
