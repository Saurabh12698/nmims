<?php

namespace App\Http\Controllers;

use App\Secret;
use App\Spam;
use Exception;
use App\Helpline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SecretController extends Controller
{
    public function store(Request $request)
    {
        $val = Validator::make($request->all() , [
            'text' => 'required|string'
        ]);
        if($val->fails()) {
            return response()->json(['error' => $val->errors()->first()] , 422);
        }
        $secret = Secret::create([
            'user_id' => $request->user()->id,
            'secret' => $request->text,
            'negative_level' => $this->getSadnessLevel($request->text),
        ]);
        $this->sendEmergencySMS($request->user(), $secret);
        return response()->json($secret, 200);
    }

    public function markAsSpam(Request $request)
    {
        $secret = Secret::with('spam')->where('id', $request->secret_id)->first();
        if ($secret) {
            $old_count = $secret->spam_count;
            $is_marked_already = Spam::select('id')
                ->where('secret_id', $request->secret_id)
                ->where('user_id', $request->user()->id)
                ->get();
            if (count($is_marked_already)) {
                return response()->json(['error' => "You've already marked this story as spam!"], 422);
            }
            Spam::create([
                'user_id' => $request->user()->id,
                'secret_id' => $request->secret_id,
            ]);
            if ($old_count == env('MIN_SPAM_COUNT')) {
                $secret->update([
                    'spam_count' => $old_count + 1,
                    'status' => false,
                ]);
            } else {
                $secret->update([
                    'spam_count' => $old_count + 1,
                ]);
            }
            return $secret;
        } else {
            return response()->json(['error' => "We can't find that secret!"], 422);
        }
    }

    public function index() {
        $secrets = Secret::where('status' , true)->orderBy('negative_level' , 'desc')->get();
        return response()->json(['secrets' => $secrets] , 200);
    }

    private function getSadnessLevel($text)
    {
        try {
            $username = 'a28f59c9-411a-4d4c-9811-c0fa3e481f16';
            $password = 'r7MYsaQZvDtM';
            $data = json_encode(array('text' => $text));
            $URL = 'https://gateway.watsonplatform.net/tone-analyzer/api/v3/tone?version=2018-10-01';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $response = json_decode($response);
            $err = curl_error($ch);
            curl_close($ch);
            $score = 0;
            foreach ($response->document_tone->tones as $tone) {
                if ($tone->tone_id == 'sadness') {
                    $score = $tone->score * 100;
                }
            }
            if (gettype($score) == 'double') {
                return $score;
            }
            else {
                Log::info(json_encode($score));
                return 0;
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return null;
        }
    }

    public function sendEmergencySMS($user, $secret)
    {
        if ($secret->negative_level > 70) {
            try {
                $helpline = Helpline::inRandomOrder()->first();
           
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "http://api.msg91.com/api/v2/sendsms?campaign=&response=&afterminutes=&schtime=&unicode=&flash=&message=&encrypt=&authkey=&mobiles=&route=&sender=&country=91",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{ \"sender\": \"SOCKET\", \"route\": \"4\", \"country\": \"91\" , \"sms\" : [ { \"message\": \"User " . $user->name ."[". $user->mobile ."] needs urgent attention. Please look! Message: " . $secret->secret ." \", \"to\": [ \"91". $helpline->mobile . "\"] } ] }",
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_HTTPHEADER => array(
                        "authkey: 203366AItpy8buz64l5aacb717",
                        "content-type: application/json",
                    ),
                ));
    
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
            }
            catch(Exception $ex) {
                Log::error($ex->getMessage());
            }
        }
    }
}
