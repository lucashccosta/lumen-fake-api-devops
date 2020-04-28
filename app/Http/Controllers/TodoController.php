<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index() {
        $todos = Todo::where('user_id', Auth::user()->id)->paginate(24);

        return response()->json($todos, 200);
    }
}
