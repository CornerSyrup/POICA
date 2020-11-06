<?php

namespace model;

class Localizer
{
    public static function Disarm(array $data): array
    {
        foreach ($data as $info) {
            html_entity_decode($info);
            mb_convert_encoding($info, 'UTF-8', ['SHIFT-JIS', 'UTF-8', 'auto']);
        }

        return $data;
    }
}
