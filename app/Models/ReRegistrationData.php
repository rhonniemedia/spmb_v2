<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReRegistrationData extends Model
{
    use HasFactory, HasUuids;

    protected $table = 're_registration_data';

    protected $guarded = ['id'];
}
