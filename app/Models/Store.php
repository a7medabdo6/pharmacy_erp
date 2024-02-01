<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope(new TenantScope());
    }
    public function managers()
    {
        return $this->belongsToMany(User::class, 'store_user')->withTimestamps();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
