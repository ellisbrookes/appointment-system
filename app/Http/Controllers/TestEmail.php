<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

Route::get('/send-email', function () {
    $data = [
        'name' => 'John Doe', // Example data
    ];

    Mail::to('recipient@example.com')->send(new TestEmail($data));

    return 'Email sent successfully!';
});
