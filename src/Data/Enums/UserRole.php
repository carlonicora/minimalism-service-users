<?php

namespace CarloNicora\Minimalism\Services\Users\Data\Enums;

use CarloNicora\Minimalism\Interfaces\User\Interfaces\UserRoleInterface;

enum UserRole implements UserRoleInterface
{
    case Visitor;
    case Registered;

    /**
     * @return bool
     */
    public function isVisitor(): bool
    {
        return ($this === self::Visitor);
    }
}