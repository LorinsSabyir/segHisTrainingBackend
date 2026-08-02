<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Notification::with(['sender', 'receiver'])
            ->where('receiver_id', $request->user()->id)
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $fields = $request->validate([
            'title' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'priority' => 'nullable|string|max:255',
            'action_url' => 'nullable|string|max:255',
            'action_type' => 'nullable|string|max:255',

            'receiver_id' => 'nullable|exists:users,id',
            'action_id' => 'nullable',
        ]);

        try {
            $fields['sender_id'] = $request->user()->id;

            $notification = $request->user()
                ->sentNotification()
                ->create($fields);

            return response()->json($notification, 201);

        } catch (QueryException $e) {
            Log::error('Failed to create notification: '.$e->getMessage());

            return response()->json([
                'message' => 'Unable to save notification. Please try again.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        return response()->json(
            $notification->load(['sender', 'receiver'])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {

        if ((int) $notification->sender_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to update this notification.',
            ], 403);
        }

        $fields = $request->validate([
            'title' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'priority' => 'nullable|string|max:255',
            'action_url' => 'nullable|string|max:255',
            'action_type' => 'nullable|string|max:255',

            'receiver_id' => 'nullable|exists:users,id',
            'action_id' => 'nullable',
        ]);

        try {

            $notification->update($fields);

            return response()->json($notification);

        } catch (QueryException $e) {
            Log::error('Failed to update notification: '.$e->getMessage());

            return response()->json([
                'message' => 'Unable to save notification. Please try again.',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Notification $notification)
    {
        $user = $request->user();

        if ($notification->sender_id !== $user->id && $notification->receiver_id !== $user->id) {
            return response()->json([
                'message' => 'You are not authorized to delete this notification.',
            ], 403);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully.',
            'deleted' => $notification,
        ]);
    }

    /**
     * Mark as read the notification
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        // if ($notification->receiver_id !== $request->user()->id) {
        //     return response()->json([
        //         'message' => 'Unauthorized.',
        //     ], 403);
        // }
    
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    
        return response()->json([
            'message' => 'Notification marked as read.',
            'notification' => $notification,
        ]);
    }
}
