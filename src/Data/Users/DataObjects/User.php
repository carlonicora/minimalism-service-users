<?php
namespace CarloNicora\Minimalism\Services\Users\Data\Users\DataObjects;

use CarloNicora\Minimalism\Interfaces\Sql\Attributes\DbField;
use CarloNicora\Minimalism\Interfaces\Sql\Attributes\DbTable;
use CarloNicora\Minimalism\Interfaces\Sql\Enums\DbFieldType;
use CarloNicora\Minimalism\Interfaces\Sql\Interfaces\SqlDataObjectInterface;
use CarloNicora\Minimalism\Services\MySQL\Traits\SqlDataObjectTrait;
use CarloNicora\Minimalism\Services\ResourceBuilder\Interfaces\ResourceableDataInterface;
use CarloNicora\Minimalism\Services\Users\Data\Users\Databases\UsersTable;
use CarloNicora\Minimalism\Services\Users\Data\Users\Interfaces\UserInterface;

#[DbTable(tableClass: UsersTable::class)]
class User implements SqlDataObjectInterface, ResourceableDataInterface, UserInterface
{
    use SqlDataObjectTrait;

    /** @var int  */
    #[DbField(field: UsersTable::userId)]
    private int $id;

    /** @var string  */
    #[DbField]
    private string $email;

    /** @var string  */
    #[DbField]
    private string $username;

    /** @var string|null  */
    #[DbField]
    private ?string $password=null;

    /** @var string|null  */
    #[DbField]
    private ?string $avatar=null;

    /** @var array|null  */
    #[DbField(fieldType: DbFieldType::Array)]
    private array|null $meta=null;

    /** @var int */
    #[DbField(fieldType: DbFieldType::IntDateTime)]
    private int $createdAt;

    /** @var int|null */
    #[DbField(fieldType: DbFieldType::IntDateTime)]
    private int|null $updatedAt = null;

    /** @var bool  */
    private bool $isSocialLogin=false;

    /** @return int */
    public function getId(): int{return $this->id;}

    /** @param int $id */
    public function setId(int $id): void{$this->id = $id;}

    /** @return string */
    public function getEmail(): string{return $this->email;}

    /** @param string $email */
    public function setEmail(string $email): void{$this->email = $email;}

    /** @return string */
    public function getUsername(): string{return $this->username;}

    /** @param string $username */
    public function setUsername(string $username): void{$this->username = $username;}

    /** @return string|null */
    public function getPassword(): ?string{return $this->password;}

    /** @param string|null $password */
    public function setPassword(?string $password): void{$this->password = $password;}

    /** @return string|null */
    public function getAvatar(): ?string{return $this->avatar;}

    /** @param string|null $avatar */
    public function setAvatar(?string $avatar): void{$this->avatar = $avatar;}

    /** @return array */
    public function getMeta(): array{return $this->meta ?? [];}

    /**
     * @param string $metaId
     * @return mixed
     */
    public function getSingleMeta(string $metaId): mixed{return $this->meta[$metaId]??null;}

    /** @param array $meta */
    public function setMeta(array $meta): void{$this->meta = $meta;}

    /**
     * @param string $metaId
     * @param mixed $value
     */
    public function addMeta(string $metaId, mixed $value): void{$this->meta[$metaId] = $value;}

    /** @return int */
    public function getCreatedAt(): int{return $this->createdAt;}

    /** @return int|null */
    public function getUpdatedAt(): int|null{return $this->updatedAt;}

    /** @return bool */
    public function isSocialLogin(): bool{return $this->isSocialLogin;}

    /**
     * @param bool $isSocialLogin
     * @return void
     */
    public function setIsSocialLogin(bool $isSocialLogin): void{$this->isSocialLogin = $isSocialLogin;}

    /**
     * @return bool
     */
    public function isActive(
    ): bool
    {
        return array_key_exists('activationDate', $this->meta) && $this->meta['activationDate'] > time();
    }

    /**
     * @return void
     */
    public function setActive(
    ): void
    {
        $this->meta['activationDate'] = time();
    }
}