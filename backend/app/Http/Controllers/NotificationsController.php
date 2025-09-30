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
            ->with(['task:id,is_completed'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->through(function (Notification $n) {
                return [
                    'id' => $n->id,
                    'user_id' => $n->user_id,
                    'task_id' => $n->task_id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'is_read' => $n->is_read,
                    'created_at' => $n->created_at,
                    'is_completed' => optional($n->task)->is_completed ?? false,
                ];
            });
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


