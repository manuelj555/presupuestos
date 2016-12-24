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

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class CustomObjectNormalizer extends ObjectNormalizer
{
    private $class;

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return parent::supportsDenormalization($data, $type, $format)
        and $type === $this->class;
    }

    public function supportsNormalization($data, $format = null)
    {
        dump(is_a($data, $this->class, true), $data);
        return parent::supportsNormalization($data, $format)
        and is_a($data, $this->class, true);
    }


}