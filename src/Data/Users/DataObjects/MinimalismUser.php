<?php

namespace CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects;

use CarloNicora\Minimalism\Interfaces\User\Interfaces\UserInterface;

class MinimalismUser implements UserInterface
{

    /**
     * @param int $id
     * @param string $userName
     * @param string $email
     * @param array $attributes
     */
    public function __construct(
        private int $id,
        private string $userName,
        private string $email,
        private array $attributes = []
    )
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserName(): int
    {
        return $this->userName;
    }

    /**
     * @return int
     */
    public function getEmail(): int
    {
        return $this->email;
    }

    /**
     * @param string $attributeName
     * @return mixed
     */
    public function getAttribute(string $attributeName): mixed
    {
        return $this->attributes[$attributeName] ?? null;
    }
}