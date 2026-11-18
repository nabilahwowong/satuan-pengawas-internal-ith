<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper statis agar mudah dipanggil dari controller manapun
     */
    public static function record(string $action, string $module, string $description): void
    {
        self::create([
            'user_id' => Auth::id(),
            'action' => strtoupper($action),
            'module' => $module,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}
