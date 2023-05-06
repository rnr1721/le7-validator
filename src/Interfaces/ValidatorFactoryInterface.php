<?php

declare(strict_types=1);

namespace Core\Interfaces;

/**
 * This is factory that create configured validator object
 */
interface ValidatorFactoryInterface
{

    /**
     * Get ready-to-use validator
     * But you can use own engine that implements ValidatorEngineInterface
     * @param ValidatorEngineInterface|null $engine Optional ValidatorEngine
     * @return ValidatorInterface
     */
    public function getValidator(
            ?ValidatorEngineInterface $engine = null
    ): ValidatorInterface;

    /**
     * Get default validator engine
     * @return ValidatorEngineInterface
     */
    public function getValidatorEngine(): ValidatorEngineInterface;
}
