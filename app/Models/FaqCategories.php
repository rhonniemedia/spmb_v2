<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqCategories extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'faq_categories';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = ['id'];

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'faq_category_id');
    }
}
