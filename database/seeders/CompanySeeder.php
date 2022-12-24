<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    const COMPANIES = [
        [
            'code' => 'bb58e633-de14-4b2a-9941-5bc419f1c4b0',
            'name' => 'BAR Afvalbeheer'
        ],
        [
            'code' => '800bf8d7-6dd1-4490-ba9d-b419d6dc8a45',
            'name' => 'Meerlanden'
        ],
        [
            'code' => '53d8db94-7945-42fd-9742-9bbc71dbe4c1',
            'name' => 'Gemeente Almere'
        ],
        [
            'code' => '8d97bb56-5afd-4cbc-a651-b4f7314264b4',
            'name' => 'TwenteMilieu'
        ],
        [
            'code' => '9dc25c8a-175a-4a41-b7a1-83f237a80b77',
            'name' => 'Reinis'
        ],
        [
            'code' => 'f8e2844a-095e-48f9-9f98-71fceb51d2c3',
            'name' => 'ACV'
        ],
        [
            'code' => '942abcf6-3775-400d-ae5d-7380d728b23c',
            'name' => 'Waardlanden'
        ],
        [
            'code' => 'f7a74ad1-fdbf-4a43-9f91-44644f4d4222',
            'name' => 'Avalex'
        ],
        [
            'code' => '24434f5b-7244-412b-9306-3a2bd1e22bc1',
            'name' => 'Gemeente Hellendoorn'
        ],
        [
            'code' => 'b7a594c7-2490-4413-88f9-94749a3ec62a',
            'name' => 'Gemeente Meppel'
        ],
        [
            'code' => 'adc418da-d19b-11e5-ab30-625662870761',
            'name' => 'AREA'
        ],
        [
            'code' => '13a2cad9-36d0-4b01-b877-efcb421a864d',
            'name' => 'RAD'
        ],
        [
            'code' => 'c0d7007a-e57e-497d-82f7-9487c9e000d0',
            'name' => 'Gemeente Rotterdam'
        ],
        [
            'code' => '78cd4156-394b-413d-8936-d407e334559a',
            'name' => 'AVRI'
        ],
        [
            'code' => '6fc75608-126a-4a50-9241-a002ce8c8a6c',
            'name' => 'Gemeente Westland'
        ],
        [
            'code' => 'e24830f0-05c0-4851-87ff-b922f8c12830',
            'name' => 'GAD Gooi en Vechtstreek'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::COMPANIES as $company) {
            DB::table('companies')->upsert($company, ['uuid'], ['name']);
        }
    }
}
