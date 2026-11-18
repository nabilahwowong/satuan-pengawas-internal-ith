<?php

namespace App\Http\Controllers;

use App\Models\ActivitySchedule;
use App\Models\Post;
use App\Models\SpiProfile;
use App\Models\WorkProgram;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // Halaman Beranda Utama (Hanya Hero, Berita & Agenda)
    public function index()
    {
        $posts = Post::latest()->take(6)->get();
        $schedules = ActivitySchedule::where('end_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        return view('public.home', compact('posts', 'schedules'));
    }

    // Halaman Khusus Visi & Misi
    public function visionMission()
    {
        $profile = SpiProfile::firstOrCreate(['id' => 1]);
        return view('public.vision-mission', compact('profile'));
    }

    // Halaman Khusus 10 Program Kerja
    public function workPrograms()
    {
        $programs = WorkProgram::orderBy('order_num', 'asc')->get();
        return view('public.work-programs', compact('programs'));
    }

    public function showPost(Post $post)
    {
        return view('public.post-detail', compact('post'));
    }
}
