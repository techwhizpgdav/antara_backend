<?php

namespace App\Http\Controllers;

use App\Mail\SendPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    //
    public function index(){
        $maildata=[
            'title'=>'mail from web',
            'body' => 'this is mail',
        ];
        Mail::to('14911suraj@gmail.com')->send(new SendPass($maildata));

        dd('Email Sent');
        


    }
}
