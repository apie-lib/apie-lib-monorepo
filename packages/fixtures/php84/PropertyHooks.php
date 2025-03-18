<?php
if (PHP_VERSION_ID >= 80400) {
    eval('
namespace Apie\Fixtures\Php84;

class PropertyHooks
{
    public string $name {
        set {
            if (strlen($value) === 0) {
                throw new \ValueError("Name must be non-empty");
            }
            $this->name = $value;
        }
    }

    public string $virtualSetter {
        set(string $value) {
            $this->name = ucfirst($value);
        }
    }

    public string $virtual {
        get {
            return "This is an example";
        }
    }

    public function __construct(string $name) {
        $this->name = $name;
    }
}');
}