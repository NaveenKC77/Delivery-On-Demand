<?php

namespace App;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

// phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace
class EmailNotVerifiedException extends AuthenticationException
{
}
