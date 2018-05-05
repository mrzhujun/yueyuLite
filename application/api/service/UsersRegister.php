<?php
/**
 * Author: zhujun
 * DateTime: 2018/5/4 15:38
 */
namespace app\api\service;

use app\api\library\BaseException;
use app\api\library\MissException;
use \app\api\library\WxException;

class UsersRegister
{
    protected $code;
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code,$appid,$appsecret)
    {
        $this->code = $code;
        $this->wxAppId = $appid;
        $this->wxAppSecret = $appsecret;
        $this->wxLoginUrl = sprintf(config('setting.login_url'),
            $this->wxAppId,$this->wxAppSecret,$this->code);
    }

    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);
        if (empty($wxResult)) {
            throw new WxException([
                'msg' => '获取session_key及open_id时异常，微信内部错误'
            ]);
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if ($loginFail) {
                throw new WxException([
                    'msg' => '微信服务器获取出错，错误原因:'.$wxResult['errmsg']
                ]);
            }else{
                return $this->saveToMysql($wxResult);
            }
        }
    }

    private function saveToMysql($wxresult)
    {
        $appDetail = db('app')->where("appid='{$this->wxAppId}'")->field('id')->find();
        if (!$appDetail) {
            throw new MissException([
                'code' => 404,
                'msg' => '需要现在后台配置小程序'
            ]);
        }
        $userDetail = db('users')->where(['appid'=>$appDetail['id'],'open_id'=>$wxresult['openid']])->find();
        if ($userDetail) {
            $uid = $userDetail['uid'];
        }else{
            $uid = db('users')->insertGetId(['appid'=>$appDetail['id'],'open_id'=>$wxresult['openid'],'create_time'=>time()]);
        }
        $return['app_id'] = $appDetail['id'];
        $return['uid'] = $uid;
        return $return;
    }
}