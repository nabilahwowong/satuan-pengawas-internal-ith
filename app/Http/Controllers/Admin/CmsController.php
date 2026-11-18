<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivitySchedule;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CmsController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        $schedules = ActivitySchedule::latest()->paginate(10);
        return view('admin.cms.index', compact('posts', 'schedules'));
    }

    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Informasi atau berita berhasil dipublikasikan.');
    }

    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after_or_equal:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        ActivitySchedule::create(array_merge($validated, [
            'created_by' => Auth::id(),
        ]));

        return back()->with('success', 'Agenda kegiatan pengawasan berhasil ditambahkan.');
    }
}
