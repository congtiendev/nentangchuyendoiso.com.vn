<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomNotification;
use App\Models\UserNotifications;
use Illuminate\Support\Facades\Auth;

class CustomNotificationController extends Controller
{
    private $customNotification;
    private $userNotifications;

    public function __construct(CustomNotification $customNotification, UserNotifications $userNotifications)
    {
        $this->customNotification = $customNotification;
        $this->userNotifications = $userNotifications;
    }

    public function index(){
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $notifications = CustomNotification::select('custom_notification.*', 'users.name as from_name', 'users.email as from_email', 'users.avatar as from_avatar')
        ->join('users', 'users.id', '=', 'custom_notification.from')
        ->whereJsonContains('send_to', Auth::user()->id)
        ->orderBy('id', 'desc')
        ->paginate(10);
        return view('custom_notification.index',compact('notifications'));
    }

    public function readNotification(Request $request)
    {
        try {
            $this->userNotifications->where('notification_id', $request->notification_id)->where('user_id', $request->user_id)->update(['is_read' => 1]);
            $count = $this->userNotifications->where('user_id', $request->user_id)->where('is_read', 0)->count();
            return response()->json(['status' => 1, 'message' => 'Notification read successfully', 'count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong']);
        }
    }
}
