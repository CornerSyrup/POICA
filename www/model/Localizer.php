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
    public static function LocalizeArray(array &$data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::LocalizeArray($data[$key]);
            } elseif (is_string($value)) {
                self::LocalizeString($data[$key]);
            }
        }
    }

    /**
     * Localize and disarm string data for system.
     *
     * @param string $data string to be localized.
     * @return void
     */
    public static function LocalizeString(string &$data)
    {
        $data = self::Disarm($data);
        $data = self::Encode($data);
    }

    /**
     * Disarm string to prevent injections.
     *
     * @param string $data string to be processed.
     * @return string
     */
    private static function Disarm(string &$data)
    {
        $data = htmlentities($data);
    }

    /**
     * Convert encoding to utf-8 to prevent crashes by encoding.
     *
     * @param string $data string to be processed.
     * @return void
     */
    private static function Encode(string &$data)
    {
        $data = mb_convert_encoding($data, 'UTF-8', ['Shift_JIS', 'UTF-8', 'auto']);
    }
}
