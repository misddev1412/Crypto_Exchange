<?php


namespace App\Services\Core;


use App\Models\Core\Country;

class CountryService
{
    public function getCountries(): array
    {
        return Country::where(['is_active' => ACTIVE])->pluck('name', 'id')->toArray();
    }
}
