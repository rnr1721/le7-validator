<?php

namespace Core\Utils;

use Core\Interfaces\ValidatorInterface;
use Core\Interfaces\ValidatorEngineInterface;
use Core\Interfaces\ValidatorFactoryInterface;

class ValidatorFactory implements ValidatorFactoryInterface
{
    public function getValidator(?ValidatorEngineInterface $engine = null): ValidatorInterface
    {
        if (!$engine) {
            $engine = $this->getValidatorEngine();
        }
        return new Validator($engine);
    }

    public function getValidatorEngine(): ValidatorEngineInterface
    {
        return new ValidatorEngine();
    }

}
