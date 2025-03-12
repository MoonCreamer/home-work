<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function indexUsers()
    {
        $users = User::with('bankAccounts')->get();
        return response()->json($users);
    }
    public function toggleUserBlock($id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = !$user->is_blocked;
        $user->save();

        return response()->json(['message' => 'User block status updated']);
    }
}
