<?php


namespace Apie\ObjectAccessNormalizer\Exceptions;


interface LocalizationableException
{
    public function getI18n(): LocalizationInfo;
}
