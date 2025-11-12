<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\DetailTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskController extends Controller
{

public function index(Request $request)
{
    $query = Task::with(['detailTasks.user']);

    // Pencarian
    if ($search = $request->get('search')) {
        $query->where('judul', 'like', "%{$search}%");
    }

    // Filter status
    if ($status = $request->get('status')) {
        $query->where('status', $status);
    }

    $tasks = $query->latest()->paginate(10)->appends($request->query());

    return view('task.index', compact('tasks'));
}

    public function create()
    {
        $crewUsers = User::where('role', 'crew')->pluck('name', 'id');
        return view('task.create', compact('crewUsers'));
    }

   public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'tanggal_mulai' => 'required|date',
        'deadline' => 'nullable|date|after_or_equal:tanggal_mulai',
        'allcrew' => 'required|in:ya,tidak',
        'crew_ids' => 'required_if:allcrew,tidak|array',
        'crew_ids.*' => 'exists:users,id',
    ], [
        'judul.required' => 'Judul wajib diisi.',
        'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
        'deadline.after_or_equal' => 'Deadline harus setelah atau sama dengan tanggal mulai.',
        'allcrew.required' => 'Pilih opsi crew.',
        'crew_ids.required_if' => 'Pilih minimal satu crew.',
    ]);

    DB::beginTransaction();
    try {
        // Simpan Task
        $task = Task::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'allcrew' => $request->allcrew,
            'tanggal_mulai' => $request->tanggal_mulai,
            'deadline' => $request->deadline,
            'status' => 'belum',
        ]);

        // HANYA simpan ke detail_tasks jika allcrew = tidak
        if ($request->allcrew === 'tidak') {
            $userIds = $request->crew_ids;

            foreach ($userIds as $userId) {
                DetailTask::create([
                    'task_id' => $task->id,
                    'user_id' => $userId,
                ]);
            }
        }

        DB::commit();
        return redirect()->route('task.create')->with('success', 'Task berhasil dibuat!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal membuat task: ' . $e->getMessage());
    }
}
// app/Http/Controllers/TaskController.php

public function edit(Task $task)
{
    $task->load('detailTasks.user');
    $crewUsers = User::where('role', 'crew')->pluck('name', 'id');
    
    // Ambil crew yang sudah dipilih (jika allcrew = tidak)
    $selectedCrew = $task->allcrew === 'tidak' 
        ? $task->detailTasks->pluck('user_id')->toArray() 
        : [];

    return view('task.edit', compact('task', 'crewUsers', 'selectedCrew'));
}

public function update(Request $request, Task $task)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'tanggal_mulai' => 'required|date',
        'deadline' => 'nullable|date|after_or_equal:tanggal_mulai',
        'tanggal_dikerjakan' => 'nullable|date|after_or_equal:tanggal_mulai',
        'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_dikerjakan',
        'status' => 'required|in:belum,proses,selesai',
        'allcrew' => 'required|in:ya,tidak',
        'crew_ids' => 'required_if:allcrew,tidak|array',
        'crew_ids.*' => 'exists:users,id',
    ], [
        'judul.required' => 'Judul wajib diisi.',
        'status.required' => 'Status wajib dipilih.',
        'tanggal_dikerjakan.after_or_equal' => 'Tanggal dikerjakan harus setelah tanggal mulai.',
        'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah tanggal dikerjakan.',
    ]);

    DB::beginTransaction();
    try {
        // Update Task
        $task->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'deadline' => $request->deadline,
            'tanggal_dikerjakan' => $request->tanggal_dikerjakan,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
            'allcrew' => $request->allcrew,
        ]);

        // Hapus detail lama jika allcrew = tidak
        if ($request->allcrew === 'tidak') {
            $task->detailTasks()->delete(); // Hapus semua detail lama

            foreach ($request->crew_ids as $userId) {
                DetailTask::create([
                    'task_id' => $task->id,
                    'user_id' => $userId,
                ]);
            }
        } else {
            // Jika allcrew = ya → hapus semua detail
            $task->detailTasks()->delete();
        }

        DB::commit();
        return redirect()->route('task.index')->with('success', 'Task berhasil diperbarui!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal update: ' . $e->getMessage());
    }
}

public function destroy(Task $task)
    {
        DB::beginTransaction();
        try {
            $task->detailTasks()->delete();
            $task->delete();
            DB::commit();
            return redirect()->route('task.index')->with('success', 'Task berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}