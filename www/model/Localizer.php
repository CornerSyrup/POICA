<?php

namespace model;

/**
 * Data disarm and encoding conversion service provider.
 */
class Localizer
{
    /**
     * Localize and disarm a set of data for system.
     * Non-string data are unprocessable. Internally called LocalizeString function.
     * 
     * @param array $data set of data to be processed.
     * @return array
     */
    public static function LocalizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::LocalizeArray($value);
            } elseif (is_string($value)) {
                $data[$key] = self::LocalizeString($value);
            }
        }

        return $data;
    }

    /**
     * Localize and disarm string data for system.
     *
     * @param string $data string to be localized.
     * @return void
     */
    public static function LocalizeString(string $data): string
    {
        $data = self::Disarm($data);
        $data = self::Encode($data);

        return $data;
    }

    /**
     * Disarm string to prevent injections.
     *
     * @param string $data string to be processed.
     * @return string
     */
    private static function Disarm(string $data): string
    {
        $data = htmlentities($data);

        return $data;
    }

    /**
     * Convert encoding to utf-8 to prevent crashes by encoding.
     *
     * @param string $data string to be processed.
     * @return void
     */
    private static function Encode(string $data): string
    {
        return mb_convert_encoding($data, 'UTF-8', ['UTF-8', 'SJIS']);
    }
}
