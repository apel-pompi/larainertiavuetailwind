<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    
    public function index(){

        if (is_null(Auth::user()) || ! Auth::user()->can('users.index')) {
            abort(403, 'Sorry !! You are Unauthorized person !');
        }
 
        $user = User::with('roles')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(), // Get assigned roles
            ];
        });

        return Inertia::render('Auth/NewRegister',compact('user'));
    }

    public function create(){

        if (is_null(Auth::user()) || ! Auth::user()->can('users.create')) {
            abort(403, 'Sorry !! You are Unauthorized person !');
        }

        $roles = Role::all();
        return Inertia::render('Auth/Register',[
            'roles'=>$roles
        ]);
    }

    public function store(Request $request){

        if (is_null(Auth::user()) || ! Auth::user()->can('users.store')) {
            abort(403, 'Sorry !! You are Unauthorized person !');
        }

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
    //Edit 
    public function edit($id){

        if (is_null(Auth::user()) || ! Auth::user()->can('users.edit')) {
            abort(403, 'Sorry !! You are Unauthorized person !');
        }

        $user = User::with('roles')->where('id', $id)->first();
        if ($user) {
            $user = [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(), // Get assigned roles
            ];
        }
        return Inertia::render('Auth/RegisterEdit',[
            'user' => $user,
            'roles' => Role::pluck('id','name')
        ]);
    }

    public function update(Request $request, string $id){

        if (is_null(Auth::user()) || ! Auth::user()->can('users.update')) {
            abort(403, 'Sorry !! You are Unauthorized person !');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Ignore the current user's email
            'password' => 'nullable|confirmed|min:3', // Password is optional
            'selectedrole' => 'required|array' // Ensure roles are in an array
        ]);

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : $user->password // Update only if provided
        ]);
        $user->syncRoles($validated['selectedrole']);
        return redirect()->route('register.index')->with('flash', [
            'success' => 'User created and roles assigned successfully!'
        ]);
    }
// logout
    public function destory(Request $request){
        {
            if (is_null(Auth::user()) || ! Auth::user()->can('users.destory')) {
                abort(403, 'Sorry !! You are Unauthorized person !');
            }

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
