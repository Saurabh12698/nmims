<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Comment;

class CommentController extends Controller
{
    
    public function storeComment(Request $request) {
        $val = Validator::make($request->all() , [
            'secret_id' => 'required|integer|exists:secrets,id',
            'comment' => 'required|string'
        ]);
        if($val->fails()) {
            return response()->json(['error' => $val->errors()->first()] , 422);
        }
        $comment = Comment::create([
            'secret_id' => $request->secret_id,
            'user_id' => $request->user()->id,
            'comment' => $request->comment
        ]);
        return response()->json(['comment' => $comment] , 200);
    }
}
