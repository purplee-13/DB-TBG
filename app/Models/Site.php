<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $table = 'sites';

    protected $fillable = [
        'site_code',
        'site_name',
        'service_area',
        'sto',
        'product',
        'tikor',
        'status',
    ];

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'site_id');
    }
}
