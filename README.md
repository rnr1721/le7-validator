# le7-validator
This is a simple and convenient PHP validator. It can be used to validate form input, API requests, and any other data that needs to be validated before being processed.

## Requirements

- PHP 8.1

## Installation

```shell
composer require rnr1721/le7-validator
```

## Testing

```shell
composer test
```

## Usage

First you need to create an instance of the validator:

```php
use Core\Utils\ValidatorFactory;

$validatorFactory = new ValidatorFactory();
$validator = $validatorFactory->getValidator();
```

Then you need to define validation rules for each field:

Method setFullRule can take three arguments:

- **Field** - Field (key) for validate.
- **Value** - Data to validate
- **Rules** - Validation rules
- **Label** - Human-like name of field. It uses for get errors

```php
$validator->setFullRule('login', 'john', 'required|minlength:3|maxlength:5', 'Login');
$validator->setFullRule('email', 'admin@example.com', 'required|email', 'User email');
$validator->setFullRule('age', 35, 'min:22|max:55', 'User age');
```

And finally, you can check:

```php
if ($validator->validate()) {
    // Validation success
} else {
    // Validation not success
    $errors = $validator->getMessages();
}
```

## Present rules

Rules - is string of "|" - separated rules. For example:

```
minlength:3|maxlength:10|numeric
```

- **required**: The field must be filled
- **min:{n}**: Field value must be at least {n}
- **max:{n}**: Field value must be no more than {n}
- **minlength:{n}**: The length of the string field value must be at least {n}
- **maxlength:{n}**: The length of the string field value must be no more than {n}
- **email**: The field value must be a valid email address
- **notempty**: The field value must not be empty or contain only spaces
- **numeric**: Validates that only numeric data
- **email_dns**:  validates the format of an email address and checks if the domain part of the email address has a valid DNS record
- **url**: Validate URL
- **url_active**: If URL address is valid and exists
- **date**: Validates that value is date
- **date_format:{n}**: Validates date format: Example: date_format:Y-m-d
- **date_before:{n}**: Validate date before some date. Example: date_before:2022-05-15
- **date_after:{n}**: Validate date after some date. Example: date_after:2022-05-15
- **boolean**: Validate boolean
