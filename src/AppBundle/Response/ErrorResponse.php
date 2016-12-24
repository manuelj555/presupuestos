<?php

namespace AppBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Util;

class ErrorResponse extends JsonResponse
{
    const ALERT = 'alertError';
    const JGROWL = 'jgrowlError';
    const ALERT_FORM = 'alertFormError';
    const JGROWL_FORM = 'jgrowFormlError';

    public function __construct($data = array(),$callback = self::ALERT)
    {
        parent::__construct(Util::normalize($data));
        $this->setCallback($callback);
    }

}
