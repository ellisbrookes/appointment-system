<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index()
    {
      return view('dashboard.settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'navigation_type' => 'required|in:sidebar,topnav'
        ]);

        $user = $request->user();
        $user->navigation_type = $request->navigation_type;
        $user->save();

        return redirect()->route('dashboard.settings.index')->with('alert', [
            'type' => 'success',
            'message' => 'Settings updated!',
        ]);
    }
}