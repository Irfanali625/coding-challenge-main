<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function getSuggestions(){

        $user = Auth::user();

        $requestSended = Connection::distinct()->pluck('receiver_id');

        $suggestions = User::whereNotIn('id', $requestSended->toArray())
            ->where('id', '!=', $user->id)
            ->get();

        $response = [
            'code'=>200,
            'suggestions' => $suggestions
        ];

        return response()->json($response);
    }

    public function sendConnection(Request $request){

        $sender = auth()->user();
        $receiver = User::find($request->id);

        $checkConnection = Connection::where('receiver_id', $receiver->id)->first();

        if(!$checkConnection){
            $connectionRequest = new Connection([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'request' => 0
            ]);

            $connectionRequest->save();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Connection sent Successfully.',
                ],
                200
            );
        }
        return response()->json(
            [
                'error' => true,
                'message' => 'Connection Already send.',
            ],
            200
        );
    }

    public function getConnectionRequests(){
        $user = auth()->user();
        $sentrequests = Connection::with('receiver')
            ->where('sender_id', $user->id)
            ->where('request', 0)
            ->where('accepted', 0)->get();

        $response = [
            'code'=>200,
            'suggestions' => $sentrequests
        ];

        return response()->json($response);
    }

    public function getRecentRequests(){
        $user = auth()->user();
        $recentRequests = Connection::with('receiver')
            ->where('receiver_id', $user->id)
            ->where('request', 1)
            ->where('accepted', 0)->get();

        $response = [
            'code'=>200,
            'suggestions' => $recentRequests
        ];

        return response()->json($response);
    }

    public function acceptConnectionRequest(Request $request){

        $checkConnection = Connection::find($request->id);

        if($checkConnection){
            $checkConnection->update([
                'accepted' => 1,

            ]);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Connection Accepted.',
            ],
            200
        );
    }

    public function getConnection(){
        $user = auth()->user();
        $connections = Connection::with('receiver')
            ->where('sender_id', $user->id)
            ->where('accepted', 1)->get();

        $response = [
            'code'=>200,
            'suggestions' => $connections
        ];

        return response()->json($response);
    }

    public function removeConnectionRequest(Request $request){

        $checkConnection = Connection::find($request->id);

        if($checkConnection){
            $checkConnection->delete();
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Connection Removed.',
            ],
            200
        );
    }


    public function getCommonConnections($id)
    {
        $user= auth()->user();

        $serConnections = $user->sentConnectionRequests()->pluck('receiver_id');

        $otherUserConnections = Connection::where('sender_id', $id)
            ->orWhere('receiver_id', $id)
            ->pluck('receiver_id', 'sender_id');

        $checkCommonConnections = $serConnections->intersect($otherUserConnections);

        $commonConnections = User::whereIn('id', $checkCommonConnections)->get();


        $response = [
            'code'=>200,
            'commonConnections' => $commonConnections
        ];

        return response()->json($response);
    }

    public function getConnectionsCount(){
        $user = Auth::user();

        $requestSended = Connection::distinct()->pluck('receiver_id');
        $commonConnections = User::whereNotIn('id', $requestSended->toArray())
            ->where('id', '!=', $user->id)
            ->count();

        $sentRequestsCount = Connection::with('receiver')
            ->where('sender_id', $user->id)
            ->where('request', 0)
            ->where('accepted', 0)->count();

        $recentRequestsCount = Connection::with('receiver')
            ->where('receiver_id', $user->id)
            ->where('request', 1)
            ->where('accepted', 0)->count();

        $connectionsCount = Connection::with('receiver')
            ->where('sender_id', $user->id)
            ->where('accepted', 1)->count();

        $responseObject = [
            'commonConnections' => $commonConnections,
            'recentRequestsCount' => $recentRequestsCount,
            'sentRequestsCount' => $sentRequestsCount,
            'connectionsCount' => $connectionsCount,
        ];

        $response = [
            'code'=>200,
            'count' => $responseObject
        ];

        return response()->json($response);
    }
}
