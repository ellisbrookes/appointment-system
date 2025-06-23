<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SettingsController extends Controller
{
  public function index(): View
  {
    $settings = Auth::user()->settings ?? [];

    return view('dashboard.settings.index', compact('settings'));
  }

  public function store(Request $request): RedirectResponse
  {
    $user = Auth::user();

    $request->validate([
      'settings.navigation_style' => 'required|string|in:sidebar,top_nav',
    ]);

    $user->settings = $request->input('settings');
        
    $user->save();

    return redirect()->route('settings')->with('alert', [
      'type' => 'success',
      'message' => 'Settings updated!',
    ]);
  }
}