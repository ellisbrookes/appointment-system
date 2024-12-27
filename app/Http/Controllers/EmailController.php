<?php

namespace App\Http\Controllers;

use App\Mail\TestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendTestEmail()
    {
        $data = ['message' => 'This is a test email.'];
        Mail::to('hello@ebrookes.dev')->send(new TestEmail($data));

        return 'Email sent successfully!';
    }
}
