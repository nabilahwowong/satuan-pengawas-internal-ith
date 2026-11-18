<?php

namespace App\Http\Controllers;

use App\Models\SpiProfile;
use App\Models\WorkProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentManagementController extends Controller
{
    // Menampilkan halaman editor profil & program kerja
    public function index()
    {
        $profile = SpiProfile::firstOrCreate(['id' => 1]);
        $programs = WorkProgram::orderBy('order_num', 'asc')->get();

        return view('content_management.index', compact('profile', 'programs'));
    }

    // Update Visi, Misi, dan Profil SPI
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'about' => ['required', 'string'],
            'vision' => ['required', 'string'],
            'mission' => ['required', 'string'],
        ]);

        $profile = SpiProfile::firstOrCreate(['id' => 1]);
        $profile->update([
            'about' => $validated['about'],
            'vision' => $validated['vision'],
            'mission' => $validated['mission'],
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Profil, visi, dan misi SPI berhasil diperbarui.');
    }

    // Tambah Program Kerja Baru
    public function storeProgram(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:terencana,berjalan,terealisasi'],
            'order_num' => ['nullable', 'integer'],
        ]);

        $highestOrder = WorkProgram::max('order_num') ?? 0;

        WorkProgram::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'order_num' => $validated['order_num'] ?: ($highestOrder + 1),
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Program kerja berhasil ditambahkan.');
    }

    // Edit Program Kerja
    public function updateProgram(Request $request, WorkProgram $program)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:terencana,berjalan,terealisasi'],
            'order_num' => ['required', 'integer'],
        ]);

        $program->update($validated);

        return back()->with('success', 'Program kerja berhasil diperbarui.');
    }

    // Hapus Program Kerja
    public function destroyProgram(WorkProgram $program)
    {
        $program->delete();
        return back()->with('success', 'Program kerja berhasil dihapus.');
    }
}
