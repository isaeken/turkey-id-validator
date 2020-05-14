# Validation of Turkey Identy Card ID

## Installation
```bash
composer require isaeken/turkey-id-validator
```
## Usage

### Verify ID
```php
<?php
if (TurkeyIdValidator::VerifyId('12345678900')) { echo "true"; }
else { echo "false"; }
```

### Verify Name
```php
<?php
if (TurkeyIdValidator::VerifyName('İsa Eken')) { echo "true"; }
else { echo "false"; }
```

### Verify Year
```php
<?php
if (TurkeyIdValidator::VerifyYear(1881)) { echo "true"; }
else { echo "false"; }
```

### Validate
```php
<?php
if (TurkeyIdValidator::Validate('xxxxxxxxxxx', 'first name', 'last name', '2000')) { echo "true"; }
else { echo "false"; }
```
