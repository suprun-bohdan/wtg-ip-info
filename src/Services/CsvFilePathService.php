<?php

namespace IpCountryDetector\Services;

class CsvFilePathService
{
    private const TEMP_CSV_FILE = 'asn-country-ipv4.csv';

    /**
     * Get the full path to the CSV file.
     *
     * @return string
     */
    public function getCsvFilePath(): string
    {
        return storage_path(self::TEMP_CSV_FILE);
    }
}
