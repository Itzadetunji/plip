<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "keywords",
        "icon",
        "enabled"
    ];
    

    protected $casts = [
        "keywords" => "array",
    ];
}
