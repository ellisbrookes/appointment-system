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
      return view('dashboard.settings.index');
    }

    public function store(Request $request)
    {
      $user = Auth::user();

      $validatedData = $request->validate([
        'settings.navigation_style' => 'required|string|in:sidebar, top_nav'
      ]);
      
      dd($request);

      $settings = $user->settings ?? [];

      $user->settings = array_merge($settings, $validatedData['settings']);
      
      $user->save();

      return redirect()->route('dashboard.settings.index')->with('alert', [
        'type' => 'success',
        'message' => 'Settings updated!',
      ]);
    }
}