<?php

namespace Widget\Contents\Domain;

use Typecho\Db;
use Widget\Base\ContentsDomain;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * 最新评论组件
 *
 * @category typecho
 * @package Widget
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 */
class RecentSold extends ContentsDomain
{
    /**
     * 执行函数
     *
     * @throws Db\Exception
     */
    public function execute()
    {
        $this->parameter->setDefault(['pageSize' => $this->options->postsListSize]);

        $this->db->fetchAll($this->select()
            ->where('table.contents.status = ?', 'sold')
            ->where('table.contents.created < ?', $this->options->time)
            ->where('table.contents.type = ?', 'domain')
            ->order('table.contents.created', Db::SORT_DESC)
            ->limit($this->parameter->pageSize), [$this, 'push']);
    }
}
