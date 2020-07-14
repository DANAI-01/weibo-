<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{
    public function __construct() {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function index() {
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

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

        //通过注册邮箱验证:
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');

        //直接注册然后自动登录:
        // Auth::login($user);//用户注册成功后能够自动登录
        // session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');//在网页顶部位置显示注册成功的提示信息
        // return redirect()->route('users.show', [$user]);
    }

    public function edit(User $user) {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request) {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'required|confirmed|min:6',
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user);
    }

    public function destroy(User $user) {//用户删除动作
        $this->authorize('destroy', $user);//对删除操作进行授权验证
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    protected function sendEmailConfirmationTo($user) {//该方法将用于发送邮件给指定用户
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($to, $subject) {//??????
            $message->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token) {//用于完成用户的激活操作
        $user = User::where('activation_token', $token)->firstOrFail();//取出第一个用户

        $user->activated = true;//将该用户的激活状态改为true
        $user->activation_token = null;//激活令牌设置为空
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
}
