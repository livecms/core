<?php

namespace LiveCMS\Auth;

use Illuminate\Http\Request;
use LiveCMS\Controllers\Controller;

class VerificationController extends Controller
{
    use RedirectsUsers;

    public function __construct()
    {
        if (class_exists(\Illuminate\Routing\Middleware\ValidateSignature::class)) {
            $this->middleware(\Illuminate\Routing\Middleware\ValidateSignature::class)->only('verify');
        } else {
            $this->middleware(\LiveCMS\Middleware\ValidateSignature::class)->only('verify');
        }
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                        ? $this->redirectUserIndex()
                        : view('livecms::auth.verify');
    }
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        if ($request->route('id') == $request->user()->getKey() &&
            $request->user()->markEmailAsVerified()) {
            if (class_exists(\Illuminate\Auth\Events\Verified::class)) {
                event(new \Illuminate\Auth\Events\Verified($request->user()));
            }
        }
        return $this->redirectUserIndex()->with('success', __('Thank you. Your email has been verified.'));
    }
    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectUserIndex();
        }
        $request->user()->sendEmailVerificationNotification();
        return back()->with('resent', true);
    }
}
