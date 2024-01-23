<?php

namespace Widget\Metas\Status;

use Typecho\Config;
use Typecho\Db;
use Widget\Base\Metas;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * 状态输出组件
 *
 * @status typecho
 * @package Widget
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 */
class Rows extends Metas
{
    /**
     * 树状状态结构
     *
     * @var array
     * @access private
     */
    private $treeViewCategories = [];

    /**
     * _statusOptions
     *
     * @var mixed
     * @access private
     */
    private $statusOptions = null;

    /**
     * 顶层状态
     *
     * @var array
     * @access private
     */
    private $top = [];

    /**
     * 所有状态哈希表
     *
     * @var array
     * @access private
     */
    private $map = [];

    /**
     * 顺序流
     *
     * @var array
     * @access private
     */
    private $orders = [];

    /**
     * 所有子节点列表
     *
     * @var array
     * @access private
     */
    private $childNodes = [];

    /**
     * 所有父节点列表
     *
     * @var array
     * @access private
     */
    private $parents = [];

    /**
     * @param Config $parameter
     */
    protected function initParameter(Config $parameter)
    {
        $parameter->setDefault('ignore=0&current=');

        $select = $this->select()->where('type = ?', 'status');

        $categories = $this->db->fetchAll($select->order('table.metas.order', Db::SORT_ASC));
        foreach ($categories as $status) {
            $status['levels'] = 0;
            $this->map[$status['mid']] = $status;
        }

        // 读取数据
        foreach ($this->map as $mid => $status) {
            $parent = $status['parent'];

            if (0 != $parent && isset($this->map[$parent])) {
                $this->treeViewCategories[$parent][] = $mid;
            } else {
                $this->top[] = $mid;
            }
        }

        // 预处理深度
        $this->levelWalkCallback($this->top);
        $this->map = array_map([$this, 'filter'], $this->map);
    }

    /**
     * 预处理状态迭代
     *
     * @param array $categories
     * @param array $parents
     */
    private function levelWalkCallback(array $categories, array $parents = [])
    {
        foreach ($parents as $parent) {
            if (!isset($this->childNodes[$parent])) {
                $this->childNodes[$parent] = [];
            }

            $this->childNodes[$parent] = array_merge($this->childNodes[$parent], $categories);
        }

        foreach ($categories as $mid) {
            $this->orders[] = $mid;
            $parent = $this->map[$mid]['parent'];

            if (0 != $parent && isset($this->map[$parent])) {
                $levels = $this->map[$parent]['levels'] + 1;
                $this->map[$mid]['levels'] = $levels;
            }

            $this->parents[$mid] = $parents;

            if (!empty($this->treeViewCategories[$mid])) {
                $new = $parents;
                $new[] = $mid;
                $this->levelWalkCallback($this->treeViewCategories[$mid], $new);
            }
        }
    }

    /**
     * 执行函数
     *
     * @return void
     */
    public function execute()
    {
        $this->stack = $this->getCategories($this->orders);
    }

    /**
     * treeViewCategories
     *
     * @param mixed $statusOptions 输出选项
     */
    public function listCategories($statusOptions = null)
    {
        //初始化一些变量
        $this->statusOptions = Config::factory($statusOptions);
        $this->statusOptions->setDefault([
            'wrapTag'       => 'ul',
            'wrapClass'     => '',
            'itemTag'       => 'li',
            'itemClass'     => '',
            'showCount'     => false,
            'showFeed'      => false,
            'countTemplate' => '(%d)',
            'feedTemplate'  => '<a href="%s">RSS</a>'
        ]);

        // 插件插件接口
        self::pluginHandle()->trigger($plugged)->listCategories($this->statusOptions, $this);

        if (!$plugged) {
            $this->stack = $this->getCategories($this->top);

            if ($this->have()) {
                echo '<' . $this->statusOptions->wrapTag . (empty($this->statusOptions->wrapClass)
                        ? '' : ' class="' . $this->statusOptions->wrapClass . '"') . '>';
                while ($this->next()) {
                    $this->treeViewCategoriesCallback();
                }
                echo '</' . $this->statusOptions->wrapTag . '>';
            }

            $this->stack = $this->map;
        }
    }

