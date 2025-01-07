<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // ログイン後のリダイレクト先
    protected $redirectTo = '/products';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * カスタムログイン処理
     */
    public function login(Request $request)
    {
        // バリデーション
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ], [
            'required' => ':attributeは必須です。',
            'email' => ':attributeは有効な形式で入力してください。',
            'min' => ':attributeは:min文字以上で入力してください。',
        ], [
            'email' => 'メールアドレス',
            'password' => 'パスワード',
        ]);

        // 認証試行
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectTo)
                ->with('success', 'ログインしました。');
        }

        // 認証失敗時
        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません。',
        ])->withInput();
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'ログアウトしました。');
    }

    /**
     * 認証後のリダイレクト処理
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard')
                ->with('success', '管理者としてログインしました。');
        }

        return redirect()->intended($this->redirectTo)
            ->with('success', 'ログインしました。');
    }
}
