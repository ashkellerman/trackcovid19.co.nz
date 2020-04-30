<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GroupedLastScope;

class Statistic extends Model
{
    use GroupedLastScope;

    protected $fillable = [
        'country_id',
        'cases_new',
        'cases_active',
        'cases_critical',
        'cases_recovered',
        'cases_total',
        'deaths_new',
        'deaths_total',
        'tests_total',
        'recorded_at'
    ];
    
    public function getId()
    {
        return $this->id;
    }

    public function getRecordedAtDate()
    {
        return $this->recorded_at;
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
}
