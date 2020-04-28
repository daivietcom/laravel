<?php namespace Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Repositories\User\UserRepositoryInterface as UserRepository;
use VnSource\Traits\ViewTrait;
use Http\Requests\User\PasswordRequest;
use Socialize;
use Avatar;
use Agent;

class MyController extends \App\Http\Controllers\Controller
{
    use ViewTrait;

    protected $lockFields = ['id', 'status', 'group_code', 'money', 'social', 'option', 'extend', 'created_at', 'updated_at'];

    public function getProfile()
    {
        return $this->assetView('profile', 'my');
    }

    public function postProfile(Request $request)
    {
        $user = $request->user();
        $inputs = $this->cleanAttrs($request->all());
        foreach ($inputs as $key => $val) {
            if (!empty($val)) {
                $user->{$key} = $val;
            }
        }

        return $user->save()?
            $this->sendResponse($request, ['status' => true]):
            $this->sendFailedResponse($request, ['status' => false])
        ;
    }

    public function getSetting()
    {
        return $this->assetView('setting', 'my');
    }

    public function postPassword(PasswordRequest $request)
    {
        $user = $request->user();
        $inputs = $request->only(['password_old', 'password']);

        if (Hash::check($inputs['password_old'], $user->password)) {
            $user->password = bcrypt($inputs['password']);
            return $user->save()?
                $this->sendResponse($request, ['status' => true]):
                $this->sendFailedResponse($request, ['status' => false])
            ;
        } else {
            return $this->sendFailedResponse($request, ['password_old' => __('Old password is not correct.')]);
        }
    }

    public function postResetApiToken(Request $request)
    {
        $user = $request->user();
        $user->api_token = str_random(60);

        return $user->save()?
            $this->sendResponse($request, ['token' => $user->api_token]):
            $this->sendFailedResponse($request, ['status' => false])
        ;
    }

    protected function sendResponse(Request $request, $response) {
        if ($request->expectsJson()) {
            return response()->json($response);
        }

        return back()->with($response);
    }

    protected function sendFailedResponse(Request $request, $response) {
        if ($request->expectsJson()) {
            return response()->json($response, 422);
        }

        return back()->withErrors($response);
    }

    protected function cleanAttrs($attributes) {
        foreach ($this->lockFields as $lockField) {
            unset($attributes[$lockField]);
        }
        return $attributes;
    }

}
