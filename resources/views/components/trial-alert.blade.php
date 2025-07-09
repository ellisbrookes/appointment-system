@auth
    @php
        $user = Auth::user();
        $subscription = $user->subscription('default');
        $showTrialAlert = false;
        $trialDaysLeft = 0;
        $alertType = 'warning';
        
        if ($subscription && $subscription->onTrial()) {
            $trialDaysLeft = $subscription->trial_ends_at->diffInDays(now());
            $showTrialAlert = true;
            
            // Change alert type based on days left
            if ($trialDaysLeft <= 1) {
                $alertType = 'danger';
            } elseif ($trialDaysLeft <= 3) {
                $alertType = 'warning';
            } else {
                $alertType = 'info';
            }
        }
    @endphp

    @if($showTrialAlert)
        <x-shared.alert :type="$alertType" class="mb-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <span class="font-semibold">
                            @if($trialDaysLeft > 1)
                                {{ $trialDaysLeft }} days left in your free trial
                            @elseif($trialDaysLeft == 1)
                                Last day of your free trial
                            @else
                                Your free trial expires today
                            @endif
                        </span>
                        <span class="block text-sm opacity-90 mt-1">
                            Upgrade now to continue using all features without interruption
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-3 ml-4">
                    @if($trialDaysLeft <= 3)
                        <a href="{{ route('pricing') }}" class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md bg-white bg-opacity-20 hover:bg-opacity-30 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Upgrade Now
                        </a>
                    @else
                        <a href="{{ route('pricing') }}" class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-md bg-white bg-opacity-20 hover:bg-opacity-30 transition-colors">
                            View Plans
                        </a>
                    @endif
                    <button onclick="this.closest('[class*=\"border-l-4\"]').style.display='none'" class="opacity-70 hover:opacity-100 transition-opacity">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </x-shared.alert>
    @endif
@endauth
