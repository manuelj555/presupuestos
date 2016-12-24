<?php

namespace AppBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Util;

class SuccessResponse extends JsonResponse
{

    const ALERT = 'alertSuccess';
    const JGROWL = 'jgrowlSuccess';

    public function __construct($message, $callback = self::JGROWL)
    {
        parent::__construct($message);
        $this->setCallback($callback);
    }

}
