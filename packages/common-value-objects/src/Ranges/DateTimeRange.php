<?php
namespace Apie\CommonValueObjects;

use Apie\CommonValueObjects\Exceptions\RangeMismatchException;
use Apie\CompositeValueObjects\CompositeValueObject;
use Apie\Core\ValueObjects\ValueObjectInterface;
use Apie\DateValueObjects\DateWithTimezone;

class DateTimeRange implements ValueObjectInterface
{
    use CompositeValueObject;

    private DateWithTimezone $start;
    private DateWithTimezone $end;

    public function __construct(DateWithTimezone $start, DateWithTimezone $end)
    {
        $this->start = $start;
        $this->end = $end;
        $this->validateState();
    }

    private function validateState()
    {
        if ($this->start->toDate() > $this->end->toDate()) {
            throw new RangeMismatchException($this->start->toDate(), $this->end->toDate());
        }
    }
}
