<?php
// +----------------------------------------------------------------------
// | tpvue [ 模块化渐进式后台 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018-2019 http://tpvue.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

namespace tpvue\core\controller;

use think\Controller;
use think\Request;
use \Firebase\JWT\JWT;

class Jwt extends Controller
{
    /**
     * 获取所有登录的用户信息
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $jwt_list = db('core_user_jwt')->select();
        dump($jwt_list);
    }

    /**
     * 用户登录：创建一条数据库记录
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 获取提交的账号密码
        $account = input('post.account');
        $password = input('post.password');

        // 账号密码验证

        // 登录验证
        $password_hash = md5($password);
        $map = [];
        $map['identity_type'] = '0';
        $map['identifier'] = $account;
        $user_info = db('core_user_identity')->where($map)->find();
        if (!$user_info) {
            return json(['code' => 0, 'message' => '账号不存在']);
        }
        if ($user_info['status'] !== '1') {
            return json(['code' => 0, 'message' => '账号状态异常']);
        }
        if ($user_info['verified'] !== '1') {
            return json(['code' => 0, 'message' => '账号未通过验证']);
        }
        if ($user_info['credential'] !== $password_hash) {
            return json(['code' => 0, 'message' => '密码错误']);
        }
    
        $key = "tpvue-key"; //秘钥加密关键 Signature
        $token = [
            'iss' => 'tpvue.com', //签发者
            "aud" => "tpvue.com", //面向的用户
            "iat" => time(), //签发时间
            "nbf" => time()+100, //在什么时候jwt开始生效 
            "exp" => time()+7200, //token 过期时间
            'id'  => $uid //可以用户ID，可以自定义
        ]; //Payload
        $jwt = JWT::encode($token, $key); //此处行进加密算法生成jwt
        return json($jwt);
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
