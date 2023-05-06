<?php

namespace Core\Interfaces;

interface ValidatorFactoryInterface
{

    /**
     * Get ready-to-use validator
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
