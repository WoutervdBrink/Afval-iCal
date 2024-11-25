<?php

namespace App\Ximmio\Models;

use Illuminate\Support\Str;

readonly class Address
{
    public function __construct(
        public int $id,
        public string $companyCode,
        public string $street,
        public string $houseNumber,
        public string $postalCode,
        public string $city,
    )
    {
    }

    public static function fromData(string $companyCode, array $data): self
    {
        return new self(
            $data['UniqueId'],
            $companyCode,
            $data['Street'],
            sprintf(
                '%s%s%s%s',
                    $data['HouseNumber'],
                    $data['HouseLetter'],
                    $data['HouseNumberAddition'],
                    $data['HouseNumberIndication'],
            ),
            preg_replace('/[^A-Z0-9]/', '', Str::upper($data['ZipCode'])),
            $data['City'],
        );
    }

    public static function of(string $companyCode, int $id): self
    {
        return new self(
            $id,
            $companyCode,
            '',
            '',
            '',
            ''
        );
    }
}
