<?php

namespace App\Services;

use App\Abstracts\BaseService;

class CountryService extends BaseService
{
    public function getDailingCodeByCoutryIsoCode2(string $country): ?string
    {
        return collect(config('countries'))->where('iso_code_2', $country)->first()['dialing_code'] ?? null;
    }
}
