<?php

namespace App\ApiePlayground\Types\Resources;

use Apie\CommonValueObjects\Email;
use Apie\CommonValueObjects\SafeHtml;
use Apie\Core\Attributes\FakeCount;
use Apie\Core\Lists\StringHashmap;
use Apie\Core\Lists\StringList;
use Apie\TextValueObjects\StrongPassword;
use App\ApiePlayground\Types\Identifiers\StringFieldsIdentifier;
use App\ApiePlayground\Types\Lists\SafeHtmlList;

#[FakeCount(25)]
class StringFields implements \Apie\Core\Entities\EntityInterface
{
    private StringFieldsIdentifier $id;

    public SafeHtml $safeHtml;

    public SafeHtmlList $safeHtmlList;

    public string $regularString;

    public ?string $nullableString;

    public Email $email;

    public StrongPassword $password;

    public StringList $stringList;

    public StringHashmap $stringHashmap;

    public ?StringList $nullableStringList;

    public ?StringHashmap $nullableStringHashmap;

    public function __construct()
    {
        $this->id = StringFieldsIdentifier::createRandom();
    }

    public function getId(): StringFieldsIdentifier
    {
        return $this->id;
    }
}
