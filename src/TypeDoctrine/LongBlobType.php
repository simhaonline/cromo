<?php

namespace App\TypeDoctrine;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * Type that maps an SQL DATE to a PHP Date object.
 *
 * @author Alexander Ceballos luna
 */
class LongBlobType extends Type
{



    const LONGBLOB = 'longblob';



    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('LONGBLOB');
    }
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return ($value === null) ? null : base64_decode($value);
//        if (null === $value) {
//            return null;
//        }
//
//        if (is_string($value)) {
//            $fp = fopen('php://temp', 'rb+');
//            fwrite($fp, $value);
//            fseek($fp, 0);
//            $value = $fp;
//        }
//
//        if ( ! is_resource($value)) {
//            throw ConversionException::conversionFailed($value, self::LONGBLOB);
//        }
//
//        return $value;
    }
    public function getName()
    {
        return self::LONGBLOB;
    }
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value === null) ? null : base64_encode($value);
    }
}