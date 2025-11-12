<?php

namespace App\Http\Controllers;

use App\Models\Hazard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HazardController extends Controller
{

    public function index(Request $request)
{
    $query = Hazard::with('user')->select('id', 'user_id', 'tanggal_laporan', 'judul_laporan', 'created_at');

    // Pencarian judul
    if ($search = $request->get('search')) {
        $query->where('judul_laporan', 'like', "%{$search}%");
    }

    // Filter tanggal
    if ($date = $request->get('tanggal')) {
        $query->whereDate('tanggal_laporan', $date);
    }

    $hazards = $query->latest()->paginate(10)->appends($request->query());

    return view('hazard.index', compact('hazards'));
}
    public function create()
    {
        return view('hazard.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date',
            'judul_laporan' => 'nullable|string|max:255',
            'deskripsi_laporan' => 'nullable|string',
        ], [
            'tanggal_laporan.required' => 'Tanggal laporan wajib diisi.',
            'tanggal_laporan.date' => 'Format tanggal tidak valid.',
        ]);

        Hazard::create([
            'user_id' => Auth::id(), // ID user yang login
            'tanggal_laporan' => $request->tanggal_laporan,
            'judul_laporan' => $request->judul_laporan,
            'deskripsi_laporan' => $request->deskripsi_laporan,
        ]);

        return redirect()->route('hazard.create')
                         ->with('success', 'Laporan hazard berhasil disimpan!');
    }
    public function edit(Hazard $hazard)
{
    // Cek apakah user yang login adalah pembuat laporan
    if ($hazard->user_id !== Auth::id()) {
        return redirect()->route('hazard.create')
                         ->with('error', 'Anda tidak diizinkan mengedit laporan ini.');
    }

    return view('hazard.edit', compact('hazard'));
}

public function update(Request $request, Hazard $hazard)
{
    // Cek ownership
    if ($hazard->user_id !== Auth::id()) {
        return back()->with('error', 'Aksi tidak diizinkan.');
    }

    $request->validate([
        'tanggal_laporan' => 'required|date',
        'judul_laporan' => 'nullable|string|max:255',
        'deskripsi_laporan' => 'nullable|string',
    ], [
        'tanggal_laporan.required' => 'Tanggal laporan wajib diisi.',
        'tanggal_laporan.date' => 'Format tanggal tidak valid.',
    ]);

    $hazard->update([
        'tanggalTIMESTAMP' => $request->tanggal_laporan,
        'judul_laporan' => $request->judul_laporan,
        'deskripsi_laporan' => $request->deskripsi_laporan,
    ]);

    return redirect()->route('hazard.create')
                     ->with('success', 'Laporan hazard berhasil diperbarui!');
}
public function destroy(Hazard $hazard)
{
    if ($hazard->user_id !== Auth::id()) {
        return back()->with('error', 'Aksi tidak diizinkan.');
    }

    $hazard->delete();

    return redirect()->route('hazard.index')
                     ->with('success', 'Laporan hazard berhasil dihapus!');
}
}
