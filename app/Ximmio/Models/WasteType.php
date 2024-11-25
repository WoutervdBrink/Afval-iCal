<?php

namespace App\Ximmio\Models;

readonly class WasteType
{
    private function __construct(
        public string $companyCode,
        public string $code,
        public string $name,
    )
    {
    }

    public static function fromData(string $companyCode, array $data): self
    {
        $name = $data['ConfigName'];

        if (!empty($data['Configurations']['wasteName'])) {
            $name = $data['Configurations']['wasteName'];
        } else if (!empty($data['Configurations']['containerName'])) {
            $name = $data['Configurations']['containerName'];
        }

        return new self($companyCode, $data['ConfigName'], $name);
    }
}
