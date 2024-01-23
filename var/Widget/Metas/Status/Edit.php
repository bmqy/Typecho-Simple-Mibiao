<?php

namespace Widget\Metas\Status;

use Typecho\Common;
use Typecho\Db\Exception;
use Typecho\Validate;
use Typecho\Widget\Helper\Form;
use Widget\Base\Metas;
use Widget\ActionInterface;
use Widget\Notice;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * 编辑状态组件
 *
 * @status typecho
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
     * 判断状态是否存在
     *
     * @param integer $mid 状态主键
     * @return boolean
     * @throws Exception
     */
    public function statusExists(int $mid): bool
    {
        $status = $this->db->fetchRow($this->db->select()
            ->from('table.metas')
            ->where('type = ?', 'status')
            ->where('mid = ?', $mid)->limit(1));

        return (bool)$status;
    }

    /**
     * 判断状态名称是否存在
     *
     * @param string $name 状态名称
     * @return boolean
     * @throws Exception
     */
    public function nameExists(string $name): bool
    {
        $select = $this->db->select()
            ->from('table.metas')
            ->where('type = ?', 'status')
            ->where('name = ?', $name)
            ->limit(1);

        if ($this->request->mid) {
            $select->where('mid <> ?', $this->request->mid);
        }

        $status = $this->db->fetchRow($select);
        return !$status;
    }

    /**
     * 判断状态名转换到缩略名后是否合法
     *
     * @param string $name 状态名
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
     * 判断状态缩略名是否存在
     *
     * @param string $slug 缩略名
     * @return boolean
     * @throws Exception
     */
    public function slugExists(string $slug): bool
    {
        $select = $this->db->select()
            ->from('table.metas')
            ->where('type = ?', 'status')
            ->where('slug = ?', Common::slugName($slug))
            ->limit(1);

        if ($this->request->mid) {
            $select->where('mid <> ?', $this->request->mid);
        }

        $status = $this->db->fetchRow($select);
        return !$status;
    }

    /**
     * 增加状态
     *
     * @throws Exception
     */
    public function insertStatus()
    {
        if ($this->form('insert')->validate()) {
            $this->response->goBack();
        }

        /** 取出数据 */
        $status = $this->request->from('name', 'slug', 'description', 'parent');

        $status['slug'] = Common::slugName(empty($status['slug']) ? $status['name'] : $status['slug']);
        $status['type'] = 'status';
        $status['order'] = $this->getMaxOrder('status', $status['parent']) + 1;

        /** 插入数据 */
        $status['mid'] = $this->insert($status);
        $this->push($status);

        /** 设置高亮 */
        Notice::alloc()->highlight($this->theId);

        /** 提示信息 */
        Notice::alloc()->set(
            _t('状态 <a href="%s">%s</a> 已经被增加', $this->permalink, $this->name),
            'success'
        );

        /** 转向原页 */
        $this->response->redirect(Common::url('manage-categories.php'
            . ($status['parent'] ? '?parent=' . $status['parent'] : ''), $this->options->adminUrl));
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
        $form = new Form($this->security->getIndex('/action/metas-status-edit'), Form::POST_METHOD);

        /** 状态名称 */
        $name = new Form\Element\Text('name', null, null, _t('状态名称') . ' *');
        $form->addInput($name);

        /** 状态缩略名 */
        $slug = new Form\Element\Text(
            'slug',
            null,
            null,
            _t('状态缩略名'),
            _t('状态缩略名用于创建友好的链接形式, 建议使用字母, 数字, 下划线和横杠.')
        );
        $form->addInput($slug);

        /** 状态描述 */
        $description = new Form\Element\Textarea(
            'description',
            null,
            null,
            _t('状态描述'),
            _t('此文字用于描述状态, 在有的主题中它会被显示.')
        );
        $form->addInput($description);

        /** 状态动作 */
        $do = new Form\Element\Hidden('do');
        $form->addInput($do);

        /** 状态主键 */
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
                ->where('type = ?', 'status')->limit(1));

            if (!$meta) {
                $this->response->redirect(Common::url('manage-categories.php', $this->options->adminUrl));
            }

            $name->value($meta['name']);
            $slug->value($meta['slug']);
            $description->value($meta['description']);
            $do->value('update');
            $mid->value($meta['mid']);
            $submit->value(_t('编辑状态'));
            $_action = 'update';
        } else {
            $do->value('insert');
            $submit->value(_t('增加状态'));
            $_action = 'insert';
        }

        if (empty($action)) {
            $action = $_action;
        }

        /** 给表单增加规则 */
        if ('insert' == $action || 'update' == $action) {
            $name->addRule('required', _t('必须填写状态名称'));
            $name->addRule([$this, 'nameExists'], _t('状态名称已经存在'));
            $name->addRule([$this, 'nameToSlug'], _t('状态名称无法被转换为缩略名'));
            $name->addRule('xssCheck', _t('请不要在状态名称中使用特殊字符'));
            $slug->addRule([$this, 'slugExists'], _t('缩略名已经存在'));
            $slug->addRule('xssCheck', _t('请不要在缩略名中使用特殊字符'));
        }

        if ('update' == $action) {
            $mid->addRule('required', _t('状态主键不存在'));
            $mid->addRule([$this, 'statusExists'], _t('状态不存在'));
        }

        return $form;
    }

    /**
     * 更新状态
     *
     * @throws Exception
     */
    public function updateStatus()
    {
        if ($this->form('update')->validate()) {
            $this->response->goBack();
        }

        /** 取出数据 */
        $status = $this->request->from('name', 'slug', 'description', 'parent');
        $status['mid'] = $this->request->mid;
        $status['slug'] = Common::slugName(empty($status['slug']) ? $status['name'] : $status['slug']);
        $status['type'] = 'status';

        /** 更新数据 */
        $this->update($status, $this->db->sql()->where('mid = ?', $this->request->filter('int')->mid));
        $this->push($status);

        /** 设置高亮 */
        Notice::alloc()->highlight($this->theId);

        /** 提示信息 */
        Notice::alloc()
            ->set(_t('状态 <a href="%s">%s</a> 已经被更新', $this->permalink, $this->name), 'success');

        /** 转向原页 */
        $this->response->redirect(Common::url('manage-categories.php'
            . ($status['parent'] ? '?parent=' . $status['parent'] : ''), $this->options->adminUrl));
    }

    /**
     * 删除状态
     *
     * @access public
     * @return void
     * @throws Exception
     */
    public function deleteStatus()
    {
        $categories = $this->request->filter('int')->getArray('mid');
        $deleteCount = 0;

        /** 提示信息 */
        Notice::alloc()
            ->set($deleteCount > 0 ? _t('状态已经删除') : _t('没有状态被删除'), $deleteCount > 0 ? 'success' : 'notice');

        /** 转向原页 */
        $this->response->goBack();
    }

    /**
     * 合并状态
     */
    public function mergeStatus()
    {
        /** 验证数据 */
        $validator = new Validate();
        $validator->addRule('merge', 'required', _t('状态主键不存在'));
        $validator->addRule('merge', [$this, 'statusExists'], _t('请选择需要合并的状态'));

        if ($error = $validator->run($this->request->from('merge'))) {
            Notice::alloc()->set($error, 'error');
            $this->response->goBack();
        }

        $merge = $this->request->merge;
        $categories = $this->request->filter('int')->getArray('mid');

        if ($categories) {
            $this->merge($merge, 'status', $categories);

            /** 提示信息 */
            Notice::alloc()->set(_t('状态已经合并'), 'success');
        } else {
            Notice::alloc()->set(_t('没有选择任何状态'), 'notice');
        }

        /** 转向原页 */
        $this->response->goBack();
    }

    /**
     * 状态排序
     */
    public function sortStatus()
    {
        $categories = $this->request->filter('int')->getArray('mid');
        if ($categories) {
            $this->sort($categories, 'status');
        }

        if (!$this->request->isAjax()) {
            /** 转向原页 */
            $this->response->redirect(Common::url('manage-categories.php', $this->options->adminUrl));
        } else {
            $this->response->throwJson(['success' => 1, 'message' => _t('状态排序已经完成')]);
        }
    }

    /**
     * 刷新状态
     *
     * @throws Exception
     */
    public function refreshStatus()
    {
        $categories = $this->request->filter('int')->getArray('mid');
        if ($categories) {
            foreach ($categories as $status) {
                $this->refreshCountByTypeAndStatus($status, 'post', 'publish');
            }

            Notice::alloc()->set(_t('状态刷新已经完成'), 'success');
        } else {
            Notice::alloc()->set(_t('没有选择任何状态'), 'notice');
        }

        /** 转向原页 */
        $this->response->goBack();
    }

    /**
     * 设置默认状态
     *
     * @throws Exception
     */
    public function defaultStatus()
    {
        /** 验证数据 */
        $validator = new Validate();
        $validator->addRule('mid', 'required', _t('状态主键不存在'));
        $validator->addRule('mid', [$this, 'statusExists'], _t('状态不存在'));

        if ($error = $validator->run($this->request->from('mid'))) {
            Notice::alloc()->set($error, 'error');
        } else {
            $this->db->query($this->db->update('table.options')
                ->rows(['value' => $this->request->mid])
                ->where('name = ?', 'defaultStatus'));

            $this->db->fetchRow($this->select()->where('mid = ?', $this->request->mid)
                ->where('type = ?', 'status')->limit(1), [$this, 'push']);

            /** 设置高亮 */
            Notice::alloc()->highlight($this->theId);

            /** 提示信息 */
            Notice::alloc()->set(
                _t('<a href="%s">%s</a> 已经被设为默认状态', $this->permalink, $this->name),
                'success'
            );
        }

        /** 转向原页 */
        $this->response->redirect(Common::url('manage-categories.php', $this->options->adminUrl));
    }

    /**
     * 获取菜单标题
     *
     * @return string|null
     * @throws \Typecho\Widget\Exception|Exception
     */
    public function getMenuTitle(): ?string
    {
        if (isset($this->request->mid)) {
            $status = $this->db->fetchRow($this->select()
                ->where('type = ? AND mid = ?', 'status', $this->request->mid));

            if (!empty($status)) {
                return _t('编辑状态 %s', $status['name']);
            }

        }
        if (isset($this->request->parent)) {
            $status = $this->db->fetchRow($this->select()
                ->where('type = ? AND mid = ?', 'status', $this->request->parent));

            if (!empty($status)) {
                return _t('新增 %s 的子状态', $status['name']);
            }

        } else {
            return null;
        }

        throw new \Typecho\Widget\Exception(_t('状态不存在'), 404);
    }

    /**
     * 入口函数
     *
     * @access public
     * @return void
     */
    public function action()
    {
        $this->security->protect();
        $this->on($this->request->is('do=insert'))->insertStatus();
        $this->on($this->request->is('do=update'))->updateStatus();
        $this->on($this->request->is('do=delete'))->deleteStatus();
        $this->on($this->request->is('do=merge'))->mergeStatus();
        $this->on($this->request->is('do=sort'))->sortStatus();
        $this->on($this->request->is('do=refresh'))->refreshStatus();
        $this->on($this->request->is('do=default'))->defaultStatus();
        $this->response->redirect($this->options->adminUrl);
    }
}
