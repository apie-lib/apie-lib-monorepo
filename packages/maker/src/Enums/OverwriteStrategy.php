<?php
namespace Apie\Maker\Enums;

enum OverwriteStrategy: string
{
    case Reset = 'Reset';
    case Merge = 'Merge';
    case Overwrite = 'Overwrite';
}
