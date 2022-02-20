<?php
namespace CarloNicora\Minimalism\Services\Users\Data\SocialLogins\Databases;

use CarloNicora\Minimalism\Services\MySQL\Data\SqlField;
use CarloNicora\Minimalism\Services\MySQL\Data\SqlTable;
use CarloNicora\Minimalism\Services\MySQL\Enums\FieldOption;
use CarloNicora\Minimalism\Services\MySQL\Enums\FieldType;

#[SqlTable(name: 'socialLogins', databaseIdentifier: 'Users')]
enum SocialLoginsTable
{
    #[SqlField(fieldType: FieldType::Integer,fieldOption: FieldOption::AutoIncrement)]
    case socialLoginId;

    #[SqlField(fieldType: FieldType::Integer)]
    case userId;

    #[SqlField]
    case social;

    #[SqlField(fieldOption: FieldOption::TimeCreate)]
    case createdAt;
}