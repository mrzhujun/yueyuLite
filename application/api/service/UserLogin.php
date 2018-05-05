<?php
/**
 * Author: zhujun
 * DateTime: 2018/5/5 10:16
 */

namespace app\api\service;

//记录用户访问
class UserLogin
{
    public  function record($uid)
    {
        $date = date('Ymd',time());
        $rs = db('counting')->where("user_id={$uid} and login_time={$date}")->find();
        if (!$rs) {
            db('counting')->insert(['user_id'=>$uid,'login_time'=>date('Ymd',time()),'login_time_int'=>time()]);
        }else{
            if (time()-$rs['login_time_int'] > 30*60) {
                db('counting')->where("user_id={$uid} and login_time={$date}")->update(['login_time_int'=>time(),'tips'=>$rs['tips']+1]);
            }
        }
    }
}