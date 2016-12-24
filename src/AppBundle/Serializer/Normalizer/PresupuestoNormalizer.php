<?php
/*
 * This file is part of the Manuel Aguirre Project.
 *
 * (c) Manuel Aguirre <programador.manuel@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\Presupuesto;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class PresupuestoNormalizer extends ObjectNormalizer
{
    public function __construct(
        ClassMetadataFactoryInterface $classMetadataFactory,
        NameConverterInterface $nameConverter,
        PropertyAccessorInterface $propertyAccessor,
        PropertyTypeExtractorInterface $propertyTypeExtractor
    ) {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);

//        $this->setIgnoredAttributes(['descripciones']);

        $this->setCircularReferenceHandler(function ($p) {
            return $p;
        });
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return is_a($type, Presupuesto::class, true);
    }

    public function supportsNormalization($data, $format = null)
    {
        return is_a($data, Presupuesto::class, true);
    }
}