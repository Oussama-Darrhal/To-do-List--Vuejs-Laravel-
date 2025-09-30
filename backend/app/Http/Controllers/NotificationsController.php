<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth('api')->id();
        $items = Notification::query()
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(15);
        return response()->json($items);
    }

    public function markRead(Request $request, int $id)
    {
        $userId = auth('api')->id();
        $n = Notification::where('user_id', $userId)->where('id', $id)->first();
        if (!$n) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $n->is_read = true; $n->save();
        return response()->json($n);
    }
}


