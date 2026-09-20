<?php

namespace App\Http\Controllers;

use App\Services\AiService;
use Illuminate\Http\Request;

class AiWebController extends Controller {
    public function ask(Request $request, AiService $ai) {
        $request->validate(['message' => 'required|string']);
        $reply = $ai->askChatbot(auth()->user(), $request->message);
        return response()->json(['reply' => $reply]);
    }
}