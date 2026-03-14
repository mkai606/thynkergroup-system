<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandLead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'brand_name',
        'product_category',
        'campaign_goal',
        'budget_range',
        'status',
        'admin_notes',
    ];
}
