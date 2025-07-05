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
    $userSettings = Auth::user()->settings;
    
    // Ensure settings is always an array
    if (is_string($userSettings)) {
      $userSettings = json_decode($userSettings, true) ?? [];
    }
    
    // Set default values if not already set
    $defaultSettings = [
      'navigation_style' => 'sidebar',
      'timeslot_start' => '09:00',
      'timeslot_end' => '17:00', 
      'timeslot_interval' => 30,
      'time_format' => '24',
      'timezone' => 'UTC'
    ];
    
    $settings = array_merge($defaultSettings, $userSettings ?? []);
    
    // Get list of common timezones for the dropdown
    $timezones = [
      'UTC' => 'UTC (Coordinated Universal Time)',
      'America/New_York' => 'Eastern Time (US & Canada)',
      'America/Chicago' => 'Central Time (US & Canada)', 
      'America/Denver' => 'Mountain Time (US & Canada)',
      'America/Los_Angeles' => 'Pacific Time (US & Canada)',
      'Europe/London' => 'London (GMT/BST)',
      'Europe/Paris' => 'Paris (CET/CEST)',
      'Europe/Berlin' => 'Berlin (CET/CEST)',
      'Europe/Rome' => 'Rome (CET/CEST)',
      'Europe/Madrid' => 'Madrid (CET/CEST)',
      'Asia/Tokyo' => 'Tokyo (JST)',
      'Asia/Shanghai' => 'Shanghai (CST)',
      'Asia/Dubai' => 'Dubai (GST)',
      'Asia/Kolkata' => 'India (IST)',
      'Australia/Sydney' => 'Sydney (AEST/AEDT)',
      'Australia/Melbourne' => 'Melbourne (AEST/AEDT)',
    ];

    return view('dashboard.settings.index', compact('settings', 'timezones'));
  }

  public function store(Request $request): RedirectResponse
  {
    $user = Auth::user();

    $request->validate([
      'settings.navigation_style' => 'required|in:sidebar,top_nav',
      'settings.timeslot_start' => 'required|date_format:H:i',
      'settings.timeslot_end' => 'required|date_format:H:i|after:settings.timeslot_start',
      'settings.timeslot_interval' => 'required|integer|min:5|max:120',
      'settings.time_format' => 'required|in:12,24',
      'settings.timezone' => 'required|string|timezone',
    ]);

    $user->settings = $request->input('settings');
        
    $user->save();

    return redirect()->route('settings')->with('alert', [
      'type' => 'success',
      'message' => 'Settings updated!',
    ]);
  }
}