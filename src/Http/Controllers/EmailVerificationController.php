<?php

namespace Gurinder\LaravelAuth\Http\Controllers;


use Gurinder\LaravelAuth\Notifications\ConfirmEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gurinder\LaravelAuth\Http\Controller;

class EmailVerificationController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        if ($this->userModel = config('gauth.user_model') ?? config('auth.providers.users.model')) {
            if (class_exists($this->userModel)) {
                $this->userModel = new $this->userModel;
            }
        }
    }

    public function confirm($token)
    {
        $data = json_decode(base64_decode(decrypt($token)));

        $user = $this->userModel->where('email', $data->email)->where('email_verification_token', $data->token)->firstOrFail();

        $user->update([
            'email_verified'           => true,
            'email_verification_token' => null
        ]);

        Auth::login($user);

        return redirect(config('gauth.redirect_path_after_email_confirmation', '/home'))->with('status', 'Email confirmed');

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        return view('gauth::emails.confirm', compact('user'));
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = $this->userModel->where('email', $data['email'])->firstOrFail();

        if ($user->email_verified) {
            return redirect()->back()->withErrors(['email' => 'Email is already verified']);
        }

        $user = generateEmailConfirmationTokenForUser($user);

        $user->notify(new ConfirmEmail());

        return back()->with('status', 'Please check your email for link');

    }


}
