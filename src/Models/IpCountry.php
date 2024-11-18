<?php

namespace IpCountryDetector\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, int $ipLong)
 * @method static insertOrIgnore(array $batch)
 * @method static truncate()
 */
class IpCountry extends Model
{
    protected $table = 'ip_country';

    protected $fillable = [
        'first_ip',
        'last_ip',
        'country',
        'region',
        'subregion',
        'city',
        'timezone'
    ];

    public $timestamps = true;
}
