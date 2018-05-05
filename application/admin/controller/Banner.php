<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 首页轮播图
 *
 * @icon fa fa-circle-o
 */
class Banner extends Backend
{
    
    /**
     * Banner模型对象
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Banner');
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("statusDataList", $this->model->getStatusDataList());
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
                $appDetail = db('app')->where(['id'=>$v['app_id_from']])->field('name')->find();
                $list[$k]['app_id_from'] = $appDetail['name'];

                if ($v['content_id']) {
                    $contentDetail = db('content')->where("id={$v['content_id']}")->field('title')->find();
                    $list[$k]['content_id'] = $contentDetail['title'];
                }
            }
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

}
