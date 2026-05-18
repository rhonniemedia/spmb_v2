<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'faqs';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];

    public function category(): BelongsTo
    {
        // Parameter kedua disesuaikan dengan nama kolom foreign key di tabel faqs Anda
        return $this->belongsTo(FaqCategories::class, 'faq_category_id');
    }
}
