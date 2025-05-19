<?php

namespace AdvancePHPSraper\Utils;

class Encoding
{
    public static function normalize(string $content): string
    {
        $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        return mb_convert_encoding($content, 'UTF-8', $encoding ?: 'UTF-8');
    }
}