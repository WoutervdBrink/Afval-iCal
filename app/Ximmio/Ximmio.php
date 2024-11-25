<?php

namespace App\Ximmio;

use App\Ximmio\Models\Address;
use App\Ximmio\Models\Collection;
use App\Ximmio\Models\WasteType;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class Ximmio
{
    const ROOT_URL = 'https://wasteapi.ximmio.com/api/';

    /**
     * Perform a request to the Ximmio API.
     *
     * @param string $method The method to call.
     * @param array $body Optional arguments for the request.
     * @throws RequestException if the request failed.
     * @throws ConnectionException if the request failed.
     */
    private static function request(string $method, array $body = []): array
    {
        $response = Http::asForm()
            ->post(self::ROOT_URL . $method, $body);

        $response->throw();

        return $response->json();
    }

    /**
     * Validate a Ximmio API response.
     *
     * @param array $body Data returned by the request.
     * @param array $rules Rules to validate the request data against.
     * @return void
     */
    private static function validate(array $body = [], array $rules = []): void
    {
        $validator = Validator::make($body, $rules);

        if ($validator->fails()) {
            throw new RuntimeException('Invalid API response data: ' . $validator->errors()->first());
        }
    }

    /**
     * Search for an address in the Ximmio database.
     *
     * @param string $companyCode Code of the company to query addresses for.
     * @param string $postalCode Postal code of the address.
     * @param string $houseNumber House number of the address.
     * @return ?Address The address with the specified parameters, if any was found.
     * @throws HttpClientException if the request fails.
     */
    public static function getAddress(string $companyCode, string $postalCode, string $houseNumber): ?Address
    {
        $data = self::request('FetchAdress', [
            'postCode' => $postalCode,
            'houseNumber' => $houseNumber,
            'companyCode' => $companyCode
        ]);

        if (empty($data['dataList'])) {
            return null;
        }

        self::validate($data, [
            'dataList' => 'array',
            'dataList.*.UniqueId' => 'required|numeric',
            'dataList.*.HouseNumber' => 'string',
            'dataList.*.HouseLetter' => 'string',
            'dataList.*.HouseNumberAddition' => 'string',
            'dataList.*.HouseNumberIndication' => 'string',
            'dataList.*.ZipCode' => 'required|string',
            'dataList.*.City' => 'required|string',
        ]);

        return Address::fromData($companyCode, $data['dataList'][0]);
    }

    /**
     * Query the Ximmio API for collection moments for the specified address.
     *
     * @param string $companyCode The code of the company to query collection moments for.
     * @param int $addressId The unique identifier of the address to query collections for.
     * @return array<Collection> The collections found for the specified address.
     * @throws HttpClientException if the request fails.
     */
    public static function getCollections(string $companyCode, int $addressId): array
    {
        $data = self::request('GetCalendar', [
            'uniqueAddressId' => $addressId,
            'startDate' => now()->subMonth()->format('Y-m-d'),
            'endDate' => now()->addYear()->format('Y-m-d'),
            'companyCode' => $companyCode,
        ]);

        if (is_null($data['dataList'])) {
            return [];
        }

        self::validate($data, [
            'dataList' => 'array',
            'dataList.*.pickupDates' => 'required|array',
            'dataList.*.pickupDates.*' => 'string|date_format:Y-m-d\TH:i:s',
            'dataList.*._pickupTypeText' => 'required|string',
        ]);

        $collections = [];

        foreach ($data['dataList'] as $collectionData) {
            $type = $collectionData['_pickupTypeText'];

            foreach ($collectionData['pickupDates'] as $pickupDate) {
                $collections[] = new Collection($type, Carbon::parse($pickupDate));
            }
        }

        return $collections;
    }

    /**
     * Get the waste types processed by the specified company.
     *
     * @param string $companyCode The company to query waste types for.
     * @return array<WasteType> The waste types found for the specified company.
     * @throws HttpClientException if the request fails.
     */
    public static function getWasteTypes(string $companyCode): array
    {
        $data = self::request('GetConfigOption', [
            'companyCode' => $companyCode,
            'configName' => 'ALL',
        ]);

        if (is_null($data['dataList'])) {
            return [];
        }

        self::validate($data, [
            'dataList' => 'array',
            'dataList.*.ConfigName' => 'sometimes|string',
            'dataList.*.Configurations.wasteName' => 'sometimes|string',
            'dataList.*.Configurations.containerNameName' => 'sometimes|string',
        ]);

        return array_map(fn (array $data): WasteType => WasteType::fromData($companyCode, $data), $data['dataList']);
    }
}
