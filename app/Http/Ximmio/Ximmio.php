<?php

namespace App\Http\Ximmio;

use App\Enums\CollectionType;
use App\Models\Address;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Ximmio
{
    const ROOT_URL = 'https://wasteapi.ximmio.com/api/';

    private static function getCachedRequest(string $key, string $url, array $body): array
    {
        $key = 'ximmio/http/'.$key.'/v1';

        if (Cache::has($key)) {
            return Cache::get($key);
        }

        $data = Http::asForm()->post($url, $body)->json();

        Cache::put($key, $data);

        return $data;
    }

    public static function getAddress(Company $company, string $postalCode, string $houseNumber): Address
    {
        $companyCode = $company->code;

        $key = sha1(sprintf('getAddress/%s/%s/%s', $company->name, $postalCode, $houseNumber));

        $data = self::getCachedRequest($key, self::ROOT_URL . 'FetchAdress', [
            'postCode' => $postalCode,
            'houseNumber' => $houseNumber,
            'companyCode' => $companyCode
        ]);

        if (!isset($data['dataList']) || count($data['dataList']) === 0) {
            throw new \InvalidArgumentException(__('ximmio.no_address'));
        }

        $addressData = $data['dataList'][0];

        return Address::updateOrCreate(
            ['id' => $addressData['UniqueId']],
            [
                'company_code' => $companyCode,
                'street' => $addressData['Street'],
                'house_number' => sprintf(
                    '%s%s%s%s',
                    $addressData['HouseNumber'],
                    $addressData['HouseLetter'],
                    $addressData['HouseNumberAddition'],
                    $addressData['HouseNumberIndication'],
                ),
                'postal_code' => preg_replace('/[^A-Z0-9]/', '', Str::upper($addressData['ZipCode'])),
                'city' => $addressData['City']
            ]
        );
    }

    /**
     * @param Address $address
     * @return array<Collection>
     */
    public static function getCollections(Address $address): array
    {
        $key = sprintf('getCollections/%d', $address->id);

        $data = self::getCachedRequest($key, self::ROOT_URL.'GetCalendar', [
            'uniqueAddressID' => $address->id,
            'startDate' => now()->format('Y-m-d'),
            'endDate' => now()->addYear()->format('Y-m-d'),
            'companyCode' => $address->company->code,
            'community' => 'Enschede',
        ]);

        if (!isset($data['dataList'])) {
            return [];
        }

        $collectionsData = $data['dataList'];
        $collections = [];

        foreach ($collectionsData as $collectionData) {
            $type = $collectionData['_pickupTypeText'];

            foreach ($collectionData['pickupDates'] as $pickupDate) {
                $collections[] = new Collection(
                    new Carbon($pickupDate),
                    $type
                );
            }
        }

        return $collections;
    }
}
