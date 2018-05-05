<?php

namespace app\api\controller;

use app\api\library\MissException;
use app\api\model\Banner;
use app\api\model\Collect;
use app\api\model\Column;
use app\api\model\Content;
use app\api\service\UserLogin;
use app\api\service\UsersRegister;
use app\common\controller\Api;
use \app\api\library\BaseException;

/**
 * swagger: 阅图lite
 */
class All extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * get: 获取用户id及小程序id
     * path: getopenidandapp_id
     * method: formData
     * param: code - {string} = code
     * param: appid - {string} = appid
     */
    public function getopenidandapp_id($code,$appid) {
        $appDetail = db('app')->where("appid='{$appid}'")->field('id,appsecret')->find();
        $AppSecret = $appDetail['appsecret'];

        $service = new UsersRegister($code,$appid,$AppSecret);
        $return = $service->get();
        return json($return);
    }

    /**
     * get: 获取banner列表 type:跳转位置:0=内容详情页,1=更多好玩,2=客服,3=跳转小程序
     * path: banner_list
     * param: app_id - {int} = '' app_id
     * param: uid - {int} = '' uid
     */
    public function banner_list($app_id)
    {
        $list = Banner::where('status_data','=',1)->where('app_id_from','=',$app_id)->select();
        (new UserLogin())->record(input('uid'));
        return json($list);
    }

    /**
     * get: 首页内容列表
     * path : content_list
     * param: app_id - {int} = '' app_id
     * param: page - {int} = '1' 页数
     * param: limit - {int} = '5' 每页显示
     */
    public function content_list($page=1,$limit=5,$app_id)
    {
        $contentIdList = db('appid_contentid')->field('contentid')->where("appid={$app_id}")->select();
        if (!$contentIdList) {
            throw new MissException([
                'msg' => '该小程序没有文章'
            ]);
        }
        $str = '';
        foreach ($contentIdList as $v)
        {
            $str .= $v['contentid'].',';
        }
        $str = rtrim($str,',');
        $page<1?$page=1:$page=$page;
        $count = Content::where("id in ({$str})")->count();
        $maxPage = ceil($count/$limit);
        $pageDetail['total_page'] = $maxPage;
        $pageDetail['now_page'] = $page;

        if ($page > $maxPage) {
            throw new MissException(['msg'=>'没有下一页了']);
        }

        $limit = $limit+($page-1)*$limit;
        $list = Content::where("id in ({$str})")->field('id,title,cover_image')->limit(0,$limit)->select();
        $return['page_detail'] = $pageDetail;
        $return['active_list'] = $list;
        return json($return);
    }

    /**
     * get: 内容详情
     * path : content_detail
     * param: id - {int} = '' 文章id
     */
    public function content_detail($id)
    {
        return json(Content::get($id));
    }

    /**
     * get: 内容详情页推荐
     * path : content_detail_recommend
     * param: id - {int} = '' 文章id
     * param: app_id - {int} = '' app_id
     */
    public function content_detail_recommend($id,$app_id)
    {
        $list = db('appid_contentid')->where("appid={$app_id}")->field('contentid')->select();
        $arr = [];
        foreach ($list as $k => $v)
        {
            $arr[] = $v['contentid'];
        }
        $length  = count($arr)>=4?4:count($arr);
        $arrkey = array_rand($arr,$length);

        $idStr = '';
        foreach ($arrkey as $v)
        {
            $idStr .= $arr[$v].',';
        }

        $idStr = rtrim($idStr,',');
        $list = Content::where("id in ({$idStr})")->field('id,title,cover_image,create_time')->limit($length)->select();
        return json($list);
    }

    /**
     * get: 专栏,展示列表
     * path : column_list
     * param: app_id - {int} = '' app_id
     */
    public function column_list($app_id)
    {
        $switch = db('app')->where("id={$app_id}")->field('column_switch')->find();
        if ($switch['column_switch'] != 1) {
            throw new BaseException([
                'code' => 400,
                'msg' => '该小程序未开启专栏'
            ]);
        }

        $list = Column::all();
        return json($list);
    }


    /**
     * post: 收藏
     * path : collect
     * param: app_id - {int} = '' app_id
     * param: uid - {int} = '' 用户id
     * param: content_id - {int} = '' 文章id
     */
    public function collect($app_id,$uid,$content_id)
    {
        $is_collect = db('collect')->where("appid='{$app_id}' and uid={$uid} and content_id={$content_id}")->count();
        if ($is_collect) {
            throw new BaseException([
                'msg' => '已经收藏过该文章'
            ]);
        }

        db('collect')->insert(['appid'=>$app_id,'uid'=>$uid,'content_id'=>$content_id,'create_time'=>time()]);
        return json([
            'code' => 1,
            'msg' => '收藏成功'
        ]);
    }

    /**
     * get: 收藏列表
     * path : collect_list
     * param: app_id - {int} = '' app_id
     * param: uid - {int} = '' 用户id
     */
    public function collect_list($app_id,$uid)
    {
        $list = Collect::where("appid={$app_id} and uid={$uid}")->with(['content'=>function($query)
        {
            $query->field('id,title,cover_image,create_time');
        }])->field('id,content_id')->select();
        return json($list);
    }

    /**
     * post: 删除收藏
     * path:collect_delete
     * param:collect_id - {int} = '' 收藏id
     */
    public function collect_delete($collect_id)
    {
        db('collect')->where("id={$collect_id}")->delete();
        return json([
            'code' => 1,
            'msg' => '删除成功'
        ]);
    }

    /**
     * get : 广告获取广告位置:0=首页内容图片下面(每个内容允许添加一个广告),1=banner下面(可以添加多个广告，后同),2=收藏顶部,3=收藏底部,4=详情页顶部,5=详情页中部,6=详情页底部
     * path:get_ad
     * param: app_id - {int} = '' app_id
     */
    public function get_ad($app_id)
    {
        $content_ad = db('ad')->field('code')->where('appid','=',$app_id)->where('is_open','=',1)->where('where','=',0)->select();
        //将所有广告随机填充到首页吗每6个文章下面
        $contentList = db('appid_contentid')->where("appid={$app_id}")->field('contentid')->select();

        $page = ceil(count($contentList)/6);
        $arr = [];
//        dump($contentList);exit();
        if ($content_ad) {
            for($i=0;$i<$page;$i++){
                $arr[$i]['content_id'] = $contentList[($i)*6]['contentid'];
                $arr[$i]['code'] = $content_ad[rand(0,count($content_ad)-1)]['code'];
            }
            $list['content_index'] = $arr;
        }else{
            $list['content_index'] = [];
        }


        $list['banner'] = db('ad')->field('code')->where('appid','=',$app_id)->where('is_open','=',1)->where('where','=',1)->select();
        $list['collect_top'] = db('ad')->field('code')->where('appid','=',$app_id)->where('is_open','=',1)->where('where','=',2)->select();
        $list['collect_bottom'] = db('ad')->field('code')->where('appid','=',$app_id)->where('is_open','=',1)->where('where','=',3)->select();
        $list['content_top'] = db('ad')->field('code')->where('appid','=',$app_id)->where('is_open','=',1)->where('where','=',4)->select();
        $list['content_mid'] = db('ad')->field('code')->where('appid','=',$app_id)->where('is_open','=',1)->where('where','=',5)->select();
        $list['content_bottom'] = db('ad')->field('code')->where('appid','=',$app_id)->where('is_open','=',1)->where('where','=',6)->select();



        return json($list);
    }

}
