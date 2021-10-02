<?php

declare(strict_types=1);

namespace App\OpenApi;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

/**
 * Class Props
 * Schemaで使用するプロパティ.
 * @SuppressWarnings("BooleanArgumentFlag")
 * @SuppressWarnings("TooManyMethods")
 * @SuppressWarnings("TooManyPublicMethods")
 * @SuppressWarnings("ExcessivePublicCount")
 * @SuppressWarnings("ExcessiveClassComplexity")
 */
class Props
{
    public static function name(bool $nullable = false, string $objectId = 'name'): PropModel
    {
        return new PropModel(Schema::string($objectId)->description('名前'), $objectId, $nullable);
    }

    public static function age(bool $nullable = false, string $objectId = 'age'): PropModel
    {
        return new PropModel(Schema::integer($objectId)->description('年齢')->minimum(0), $objectId, $nullable);
    }

    public static function birthday(bool $nullable = false, string $objectId = 'birthday'): PropModel
    {
        return new PropModel(Schema::string($objectId)->description('誕生日')->pattern('/^([0-9]+)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])[Tt]([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]|60)(\.[0-9]+)?(([Zz])|([\+|\-]([01][0-9]|2[0-3]):[0-5][0-9]))$/'), $objectId, $nullable);
    }
}
