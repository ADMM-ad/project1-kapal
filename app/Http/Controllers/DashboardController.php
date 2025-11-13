<?php

namespace App\Http\Controllers;
use App\Models\Task;
use App\Models\Hazard;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function admin()
{
    $start = Carbon::now()->subDays(6)->startOfDay(); // 7 hari = hari ini + 6 hari sebelumnya
    $end   = Carbon::now()->endOfDay();

    $tasks = Task::whereBetween('tanggal_mulai', [$start, $end])
        ->latest('tanggal_mulai')
        ->get(); // Ambil semua (tidak limit)

    $hazards = Hazard::with('user')
        ->whereBetween('tanggal_laporan', [$start, $end])
        ->latest('tanggal_laporan')
        ->get();

    return view('dashboard.admin', compact('tasks', 'hazards', 'start', 'end'));
}
public function crew()
{
    $user = Auth::user(); // User yang login

    $start = Carbon::now()->subDays(6)->startOfDay(); // 7 hari terakhir
    $end = Carbon::now()->endOfDay();

    // Task milik user login
   $tasks = Task::where(function ($query) use ($user) {
            // Task yang ditugaskan ke user ini
            $query->whereHas('detailTasks', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            // ATAU task untuk semua crew
            ->orWhere('allcrew', 'ya');
        })
        ->whereBetween('tanggal_mulai', [$start, $end])
        ->with(['detailTasks.user']) // Optional: load user
        ->latest('tanggal_mulai')
        ->get();

    // Hazard milik user login
    $hazards = Hazard::with('user')
        ->where('user_id', $user->id)
        ->whereBetween('tanggal_laporan', [$start, $end])
        ->latest('tanggal_laporan')
        ->get();

    return view('dashboard.crew', compact('tasks', 'hazards', 'start', 'end'));
}
}
