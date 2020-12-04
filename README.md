# PHP Bank Card Validator

[![Latest Stable Version](https://poser.pugx.org/samyan/bankcard-validator/v)](//packagist.org/packages/samyan/bankcard-validator)
[![Total Downloads](https://poser.pugx.org/samyan/bankcard-validator/downloads)](//packagist.org/packages/samyan/bankcard-validator)
[![License](https://poser.pugx.org/samyan/bankcard-validator/license)](//packagist.org/packages/samyan/bankcard-validator)

Validates debit and credit cards numbers against regular expressions and Luhn algorithm for **PHP 7.0+**
Also validates the CVC and the expiration date.
Project project based on and inspired by [inacho/php-credit-card-validator](https://github.com/inacho/php-credit-card-validator)

## Installation

Require the package in `composer.json`

```json
"require": {
    "samyan/bankcard-validator": "1.*"
},
```

## Actual suported Bank Cards
**Debit cards**
* Visa Electron
* Maestro
* Forbrugsforeningen
* Dankort

**Credit cards**
* Visa
* Mastercard
* Amex
* Diners Club
* Discover
* UnionPay
* JCB (Japan Credit Bureau)
* Elo
* Hipercard
* Mir
* UATP (Universal Air Travel Plan)
* RuPay

## Usage

### Validate a card number knowing the type:

```php
$card = BankCard::validateCardNumber('5500005555555559', 'mastercard');
print_r($card);
```

Output:

```
Array
(
    [valid] => 1
    [number] => 5500005555555559
    [type] => mastercard
)
```

### Validate a card number and return the type:

```php
$card = BankCard::validateCardNumber('371449635398431');
print_r($card);
```

Output:

```
Array
(
    [valid] => 1
    [number] => 371449635398431
    [type] => amex
)
```

### Validate the CVC

```php
$validCvc = BankCard::isValidCvc('234', 'visa');
var_dump($validCvc);
```

Output:

```
bool(true)
```

### Validate the expiration date

```php
$validDate = BankCard::isValidDate('2013', '07'); // past date
var_dump($validDate);
```

Output:

```
bool(false)
```