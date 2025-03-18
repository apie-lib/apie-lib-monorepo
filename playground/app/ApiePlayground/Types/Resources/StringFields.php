<?php

namespace App\ApiePlayground\Types\Resources;

use Apie\CommonValueObjects\Email;
use Apie\CommonValueObjects\SafeHtml;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Lists\StringHashmap;
use Apie\Core\Lists\StringList;
use Apie\Core\Lists\StringSet;
use Apie\TextValueObjects\StrongPassword;
use App\ApiePlayground\Types\Identifiers\StringFieldsIdentifier;
use App\ApiePlayground\Types\Lists\SafeHtmlList;
use App\ApiePlayground\Types\Lists\SafeHtmlSet;

#[FakeCount(25)]
class StringFields implements \Apie\Core\Entities\EntityInterface
{
    private StringFieldsIdentifier $id;

    public SafeHtml $safeHtml;

    public SafeHtmlList $safeHtmlList;

    public SafeHtmlSet $safeHtmlSet;

    public string $regularString;

    public ?string $nullableString;

    public Email $email;

    public StrongPassword $password;

    public StringList $stringList;

    public StringHashmap $stringHashmap;

    public StringSet $stringSet;

    public ?StringList $nullableStringList;

    public ?StringHashmap $nullableStringHashmap;

    public ?StringSet $nullableStringSet;

    public function __construct()
    {
        $this->id = StringFieldsIdentifier::createRandom();
    }

    public function getId(): StringFieldsIdentifier
    {
        return $this->id;
    }
}
