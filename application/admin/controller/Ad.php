<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 广告管理
 *
 * @icon fa fa-circle-o
 */
class Ad extends Backend
{
    
    /**
     * Ad模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Ad');
        $this->view->assign("whereList", $this->model->getWhereList());
        $this->view->assign("isOpenList", $this->model->getIsOpenList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            foreach ($list as $k => $v)
            {
                $appO = db('app')->where("id={$v['appid']}")->field('name')->find();
                $list[$k]['appid'] = $appO['name'];

                $contentO = db('content')->where("id={$v['type_id']}")->field('title')->find();
                $list[$k]['type_id'] = $contentO['title'];
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

}
