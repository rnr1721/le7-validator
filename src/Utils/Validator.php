<?php

declare(strict_types=1);

namespace Core\Utils;

use Core\Interfaces\ValidatorEngineInterface;
use Core\Interfaces\ValidatorInterface;
use function array_key_exists,
             method_exists,
             explode,
             in_array,
             bindtextdomain,
             textdomain;

class Validator implements ValidatorInterface
{

    /**
     * This object stores implementations of specific validators
     * They run depending on the validation instructions
     * @var ValidatorEngineInterface
     */
    protected ValidatorEngineInterface $validators;
    
    /**
     * Array of ignored fields for validation
     * @var array
     */
    protected array $ignored = array();
    
    /**
     * Validation rules is an associative array
     * in $key=>$value format where key is field name and value is rule
     * @var array
     */
    protected array $rules = array();
    
    /**
     * Values array, that is an $key=>$value associtive array,
     * where $key is field name and value is field value
     * @var array
     */
    protected array $values = array();

    /**
     * Constructor that set validation engine and set locales
     * @param ValidatorEngineInterface $validators
     */
    public function __construct(ValidatorEngineInterface $validators)
    {
        $this->validators = $validators;
        $this->setLocales();
    }

    /**
     * @inheritdoc
     */
    public function validate(): bool
    {
        if (empty($this->rules) && empty($this->values)) {
            return false;
        }
        $result = true;
        $actions = $this->getActions();
        foreach ($actions as $field => $cActions) {
            foreach ($cActions as $action => $actionValue) {
                $method = 'validate_' . $action;
                if (method_exists($this->validators, $method)) {
                    if (array_key_exists($field, $this->values)) {
                        if (!$this->validators->{$method}($field, $this->values[$field], $actionValue)) {
                            $result = false;
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function getActions(): array
    {
        $actions = [];
        foreach ($this->rules as $field => $rules) {
            $rulesArray = explode('|', $rules);
            foreach ($rulesArray as $ruleUnit) {
                $currentOrerationArray = explode(':', $ruleUnit);
                if (!in_array($field, $this->ignored)) {
                    $actions[$field][$currentOrerationArray[0]] = ($currentOrerationArray[1] ?? '');
                }
            }
        }
        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function setIgnored(array|string $fields): self
    {
        if (is_string($fields)) {
            $fieldsArray = explode(',', $fields);
            foreach ($fieldsArray as $field) {
                $this->ignored[] = $field;
            }
        }
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $this->ignored[] = $field;
            }
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setValues(array $fields): self
    {
        foreach ($fields as $field => $value) {
            $this->setValue($field, $value);
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setValue(string $field, mixed $value): self
    {
        $this->values[$field] = $value;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setRules(array $rules): self
    {
        foreach ($rules as $field => $rule) {
            $this->setRule($field, $rule);
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setRule(string $field, string $rule): self
    {
        $fieldArray = explode(',', $field);
        foreach ($fieldArray as $cField) {
            $this->rules[$cField] = $rule;
            if (!array_key_exists($cField, $this->values)) {
                $this->values[$cField] = '';
            }
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setFullRule(string $field, mixed $value, string $rule, string $name = ''): self
    {
        $this->setRule($field, $rule);
        $this->setValue($field, $value);
        if (!empty($name)) {
            $this->validators->setName($field, $name);
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function reset(): self
    {
        $this->rules = [];
        $this->values = [];
        $this->validators->clearNames();
        $this->validators->clearMessages();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMessages(): array
    {
        return $this->validators->getMessages();
    }

    /**
     * @inheritdoc
     */
    private function setLocales(): void
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'locales';
        bindtextdomain('le7-validator', $path);
        textdomain('le7-validator');
    }

}
