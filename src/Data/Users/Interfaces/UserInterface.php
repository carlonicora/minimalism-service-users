<?php

namespace CarloNicora\Minimalism\Services\Users\Data\Users\Interfaces;

interface UserInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $id
     * @return void
     */
    public function setId(
        int $id,
    ): void;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(
        string $email,
    ): void;

    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @param string $username
     * @return void
     */
    public function setUsername(
        string $username,
    ): void;

    /**
     * @return string|null
     */
    public function getPassword(): ?string;

    /**
     * @param string|null $password
     * @return void
     */
    public function setPassword(
        ?string $password,
    ): void;

    /**
     * @return string|null
     */
    public function getAvatar(): ?string;

    /**
     * @param string|null $avatar
     * @return void
     */
    public function setAvatar(
        ?string $avatar,
    ): void;

    /**
     * @return array
     */
    public function getMeta(): array;

    /**
     * @param string $metaId
     * @return mixed
     */
    public function getSingleMeta(
        string $metaId,
    ): mixed;

    /** @param array $meta */
    public function setMeta(
        array $meta,
    ): void;

    /**
     * @param string $metaId
     * @param mixed $value
     */
    public function addMeta(
        string $metaId,
        mixed  $value,
    ): void;

    /**
     * @return int|null
     */
    public function getCreatedAt(): ?int;

    /**
     * @return int|null
     */
    public function getUpdatedAt(): ?int;

    /**
     * @return bool
     */
    public function isSocialLogin(): bool;

    /**
     * @param bool $isSocialLogin
     * @return void
     */
    public function setIsSocialLogin(
        bool $isSocialLogin,
    ): void;
}