<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index(){
        // Mengupdate semua notifikasi yang belum dibaca menjadi dibaca (is_read = true)
        Notification::where('user_id', auth()->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Mengambil notifikasi dengan pagination (misalnya 10 per halaman)
        $notifications = Notification::where('user_id', auth()->user()->id)
            ->paginate(1);

        return view('apps-crm-messages', [
            'title' => 'messages',
            'user' => auth()->user(),
            'notifications' => $notifications, // Mengirimkan data notifikasi dengan pagination ke view
        ]);
    }

    public function deleteNotification(Request $request)
    {
        // Validasi bahwa role_id dikirim
        $request->validate([
            'notification_id' => 'required|exists:notifications,id',
        ]);

        // Hapus notifikasi
        Notification::where('id', $request->notification_id)
            ->where('user_id', auth()->user()->id) // Pastikan hanya notifikasi milik user yang dihapus
            ->delete();

        // Redirect dengan pesan sukses
        return redirect('/message')->with('success', 'Notification deleted successfully');
    }
}
