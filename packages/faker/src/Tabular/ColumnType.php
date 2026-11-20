<?php
namespace Apie\Faker\Tabular;

enum ColumnType: int
{
    case Text = 0;
    case Integer = 1;
    case Decimal = 2;
    case Date = 3;
}
