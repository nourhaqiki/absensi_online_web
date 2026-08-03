<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['app_name', 'company_name', 'timezone', 'attendance_required_photo', 'attendance_required_location'];
}
