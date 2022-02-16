<?php


namespace app\api\controller;

/**
 * 帖子
 * Class Post
 * @package app\api\controller
 */
class Post extends Base
{
    /**
     * 搜索帖子
     */
    public function search()
    {
        $keyword = input('keyword/s', '');
        if (empty($keyword)) $this->output(1, '请输入关键词');
        $data = [];
        $this->output(0, '', $data);
    }

    /**
     * 发布帖子
     */
    public function release()
    {
        $content = input('content/s', '');
    }
}