<?php

namespace App\ApiePlayground\Types\Resources;

use Apie\Core\Attributes\AllowMultipart;
use Apie\Core\Attributes\FakeMethod;
use Apie\Core\FileStorage\ImageFile;
use Apie\Core\FileStorage\StoredFile;
use Apie\Core\Lists\UploadedFileList;
use App\ApiePlayground\Types\Identifiers\UploadFileFieldsIdentifier;
use Faker\Generator;
use Psr\Http\Message\UploadedFileInterface;

#[AllowMultipart]
#[FakeMethod('createRandom')]
class UploadFileFields implements \Apie\Core\Entities\EntityInterface
{
    private UploadFileFieldsIdentifier $id;

    public UploadedFileInterface $interfaceFile;

    public StoredFile $storedFile;

    //public ImageFile $imageFile;

    public UploadedFileList $fileList;

    public function __construct(?UploadFileFieldsIdentifier $id = null)
    {
        $this->id = $id ?? UploadFileFieldsIdentifier::createRandom();
    }

    public function getId(): UploadFileFieldsIdentifier
    {
        return $this->id;
    }

    public static function createRandom(Generator $faker)
    {
        $res = new self();
        $file = $faker->fakeClass(StoredFile::class);
        $res->interfaceFile = $file;
        $res->storedFile = $file;
        $res->fileList = new UploadedFileList([$file]);
        return $res;
    }
}
