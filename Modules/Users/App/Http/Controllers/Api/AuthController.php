<?php


namespace Modules\Users\App\Http\Controllers\Api;

use Modules\Users\App\Events\VerifyEmailByCode;
use Modules\Users\App\Events\VerifyMobileByCode;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
// use Illuminate\Http\Client\Request;
use Illuminate\Http\Request;
use Modules\Users\App\Http\Requests\Register;
use Modules\Users\App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function activeCode(Request $request){
        $data=$request->validate([
            'code_type'=>'required|in:email,mobile',
            'code'=>'required|integer',
        ]);

        $user=collect(  authApi()->user());

        // if(request('code_type')=='mobile')
        // {
        //     // 7T9RKRTYL59YRAL99QM3EVNJ
        //     if($user->mobile_code==request('code')){
        //         User::find(authApi()->user()->id)->update([
        //             'mobile_verified_at'=>now(),
        //             'mobile_code'=>null
        //         ]);
        //         $message=__('main.mobile_active_successfully');
        //     }else{
        //         $message=__('main.wrong_code');
        //     }
        // }
        // elseif(request('code_type')=='email'){
            if($user[request('code_type')."_code"]==request('code')){

                User::find(authApi()->user()->id)->update([
                    request('code_type').'_verified_at'=>now(),
                    request('code_type').'_code'=>null
                ]);
               // $user>save();
                $message=__('main.'.request('code_type').'_active_successfully');
            }else{
                $message=__('main.wrong_code');
            }
        // }
        return res_data([],$message);
    }

    public function resendActiveCode(Request $request){
        $request->validate([
            'code_type'=>'required|in:email,mobile'
        ]);
        User::find(authApi()->user()->id)->update([
            request('code_type').'_verified_at'=>null,
            request('code_type').'_code'=>rand(000000,999999)
        ]);
        if(request('code_type')=='mobile')
        {
            event(new VerifyMobileByCode(User::find( authApi()->user()->id)));
        }
        elseif(request('code_type')=='email'){
            event(new VerifyEmailByCode(User::find( authApi()->user()->id)));
        }

        $message=__('main.code_sent_to',[
            'type'=>__('main.'.request('code_type'))
        ]);
        return res_data([],$message);
    }
     /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Register $request)
    {
        $data=$request->validated();
        $data['password']=bcrypt($data['password']);
        $data['mobile']=ltrim($data['mobile'],"0");
        $data['email_code']=rand(000000,999999);
        $data['mobile_code']=rand(000000,999999);

        // return $data;
        User::create( $data);
        $credentials = request(['email', 'password']);
         return $this->login($credentials);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login($credentials=null)
    {
        if($credentials==null)
        {
            $credentials = [
                'password'=>request('password'),
            ];

            $credentials[filter_var(request('account'),
            FILTER_VALIDATE_EMAIL)?'email':'mobile']=
            intval( request('account'))?ltrim(request('account'),"0"):request('account');
        }

        // return   $credentials;
        if (! $token = authApi()->attempt($credentials)) {
            return res_data(['error' => 'Unauthorized'],'', 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return res_data( authApi()->user());
        // return res_data(collect( auth()->user()));

    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        authApi()->logout();
        return res_data([],__('main.logout_msg'));
        // return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(authApi()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $data=[
            'access_token' => str( $token),
            'token_type' => 'bearer',
            'expires_in' => authApi()->factory()->getTTL() * 60
        ];
        $data['need_email_verified']=authApi()->user()->email_verified_at!=null;
        $data['need_mobile_verified']=authApi()->user()->mobile_verified_at!=null;
        return res_data($data);
    }
}
