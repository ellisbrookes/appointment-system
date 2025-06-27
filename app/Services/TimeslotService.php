<?php

namespace App\Services;

use Carbon\Carbon;

class TimeslotService
{
    /**
     * Generate timeslots based on user settings
     */
    public function generateTimeslots(array $userSettings = []): array
    {
        // Ensure settings is always an array
        if (is_string($userSettings)) {
            $userSettings = json_decode($userSettings, true) ?? [];
        }
        
        // Set defaults for timeslot settings
        $defaultSettings = [
            'timeslot_start' => '09:00',
            'timeslot_end' => '17:00', 
            'timeslot_interval' => 30,
            'time_format' => '24',
            'timezone' => 'UTC'
        ];
        
        $settings = array_merge($defaultSettings, $userSettings ?? []);
        
        // Set timezone for this user
        $userTimezone = $settings['timezone'];
        
        $startTime = Carbon::createFromTimeString($settings['timeslot_start'], $userTimezone);
        $endTime = Carbon::createFromTimeString($settings['timeslot_end'], $userTimezone);
        $interval = (int)$settings['timeslot_interval'];
        $timeFormat = $settings['time_format'];

        $timeslots = [];

        while ($startTime < $endTime) {
            // Format based on user preference: 12-hour (g:i A) or 24-hour (H:i)
            $formattedTime = $timeFormat === '12' ? $startTime->format('g:i A') : $startTime->format('H:i');
            $timeslots[] = [
                'value' => $startTime->format('H:i'), // Always store in 24-hour format for consistency
                'display' => $formattedTime // Display in user's preferred format
            ];
            $startTime->addMinutes($interval);
        }

        return $timeslots;
    }

    /**
     * Get the first timeslot from generated timeslots
     */
    public function getFirstTimeslot(array $timeslots): Carbon
    {
        return count($timeslots) > 0 ? Carbon::parse($timeslots[0]['value']) : Carbon::parse('09:00');
    }
}
