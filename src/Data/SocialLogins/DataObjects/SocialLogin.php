<?php
namespace CarloNicora\Minimalism\Services\Users\Data\SocialLogins\DataObjects;

use CarloNicora\Minimalism\Interfaces\Sql\Attributes\DbField;
use CarloNicora\Minimalism\Interfaces\Sql\Attributes\DbTable;
use CarloNicora\Minimalism\Interfaces\Sql\Enums\DbFieldType;
use CarloNicora\Minimalism\Interfaces\Sql\Interfaces\SqlDataObjectInterface;
use CarloNicora\Minimalism\Interfaces\Sql\Traits\SqlDataObjectTrait;
use CarloNicora\Minimalism\Services\Users\Data\SocialLogins\Databases\SocialLoginsTable;

#[DbTable(tableClass: SocialLoginsTable::class)]
class SocialLogin implements SqlDataObjectInterface
{
    use SqlDataObjectTrait;

    /** @var int  */
    #[DbField(field: SocialLoginsTable::socialLoginId)]
    private int $id;

    /** @var int */
    #[DbField]
    private int $userId;

    /** @var string  */
    #[DbField]
    private string $social;

    /** @var int */
    #[DbField(fieldType: DbFieldType::IntDateTime)]
    private int $createdAt;

    /** @return int */
    public function getId(): int{return $this->id;}

    /** @param int $id */
    public function setId(int $id): void{$this->id = $id;}

    /** @return int */
    public function getUserId(): int{return $this->userId;}

    /** @param int $userId */
    public function setUserId(int $userId): void{$this->userId = $userId;}

    /** @return string */
    public function getSocial(): string{return $this->social;}

    /** @param string $social */
    public function setSocial(string $social): void{$this->social = $social;}

    /** @return int */
    public function getCreatedAt(): int{return $this->createdAt;}
}