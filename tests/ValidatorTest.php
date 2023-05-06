<?php

declare(strict_types=1);

use Core\Utils\ValidatorFactory;
use Core\Interfaces\ValidatorInterface;

require_once 'vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

class ValidatorTest extends PHPUnit\Framework\TestCase
{

    protected function setUp(): void
    {
        
    }

    public function testValidator()
    {
        $validator = $this->getValidator();
        $validator->setFullRule('field1', 'John', 'min:3|max:5', 'Name');
        $validator->setFullRule('field2', '', 'required', 'Lastname');
        $this->assertFalse($validator->validate());
        $this->assertEquals(2, count($validator->getMessages()));
        $validator->reset();
        $validator->setFullRule('field1', 'John', 'minlength:3|maxlength:5', 'Name');
        $validator->setFullRule('field2', 'Doe', 'required|minlength:2|maxlength:4', 'Lastname');
        $this->assertTrue($validator->validate());
        $validator->setFullRule('field3', 'admin@ehukr.com', 'email', 'Email');
        $validator->setFullRule('field4', 'admin@', 'email', 'Email2');
        $validator->setFullRule('field5', '', 'notempty', 'Another field');
        $this->assertFalse($validator->validate());
        $this->assertEquals(2, count($validator->getMessages()));
        $validator->reset();
        $this->assertFalse($validator->validate());
    }

    public function getValidator(): ValidatorInterface
    {
        $factory = new ValidatorFactory();
        return $factory->getValidator();
    }
    
}
