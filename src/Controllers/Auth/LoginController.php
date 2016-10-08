<?php

namespace LiveCMS\Controllers\Auth;

use Illuminate\Http\Request;
use LiveCMS\Controllers\Controller;
use LiveCMS\Models\Users\User;
use LiveCMS\Models\User as UserModel;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Add login view
     */
    protected $loginView  = 'livecms::auth.login';

    /**
     * Add register view
     */
    protected $registerView  = 'livecms::auth.register';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);

        $userSlug = getSlug('userhome');
        $this->redirectTo = $userSlug;
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view($this->loginView);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, app(UserModel::class)->rules());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = UserModel::create($data);
        return User::find($user->id);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        $credentials = $request->only($this->loginUsername(), 'password');
        
        return array_merge($credentials, ['site_id' => site()->getCurrent()->id]);
    }

    protected function authenticated($request, User $user)
    {
        if ($user->is_banned) {
            $this->logout();
            alert()->error(trans('livecms::livecms.userisbanned'), trans('livecms::livecms.loginfailed'));
            return redirect()->back();
        }
        
        if ($user->site_id != site()->id) {
            return redirect()->to('logout');
        }

        return redirect()->intended($this->redirectTo);
    }
}
