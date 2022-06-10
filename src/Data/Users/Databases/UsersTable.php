<?php

namespace CarloNicora\Minimalism\Services\Users\Data\Users\Databases;

use CarloNicora\Minimalism\Interfaces\Sql\Attributes\SqlFieldAttribute;
use CarloNicora\Minimalism\Interfaces\Sql\Attributes\SqlTableAttribute;
use CarloNicora\Minimalism\Interfaces\Sql\Enums\SqlFieldOption;
use CarloNicora\Minimalism\Interfaces\Sql\Enums\SqlFieldType;

#[SqlTableAttribute(name: 'users', databaseIdentifier: 'Users')]
enum UsersTable
{
    #[SqlFieldAttribute(fieldType: SqlFieldType::Integer, fieldOption: SqlFieldOption::AutoIncrement)]
    case userId;

    #[SqlFieldAttribute(fieldType: SqlFieldType::String)]
    case email;

    #[SqlFieldAttribute(fieldType: SqlFieldType::String)]
    case username;

    #[SqlFieldAttribute(fieldType: SqlFieldType::String)]
    case password;

    #[SqlFieldAttribute(fieldType: SqlFieldType::String)]
    case avatar;

    #[SqlFieldAttribute(fieldType: SqlFieldType::String)]
    case meta;

    #[SqlFieldAttribute(fieldType: SqlFieldType::String, fieldOption: SqlFieldOption::TimeCreate)]
    case createdAt;

    #[SqlFieldAttribute(fieldType: SqlFieldType::String, fieldOption: SqlFieldOption::TimeUpdate)]
    case updatedAt;
}