<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function get(Request $request) {
        $id = $request->id;
        $user = User::findOrFail($id);
        return response()->json($user);
    }
}
