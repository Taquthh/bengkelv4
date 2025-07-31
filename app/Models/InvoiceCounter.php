<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk Invoice Counter
class InvoiceCounter extends Model
{
    protected $fillable = ['period', 'counter'];
    
    public $timestamps = true;
}
