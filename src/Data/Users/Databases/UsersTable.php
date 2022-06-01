<?php

namespace CarloNicora\Minimalism\Services\Users\Data\Users\Databases;

use CarloNicora\Minimalism\Services\MySQL\Data\SqlField;
use CarloNicora\Minimalism\Services\MySQL\Data\SqlTable;
use CarloNicora\Minimalism\Services\MySQL\Enums\FieldOption;
use CarloNicora\Minimalism\Services\MySQL\Enums\FieldType;

#[SqlTable(name: 'users', databaseIdentifier: 'Users')]
enum UsersTable
{
    #[SqlField(fieldType: FieldType::Integer, fieldOption: FieldOption::AutoIncrement)]
    case userId;

    #[SqlField]
    case email;

    #[SqlField]
    case username;

    #[SqlField]
    case password;

    #[SqlField]
    case avatar;

    #[SqlField]
    case meta;

    #[SqlField(fieldOption: FieldOption::TimeCreate)]
    case createdAt;

    #[SqlField]
    case activatedAt;

    #[SqlField(fieldOption: FieldOption::TimeUpdate)]
    case updatedAt;
}