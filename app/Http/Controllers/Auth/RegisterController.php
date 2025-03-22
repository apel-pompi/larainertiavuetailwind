<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    public function index(){
        $user = User::with('roles')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(), // Get assigned roles
            ];
        });
        //dd($user);
        return Inertia::render('Auth/NewRegister',compact('user'));
    }

    public function create(){
        $roles = Role::all();
        return Inertia::render('Auth/Register',[
            'roles'=>$roles
        ]);
    }

    public function store(Request $request){
        
        $credentials = $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:3',
            'selectedrole' => 'required'
        ]);
        
        $user = User::create($credentials);
        $role = Role::whereIn('id',$credentials['selectedrole'])->get(['id'])->pluck('id');
        $user->assignRole($role);
        return redirect()->route('register.index')->with('success', 'Role Assign successfully!');
        
    }

    
// logout
    public function destory(Request $request){
        {
            Auth::logout();
    
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            return redirect()->route('login');
        }
    }

//email verification
    public function notice()
    {
        return Inertia::render('Auth/VerifyEmail', [
            'status' => session('status')
        ]);
    }

    public function handler(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('dashboard');
    }


    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification link sent!');
    }
//reset password

    public function requestPass()
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status')
        ]);
    }

    public function sendEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetForm(Request $request)
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token')
        ]);
    }

    public function resetHandler(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:3|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
