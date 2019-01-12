<?php

namespace App\TypeDoctrine;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use function fopen;
use function fseek;
use function fwrite;
use function is_resource;
use function is_string;

/**
 * Type that maps an SQL DATE to a PHP Date object.
 *
 * @author Alexander Ceballos luna
 */
class LongBlobType extends Type
{



    const LONGBLOB = 'longblob';



    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDoctrineTypeMapping('LONGBLOB');
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if (is_string($value)) {
            $fp = fopen('php://temp', 'rb+');
            fwrite($fp, $value);
            fseek($fp, 0);
            $value = $fp;
        }

        if ( ! is_resource($value)) {
            throw ConversionException::conversionFailed($value, self::LONGBLOB);
        }

        return $value;
    }


    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value === null) ? null : base64_encode($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::LONGBLOB;
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function getBindingType()
//    {
//        return ParameterType::LARGE_OBJECT;
//    }



}