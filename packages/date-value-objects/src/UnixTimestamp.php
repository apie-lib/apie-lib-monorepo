<?php
namespace Apie\DateValueObjects;

use Apie\DateValueObjects\Concerns\CanCreateInstanceFromDateTimeObject;
use Apie\DateValueObjects\Concerns\CanHaveDayIntervals;
use Apie\DateValueObjects\Concerns\CanHaveMonthIntervals;
use Apie\DateValueObjects\Concerns\CanHaveTimeIntervals;
use Apie\DateValueObjects\Concerns\CanHaveYearIntervals;
use Apie\DateValueObjects\Concerns\IsDateValueObject;
use Apie\DateValueObjects\Interfaces\WorksWithDays;
use Apie\DateValueObjects\Interfaces\WorksWithMonths;
use Apie\DateValueObjects\Interfaces\WorksWithTimeIntervals;
use Apie\DateValueObjects\Interfaces\WorksWithYears;

/**
 * Contains a Unix timestamp.
 *
 * Example '1654579007'
 */
final class UnixTimestamp implements WorksWithDays, WorksWithMonths, WorksWithYears, WorksWithTimeIntervals
{
    use CanCreateInstanceFromDateTimeObject;
    use CanHaveDayIntervals;
    use CanHaveMonthIntervals;
    use CanHaveTimeIntervals;
    use CanHaveYearIntervals;
    use IsDateValueObject;

    public static function getDateFormat(): string
    {
        return 'U';
    }

    protected function isStrictFormat(): bool
    {
        return true;
    }
}
