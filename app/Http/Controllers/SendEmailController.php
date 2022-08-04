<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;
use App\Mail\NotifyMail;
class SendEmailController extends Controller
{
     
    public function index()
    {
		//die('==');
		Mail::to('sanjay.techvoi@gmail.com')->send(new NotifyMail());
		return ('Great! Successfully send in your mail');
    } 
}
