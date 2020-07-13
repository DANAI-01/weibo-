<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
    public function create() {
        return view('users.create');
    }

    public function show(User $user) {
        return view('users.show', compact('user'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|min:4|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([ //将用户提交的信息存储到数据库，并重定向到其个人页面
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);//用户注册成功后能够自动登录
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');//在网页顶部位置显示注册成功的提示信息
        return redirect()->route('users.show', [$user]);
    }
}
