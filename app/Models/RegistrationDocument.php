<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationDocument extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'registration_documents';

    protected $guarded = ['id'];
}
