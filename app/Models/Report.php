<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    protected $table = 'reports';
    protected $fillable = [
        'incident_date',
        'incident_description',
        'incident_type',
        'province',
        'city',
        'phone_number',
        'email'
    ];

    public $timestamps = false;


}
