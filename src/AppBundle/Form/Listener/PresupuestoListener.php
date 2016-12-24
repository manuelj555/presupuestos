<?php

namespace AppBundle\Form\Listener;

use AppBundle\Util;
use Symfony\Component\Form\FormEvent;

/**
 * Description of PresupuestoListener
 *
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
class PresupuestoListener
{

    public function onPreBind(FormEvent $form)
    {
        $data = $event->getData();
        if (isset($data['descripciones'])) {
            $data['descripciones'] = array_values(Util::removeEmpty($data['descripciones']));
            $event->setData($data);
        }
    }

}
