<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function bills()
    {
        return $this->belongsToMany(Bill::class)->withPivot('quantity');
    }
}
