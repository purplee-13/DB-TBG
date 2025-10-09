<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'maintenances';

    protected $fillable = [
        'site_id',
        'technician',
        'description',
        'visit_date',
        'status',
        'operator',
        'notes',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
