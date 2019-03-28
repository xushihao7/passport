<?php

namespace App\Http\Controllers\User;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
class UserController extends Controller
{
    //passport登录 web端
    public  function  loginShow(Request $request){
        $redirect=$request->input("redirect") ?? env("SHOP_URL");
        //var_dump($redirect);die;
        $data=[
            'redirect'=>$redirect
        ];
        return view("user.login",$data);
    }
    //passport登录
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
                $key="h:token:".$res->uid;
                Redis::del($key,"android");
                Redis::hSet($key,'web',$token);
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
    //passport注册 web端
    public  function regShow(Request $request){
        $redirect=$request->input("redirect") ?? env("SHOP_URL");
        //var_dump($redirect);die;
        $data=[
            'redirect'=>$redirect
        ];
        return view("user.register",$data);
    }
    public  function  register(Request $request){
       $username=$request->input("name");
       $pwd=$request->input("pwd");
       $email=$request->input("email");
       $age=$request->input("age");
       $where=[
           'name'=>$username
       ];
       $count=UserModel::where($where)->count();
       if($count>0){
           $response=[
               'error'=>5001,
               'msg'=>"账号已经存在"
           ];
       }else{
           $pwd=password_hash($pwd,PASSWORD_BCRYPT);
           $data=[
               'name'=>$username,
               'pwd'=>$pwd,
               'age'=>$age,
               'email'=>$email,
               'reg_time'=>time()
           ];
           $uid=UserModel::insertGetId($data);
           if($uid){
               $response=[
                   'error'=>'0',
                   'msg'=>'注册成功'
               ];
               $token=substr(md5(time().mt_rand(1,99999)),10,10);
               setcookie("uid",$uid,time()+86400,"/",'xushihao.com',false,true);
               setcookie("uname",$username,time()+86400,"/",'xushihao.com',false,true);
               setcookie("token",$token,time()+86400,"/","xushihao.com",false,true);
               $key="h:token:".$uid;
               Redis::del($key,"android");
               Redis::hSet($key,'web',$token);
           }else{
               $response=[
                   'error'=>'5001',
                   'msg'=>'注册失败'
               ];
           }

       }
       return $response;


   }
    // possport登录 app端
    public  function  apiLogin(Request $request){
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
                $uid=$res->uid;
                $token=substr(md5(time().mt_rand(1,99999)),10,10);
                $key="h:token:".$uid;
                Redis::del($key);
                Redis::hSet($key,"android",$token);
                $response=[
                    'error'=>0,
                    'msg'=>'登录成功',
                    'token'=>$token ,
                    'name'=>$res->name,
                    'uid'=>$uid
                ];

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
    //退出
    public  function  quit(){
        setcookie("uid",null,time()-1,"/",'xushihao.com',false,true);
        setcookie("token",null,time()-1,"/","xushihao.com",false,true);
        echo "退出成功";
        header("refresh:1,url=http://www.xushihao.com");
    }
}
