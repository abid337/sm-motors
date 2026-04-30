<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['item_id', 'file_path', 'type', 'is_primary'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}