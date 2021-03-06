<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function welcome() {
        return view('welcome');
    }
    
    
    //登录页面
    public function index() {
        return view('login.index');
    }
    
    //登录行为
    public function login() {
        //验证
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required|min:5',
            'is_remember' => 'integer',
        ]);
        
        //TODO:验证错误后刷新界面保留用户填写的数据
        
        //逻辑
        $user = request(['email', 'password']);
        $is_remember = boolval(request('is_remember'));
        if (Auth::attempt($user, $is_remember)) {
            return redirect('/posts');
        }
        
        //渲染
        return Redirect::back()->withErrors("邮箱密码不匹配");
    }
    
    //登出行为
    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}
