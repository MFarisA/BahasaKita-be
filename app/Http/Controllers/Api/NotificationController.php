<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\LessonLog;
use App\Models\Lesson;
use App\Models\User;
use Carbon\Carbon;

class NotificationController extends Controller
{
    // Get all user notifications
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->get();

        return response()->json([
            'status' => 'success',
            'notifications' => $notifications,
        ]);
    }

    // Mark a notification as read
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->update(['read_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marked as read.',
        ]);
    }

    // Send study reminder if user hasn't studied in 2 days
    public function sendStudyReminders()
    {
        $users = User::all();

        foreach ($users as $user) {
            $lastLog = LessonLog::where('user_id', $user->id)->latest()->first();

            $lastActivity = $lastLog ? $lastLog->created_at : null;

            if (!$lastActivity || Carbon::parse($lastActivity)->diffInDays(now()) >= 2) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Waktunya belajar!',
                    'message' => 'Kamu belum belajar dalam 2 hari. Ayo lanjutkan progress belajarmu!',
                ]);
            }
        }

        return response()->json(['status' => 'reminders sent']);
    }

    // Notify users when new lesson is added
    // public function notifyNewLesson(Lesson $lesson)
    // {
    //     $users = User::all(); // Atau filter user yang ikut course terkait

    //     foreach ($users as $user) {
    //         Notification::create([
    //             'user_id' => $user->id,
    //             'title' => 'Pelajaran Baru Tersedia',
    //             'message' => "Pelajaran baru \"{$lesson->title}\" telah ditambahkan!",
    //         ]);
    //     }

    //     return response()->json(['status' => 'new lesson notification sent']);
    // }

    // Notify when user achieves a progress milestone
    public function checkMilestones(User $user)
    {
        $completedLessons = LessonLog::where('user_id', $user->id)->distinct('lesson_id')->count();

        $milestones = [5, 10, 20];

        foreach ($milestones as $milestone) {
            $alreadyNotified = Notification::where('user_id', $user->id)
                ->where('title', "Kamu telah menyelesaikan $milestone pelajaran!")
                ->exists();

            if ($completedLessons >= $milestone && !$alreadyNotified) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => "Kamu telah menyelesaikan $milestone pelajaran!",
                    'message' => "Kerja bagus! Terus lanjutkan progres belajarmu!",
                ]);
            }
        }

        return response()->json(['status' => 'milestone check complete']);
    }
}
