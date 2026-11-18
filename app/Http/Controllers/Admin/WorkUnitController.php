<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkUnit;
use Illuminate\Http\Request;

class WorkUnitController extends Controller
{
    public function index()
    {
        $units = WorkUnit::latest()->paginate(10);
        return view('admin.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_code' => ['required', 'string', 'max:50', 'unique:work_units,unit_code'],
            'name' => ['required', 'string', 'max:255'],
            'leader_name' => ['nullable', 'string', 'max:255'],
            'leader_nip' => ['nullable', 'string', 'max:50'],
        ]);

        WorkUnit::create($validated);
        return redirect()->route('admin.units.index')->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    public function update(Request $request, WorkUnit $unit)
    {
        $validated = $request->validate([
            'unit_code' => ['required', 'string', 'max:50', 'unique:work_units,unit_code,' . $unit->id],
            'name' => ['required', 'string', 'max:255'],
            'leader_name' => ['nullable', 'string', 'max:255'],
            'leader_nip' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ]);

        $unit->update($validated);
        return redirect()->route('admin.units.index')->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy(WorkUnit $unit)
    {
        $unit->delete();
        return redirect()->route('admin.units.index')->with('success', 'Unit kerja berhasil dinonaktifkan.');
    }
}
