<?php

declare(strict_types=1);

namespace Core\Utils;

use Core\Interfaces\ValidatorInterface;
use Core\Interfaces\ValidatorEngineInterface;
use Core\Interfaces\ValidatorFactoryInterface;

/**
 * Validator factory that create ready-for-use validator object
 */
class ValidatorFactory implements ValidatorFactoryInterface
{

    /**
     * @inheritdoc
     */
    public function getValidator(?ValidatorEngineInterface $engine = null): ValidatorInterface
    {
        if (!$engine) {
            $engine = $this->getValidatorEngine();
        }
        return new Validator($engine);
    }

    /**
     * @inheritdoc
     */
    public function getValidatorEngine(): ValidatorEngineInterface
    {
        return new ValidatorEngine();
    }

}
