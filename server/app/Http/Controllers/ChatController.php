<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Events\NewMessage;
use Illuminate\Http\Request;
use App\Http\Requests\NewMessageRequest;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Model\Message;
class ChatController extends Controller
{
    /**
     * this method simple initialize to login with the user name
     * and get jwt token so we can connect to the api end point
     * the route pathes are in roure/api.php
     */
    public function postInit($user=null)
    {
        /**
         * here i need a helper class has some function that i will need so in the service provider
         * i bind this class so i can use it where ever i want
         * the class is in app/Classes/Helper.php
         */
        $helper = app()->make('helper');
        $log=[
            "method",
            "type",
            "error",
            "user",
            "data"
        ];
        try {
            $userData = User::where('name',$user)->first();
            // attempt to verify the credentials and create a token for the user
            if ($userData==null) {
                $log['method']="POST";
                $log['response']=json_encode(['error' => 'invalid_credentials']);
                $log['request']="$user";
                $log['user']="anonymous";
                $helper->log($log);
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
            else
            {
                $token = JWTAuth::fromUser($userData);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            $log['method']="POST";
            $log['response']=json_encode(['error' => 'could_not_create_token']);
            $log['request']="$user";
            $log['user']="anonymous";
            $helper->log($log);
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $log['method']="POST";
        $log['response']="token sent";
        $log['request']="$user";
        $log['user']=$userData->email;
        $helper->log($log);
        $myData=$userData;
        $allUsers=User::where('id',"!=",$userData->id)->get();
        return response()->json(compact('token','allUsers','myData'),200);
    }
    /**
     * postNewMessage
     * This methode is used to send a message after sending a message and saved to the data base
     * iam firing an event to a chanel that connet to the redis server
     * after iam firing the event laravel brodcast will handel the connection to the redis
     * node js will lestin to the redis and when there is a message it will send to the clinet by socket.io
     * here iam using NewMessageRequest from app/Http/Request/NewMessageRequest.php
     */
    public function postNewMessage(NewMessageRequest $request)
    {
        $helper = app()->make('helper');
        $newMessageData=$request->except(['token']);
        $newMessageData['user']=$request->user()->id;
        $toUser=User::find($newMessageData['to']);
        if($toUser!=null)
        {
            $newMessageData['to']=$toUser->id;
            $newMessage=Message::create($newMessageData);
            /**here the new message event that take an opject from Message it will be found in 
             * app/Events/NewMessage.php
             */
            event(new NewMessage($newMessage));
            $log['method']="POST";
            $log['request']=$request->except(['token']);
            $log['response']=json_encode(compact('newMessageData'));
            $log['user']=$request->user()->email;
            $helper->log($log);
            return response()->json(compact('newMessage'),200,[],JSON_UNESCAPED_UNICODE);
        }
        else
        {
            $log['method']="POST";
            $log['request']=$request->except(['token']);
            $log['response']=json_encode(["error"=>"reciver not found"]);
            $log['user']=$request->user()->email;
            $helper->log($log);
            return response()->json(["error"=>"reciver not found"],422,[],JSON_UNESCAPED_UNICODE);
        }
    }
    /**
     * getHistory
     * This methode is used to get the previos messages
     */
    public function getHistory($with,Request $request)
    {
        $helper = app()->make('helper');
        $user=$request->user()->id;
        $message=Message::where(function($q)use($user,$with){
            $q->where('user',$user)
            ->where('to',$with);
        })
        ->orWhere(function($q)use($user,$with){
            $q->where('to',$user)
            ->where('user',$with);
        })
        ->orderBy("created_at",'DESC')
        ->paginate(7);
        $log['method']="GET";
        $log['request']=json_encode($request->except(['token']));
        $log['response']=json_encode(compact('message'));
        $log['user']=$request->user()->email;
        $helper->log($log);
        return response()->json($message,200,[],JSON_UNESCAPED_UNICODE);
    }
}