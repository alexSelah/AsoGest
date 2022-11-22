<?php
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Mail\EnviaEmail;
use Illuminate\Support\Facades\Mail;
 
class MailController extends Controller
{
    public function send(Request $request)
    {
        dd($request);
        $objDemo = new \stdClass();
        $objDemo->demo_one = 'Demo One Value';
        $objDemo->demo_two = 'Demo Two Value';
        $objDemo->sender = 'SenderUserName';
        $objDemo->receiver = 'ReceiverUserName';
 
        Mail::to("acamfue@gmail.com")->send(new EnviaEmail($objDemo));

        return view("home");
    }
}