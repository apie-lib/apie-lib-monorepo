<?php
namespace Apie\LaravelApie\Wrappers\Cms;

use Stringable;

class DashboardContents implements Stringable
{
    public function __toString(): string
    {
        return view(config('apie.cms.dashboard_template'));
    }
}
