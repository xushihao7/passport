<?php

namespace App\Http\Controllers\User;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
class UserController extends Controller
{
    //
    public  function  loginShow(Request $request){
        $redirect=urldecode($request->input("redirect")) ?? env("SHOP_URL");
        //var_dump($redirect);die;
        $data=[
            'redirect'=>$redirect
        ];
        return view("user.login",$data);
    }
    public function  login(Request $request){
        $uname=$request->input("name");
        $pwd=$request->input("pwd");
        //var_dump($name);die;
        $where=[
            'name'=>$uname,
        ];
        $res=UserModel::where($where)->first();
        //var_dump($res);die;
        if($res){
            if(password_verify($pwd,$res->pwd)){
                $token=substr(md5(time().mt_rand(1,99999)),10,10);
                setcookie("uid",$res->uid,time()+86400,"/",'xushihao.com',false,true);
                setcookie("token",$token,time()+86400,"/","xushihao.com",false,true);
                $response=[
                    'error'=>0,
                    'msg'=>'登录成功'
                ];
                $key="s:token:".$res->uid;
                Redis::set($key,$token);
                Redis::expire($key,83600);
            }else{
                $response=[
                    'error'=>5001,
                    'msg'=>'密码错误'
                ];
            }
        }else{
            $response=[
                'error'=>5001,
                'msg'=>'账号错误'
            ];
        }
        return $response;
    }
}
