<?php
namespace CarloNicora\Minimalism\Services\Users\Data\SocialLogins\Databases;

use CarloNicora\Minimalism\Interfaces\Sql\Attributes\SqlFieldAttribute;
use CarloNicora\Minimalism\Interfaces\Sql\Attributes\SqlTableAttribute;
use CarloNicora\Minimalism\Interfaces\Sql\Enums\SqlFieldOption;
use CarloNicora\Minimalism\Interfaces\Sql\Enums\SqlFieldType;

#[SqlTableAttribute(name: 'socialLogins', databaseIdentifier: 'Users')]
enum SocialLoginsTable
{
    #[SqlFieldAttribute(fieldType: SqlFieldType::Integer,fieldOption: SqlFieldOption::AutoIncrement)]
    case socialLoginId;

    #[SqlFieldAttribute(fieldType: SqlFieldType::Integer)]
    case userId;

    #[SqlFieldAttribute(fieldType: SqlFieldType::String)]
    case social;

    #[SqlFieldAttribute(fieldType: SqlFieldType::String, fieldOption: SqlFieldOption::TimeCreate)]
    case createdAt;
}