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
        private readonly int    $id,
        private readonly string $userName,
        private readonly string $email,
        private readonly array  $attributes = []
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
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
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