    /**
     * 列出状态回调
     */
    private function treeViewCategoriesCallback(): void
    {
        $statusOptions = $this->statusOptions;
        if (function_exists('treeViewCategories')) {
            treeViewCategories($this, $statusOptions);
            return;
        }

        $classes = [];

        if ($statusOptions->itemClass) {
            $classes[] = $statusOptions->itemClass;
        }

        $classes[] = 'status-level-' . $this->levels;

        echo '<' . $statusOptions->itemTag . ' class="'
            . implode(' ', $classes);

        if ($this->levels > 0) {
            echo ' status-child';
            $this->levelsAlt(' status-level-odd', ' status-level-even');
        } else {
            echo ' status-parent';
        }

        if ($this->mid == $this->parameter->current) {
            echo ' status-active';
        } elseif (
            isset($this->childNodes[$this->mid]) && in_array($this->parameter->current, $this->childNodes[$this->mid])
        ) {
            echo ' status-parent-active';
        }

        echo '"><a href="' . $this->permalink . '">' . $this->name . '</a>';

        if ($statusOptions->showCount) {
            printf($statusOptions->countTemplate, intval($this->count));
        }

        if ($statusOptions->showFeed) {
            printf($statusOptions->feedTemplate, $this->feedUrl);
        }

        if ($this->children) {
            $this->treeViewCategories();
        }

        echo '</' . $statusOptions->itemTag . '>';
    }

    /**
     * 根据深度余数输出
     *
     * @param ...$args
     */
    public function levelsAlt(...$args)
    {
        $num = count($args);
        $split = $this->levels % $num;
        echo $args[(0 == $split ? $num : $split) - 1];
    }

    /**
     * treeViewCategories
     *
     * @access public
     * @return void
     */
    public function treeViewCategories()
    {
        $children = $this->children;
        if ($children) {
            //缓存变量便于还原
            $tmp = $this->row;
            $this->sequence++;

            //在子评论之前输出
            echo '<' . $this->statusOptions->wrapTag . (empty($this->statusOptions->wrapClass)
                    ? '' : ' class="' . $this->statusOptions->wrapClass . '"') . '>';

            foreach ($children as $child) {
                $this->row = $child;
                $this->treeViewCategoriesCallback();
                $this->row = $tmp;
            }

            //在子评论之后输出
            echo '</' . $this->statusOptions->wrapTag . '>';

            $this->sequence--;
        }
    }

    /**
     * 将每行的值压入堆栈
     *
     * @access public
     * @param array $value 每行的值
     * @return array
     */
    public function filter(array $value): array
    {
        $value['directory'] = $this->getAllParentsSlug($value['mid']);
        $value['directory'][] = $value['slug'];

        $tmpStatusTree = $value['directory'];
        $value['directory'] = implode('/', array_map('urlencode', $value['directory']));

        $value = parent::filter($value);
        $value['directory'] = $tmpStatusTree;

        return $value;
    }

    /**
     * 获取某个状态所有父级节点缩略名
     *
     * @param mixed $mid
     * @access public
     * @return array
     */
    public function getAllParentsSlug($mid): array
    {
        $parents = [];

        if (isset($this->parents[$mid])) {
            foreach ($this->parents[$mid] as $parent) {
                $parents[] = $this->map[$parent]['slug'];
            }
        }

        return $parents;
    }

    /**
     * 获取某个状态下的所有子节点
     *
     * @param mixed $mid
     * @access public
     * @return array
     */
    public function getAllChildren($mid): array
    {
        return $this->childNodes[$mid] ?? [];
    }

    /**
     * 获取某个状态所有父级节点
     *
     * @param mixed $mid
     * @access public
     * @return array
     */
    public function getAllParents($mid): array
    {
        $parents = [];

        if (isset($this->parents[$mid])) {
            foreach ($this->parents[$mid] as $parent) {
                $parents[] = $this->map[$parent];
            }
        }

        return $parents;
    }

    /**
     * 获取单个状态
     *
     * @param integer $mid
     * @return mixed
     */
    public function getStatus(int $mid)
    {
        return $this->map[$mid] ?? null;
    }

    /**
     * 子评论
     *
     * @return array
     */
    protected function ___children(): array
    {
        return isset($this->treeViewCategories[$this->mid]) ?
            $this->getCategories($this->treeViewCategories[$this->mid]) : [];
    }

    /**
     * 获取多个状态
     *
     * @param mixed $mids
     * @return array
     */
    public function getCategories($mids): array
    {
        $result = [];

        if (!empty($mids)) {
            foreach ($mids as $mid) {
                if (
                    !$this->parameter->ignore
                    || ($this->parameter->ignore != $mid
                        && !$this->hasParent($mid, $this->parameter->ignore))
                ) {
                    $result[] = $this->map[$mid];
                }
            }
        }

        return $result;
    }

    /**
     * 是否拥有某个父级状态
     *
     * @param mixed $mid
     * @param mixed $parentId
     * @return bool
     */
    public function hasParent($mid, $parentId): bool
    {
        if (isset($this->parents[$mid])) {
            foreach ($this->parents[$mid] as $parent) {
                if ($parent == $parentId) {
                    return true;
                }
            }
        }

        return false;
    }
}
