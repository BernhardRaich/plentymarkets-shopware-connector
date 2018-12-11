<?php

namespace SystemConnector\Validator;

interface ValidatorInterface
{
    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function supports($object);

    /**
     * @param mixed $object
     */
    public function validate($object);
}
