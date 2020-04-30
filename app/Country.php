<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name'
    ];

    public function getName()
    {
        return $this->name;
    }
    
    public function getNameFormatted()
    {
        return str_replace('-', ' ', $this->name);;
    }

    public function getId()
    {
        return $this->id;
    }

    public function statistics()
    {
        return $this->hasMany(Statistic::class);
    }
}
