<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    public $timestamps = false;

    protected $fillable = [
        'husband_id', 'husband_date', 'husband_sign',
        'wife_id', 'wife_date', 'wife_sign'
    ];

    public static function checkAndCreate(array $fields)
    {
        $count = static::where('husband_id', $fields['husband_id'])->count();
        if ($count == 0) {
            static::create($fields);
        }
    }
}
