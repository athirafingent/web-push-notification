<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;

class UserController extends Controller
{
    protected $serverKey;
 
    public function __construct()
    {
        $this->serverKey = config('app.firebase_server_key');
    }

    public function sendMessage(Request $res) {

        $data = array('post_id'=>'12345','post_title'=>'A Blog post');
        $target = $res->target;
    
        //FCM api URL
        $url = 'https://fcm.googleapis.com/fcm/send';
                    
        $fields = array();
        $fields['data'] = $data;
        if(is_array($target)){
            $fields['registration_ids'] = $target;
        }else{
            $fields['to'] = $target;
        }
        
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='. $this->serverKey,
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    

    // public function saveToken (Request $request)
    // {
    //     $user = User::find($request->user_id);
    //     $user->device_token = $request->fcm_token;
    //     $user->save();

    //     if($user)
    //         return response()->json([
    //             'message' => 'User token updated'
    //         ]);

    //     return response()->json([
    //         'message' => 'Error!'
    //     ]);
    // }

    // public function sendPush (Request $request)
    // {
    //     $user = User::find($request->id);
    //     $data = [
    //         "to" => $user->device_token,
    //         "notification" =>
    //             [
    //                 "title" => 'Web Push',
    //                 "body" => "Sample Notification",
    //                 "icon" => url('/logo.png')
    //             ],
    //     ];
    //     dd($data);
    //     $dataString = json_encode($data);
  
    //     $headers = [
    //         'Authorization: key=' . $this->serverKey,
    //         'Content-Type: application/json',
    //     ];
  
    //     $ch = curl_init('');
  
    //     curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
  
    //     curl_exec($ch);

    //     return redirect('/home')->with('message', 'Notification sent!'); 
    // }
}
