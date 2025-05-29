<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
  public function notice(): View
  {
    return view('auth.verify');
  }

  public function verify(EmailVerificationRequest $request): RedirectResponse
  {
    if ($request->user()->hasVerifiedEmail()) {
      return redirect()->route('dashboard')->with('alert', [
        'type' => 'info',
        'message' => 'Your email is already verified.'
      ]);
    }

    $request->fulfill();

    return redirect()->route('login')->with('alert', [
      'type' => 'success',
      'message' => 'Your email has been verified successfully.'
    ]);
  }

  public function resend(Request $request): RedirectResponse
  {
    if ($request->user()->hasVerifiedEmail()) {
      return redirect()->route('dashboard')->with('alert', [
        'type' => 'info',
        'message' => 'Your email is already verified.'
      ]);
    }

    $request->user()->sendEmailVerificationNotification();

    return back()->with('alert', [
      'type' => 'success',
      'message' => 'Verification link sent! Check your email.'
    ]);
  }
}
