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

    protected ValidatorEngineInterface $validators;
    protected array $ignored = array();
    protected array $rules = array();
    protected array $values = array();

    public function __construct(ValidatorEngineInterface $validators)
    {
        $this->validators = $validators;
        $this->setLocales();
    }

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

    public function setValues(array $fields): self
    {
        foreach ($fields as $field => $value) {
            $this->setValue($field, $value);
        }
        return $this;
    }

    public function setValue(string $field, mixed $value): self
    {
        $this->values[$field] = $value;
        return $this;
    }

    public function setRules(array $rules): self
    {
        foreach ($rules as $field => $rule) {
            $this->setRule($field, $rule);
        }
        return $this;
    }

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

    public function setFullRule(string $field, mixed $value, string $rule, string $name = ''): self
    {
        $this->setRule($field, $rule);
        $this->setValue($field, $value);
        if (!empty($name)) {
            $this->validators->setName($field, $name);
        }
        return $this;
    }

    public function reset(): self
    {
        $this->rules = [];
        $this->values = [];
        $this->validators->clearNames();
        $this->validators->clearMessages();
        return $this;
    }

    public function getMessages(): array
    {
        return $this->validators->getMessages();
    }

    private function setLocales(): void
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'locales';
        bindtextdomain('le7-validator', $path);
        textdomain('le7-validator');
    }

}
