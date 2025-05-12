<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index()
    {
        $notifications = \App\Models\Notification::latest()->get();
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('notifications.create');
    }

    public function store(Request $request)
    {
    $request->validate([
        'title' => 'required|string|max:255',
        'notificationBody' => 'required|string',
    ]);

    \App\Models\Notification::create([
        'notification_subject' => $request->input('title'),
        'notification_body' => $request->input('notificationBody'),
    ]);

    return redirect()->back()->with('success', 'Notification sent successfully!');
}

}
