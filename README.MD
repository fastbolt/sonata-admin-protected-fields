[![Composer version](https://img.shields.io/packagist/v/fastbolt/sonata-admin-protected-fields)](https://packagist.org/packages/fastbolt/sonata-admin-protected-fields)

[![Code Climate maintainability](https://img.shields.io/codeclimate/maintainability/fastbolt/sonata-admin-protected-fields)](https://codeclimate.com/github/fastbolt/sonata-admin-protected-fields)
[![Test Coverage](https://img.shields.io/codecov/c/github/fastbolt/sonata-admin-protected-fields)](https://app.codecov.io/gh/fastbolt/sonata-admin-protected-fields/)

[![Type Coverage](https://shepherd.dev/github/fastbolt/sonata-admin-protected-fields/coverage.svg)](https://shepherd.dev/github/fastbolt/sonata-admin-protected-fields)
[![Psalm Level](https://shepherd.dev/github/fastbolt/sonata-admin-protected-fields/level.svg)](https://shepherd.dev/github/fastbolt/sonata-admin-protected-fields)

[![GitHub](https://img.shields.io/github/workflow/status/fastbolt/sonata-admin-protected-fields/PHPUnit%20tests/main?label=Tests)](https://github.com/fastbolt/sonata-admin-protected-fields/actions)

# Field and Entity protection for sonata admin

This library aims to provide an easy way to protect fields from being edited in sonata admin, while still being displayed in the form.

Using the library, some entities fields may be fixed, e.g. coming from the ERP or some other system, while other fields may still be edited by the user. Furthermore, items created in the GUI might still be editable, including all fields, which are not editable for other instances.

In addition, the same checks can be used to protect entities from being deleted in the Sonata admin GUI on a per-item basis. 

The system relies on the `Fastbolt\SonataAdminProtectedFields\Protection\Checker\Checker` interface which has access to all entity properties.

For custom implementation, please head to the [Implementing custom checkers](#implementing-custom-checkers) section.

## Prerequisites

Due to the PHP Attribute usage, we need at least PHP 8.0 or higher.

For now, the bundle is tested using PHP 8.0, 8.1 and 8.2 and the [sonata admin bundle](https://github.com/sonata-project/SonataAdminBundle) version 4.9

## Installation

The library can be installed via composer:

```
composer require fastbolt/sonata-admin-protected-fields
```

## Configuration

If not configured automatically, the bundle needs to be enabled in your project's `bundles.php` file:

```php
<?php

return [
    Fastbolt\SonataAdminProtectedFields\SonataAdminProtectedFieldsBundle::class => ['all' => true],
];
```

## Usage

### Field protection

To enable dynamic field protection when editing entities in sonata admin, just add the corresponding attribute to your entity's field:

```php
<?php

#[ORM\Column(type: 'string', length: 255)]
#[SonataAdminProtectedFields\WriteProtected]
private string $name = '';
```

The default property protection is performed using the `Fastbolt\SonataAdminProtectedFields\Protection\Checker\PropertyProtectionChecker` service, which is automatically registered through the bundle configuration.

It expects your entity to implement a method `isProtected()` that returns a boolean value:

```php
public function isProtected(): bool
{
    return $this->isProtected;
}
```

### Delete protection

To enable dynamic delete protection, just add the corresponding attribute to your entity's class header:

```php
<?php

#[ORM\Entity(repositoryClass: MaterialRepository::class)]
#[SonataAdminProtectedFields\DeleteProtected]
class Material
```

The protection checks use the same mechanism as outlined in the [Field protection](#field-protection) section.

## Implementing custom checkers

Custom checkers need to implement the simple `Fastbolt\SonataAdminProtectedFields\Protection\Checker\Checker` interface:

```php
<?php

use Fastbolt\SonataAdminProtectedFields\Protection\Checker\Checker;

class MyChecker implements Checker
{
    public function getName(): string
    {
        return 'my_checker';
    }

    public function shouldBeProtected(object $item): bool 
    {
        return true;    
    }
}
```

All checkers need to have the DIC tag `sonata_admin_protected_fields_checker`. For further information on DIC tags 
please refer to the [symfony documentation](https://symfony.com/doc/current/service_container/tags.html). 

```yaml
services:
    App\MyChecker:
        tags:
            - 'sonata_admin_protected_fields.protection_checkers'
```

To enable the custom checker, the value returned from the `getName` method needs to be set in both Attributes:

```php
<?php

use Fastbolt\SonataAdminProtectedFields\Mapping\Attributes as SonataAdminProtectedFields;

#[ORM\Entity(repositoryClass: App\Repository\MyEntityRepository::class)]
#[SonataAdminProtectedFields\DeleteProtected(checker: 'my_checker')]
class MyEntity
{
    #[ORM\Column(type: 'string', length: 18)]
    #[SonataAdminProtectedFields\WriteProtected(checker: 'my_checker')]
    private string $myField = '';
}
```
