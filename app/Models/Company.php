<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = ['name'];

    public function stockPrices(): HasMany
    {
        return $this->hasMany(StockPrice::class, 'company_id', 'id');
    }
}
