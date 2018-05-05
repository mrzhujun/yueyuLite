<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        $seventtime = \fast\Date::unixtime('day', -7);
        $paylist = $createlist = [];
        for ($i = 0; $i <= 7; $i++)
        {
            $day = date("Y-m-d", $seventtime + ($i * 86400));
            $time = $seventtime + ($i * 86400);
            $time1 = $time+24*3600;
            $createlist[$day] = db('counting')->where("login_time_int>={$time} and login_time_int<{$time1}")->sum('tips');//每天访问人次
            $paylist[$day] = db('counting')->where("login_time_int>={$time} and login_time_int<{$time1}")->count();//每天登陆人次
        }
        $hooks = config('addons.hooks');
        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';
        $addonComposerCfg = ROOT_PATH . '/vendor/karsonzhang/fastadmin-addons/composer.json';
        Config::parse($addonComposerCfg, "json", "composer");
        $config = Config::get("composer");
        $addonVersion = isset($config['version']) ? $config['version'] : __('Unknown');


        //开始统计
        //总会员数
        $totaluser = db('users')->count();
        //今日新增会员数
        $newUser = db('users')->where("create_time > ".\fast\Date::unixtime('day'))->count();
        //今日访客数
        $todayUserCount = db('counting')->where('login_time_int>'.\fast\Date::unixtime('day'))->count();
        //今日流量
        $todayUserView = db('counting')->where('login_time_int>'.\fast\Date::unixtime('day'))->sum('tips');


        $this->view->assign([
            'totaluser'        => $totaluser,//总会员数
            'totalviews'       => $newUser,//今日新增会员数
            'totalorder'       => $todayUserCount,//今日访客数
            'totalorderamount' => $todayUserView,
            'todayuserlogin'   => '-',
            'todayusersignup'  => '-',
            'todayorder'       => '-',
            'unsettleorder'    => '-',
            'sevendnu'         => '-',
            'sevendau'         => '-',
            'paylist'          => $paylist,
            'createlist'       => $createlist,
            'addonversion'       => '-',
            'uploadmode'       => '-'
        ]);

        return $this->view->fetch();
    }

}
