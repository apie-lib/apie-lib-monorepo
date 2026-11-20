<?php
namespace Apie\Faker\Support;

use ZipStream\CompressionMethod;
use ZipStream\ZipStream;

/**
 * Creates an OpenDocument package (odt, ods, ...) from a content.xml.
 */
final class OpenDocumentWriter
{
    public const XML = '<?xml version="1.0" encoding="UTF-8"?>';
    public const NAMESPACES = 'xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"'
        . ' xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0"'
        . ' xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0"'
        . ' xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0"'
        . ' xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0"'
        . ' xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0"';

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES | ENT_SUBSTITUTE);
    }

    /** @return resource */
    public static function write(string $mimeType, string $originalFilename, string $title, string $contentXml): mixed
    {
        $resource = tmpfile();
        $zipstream = new ZipStream(
            outputStream: $resource,
            sendHttpHeaders: false,
            outputName: $originalFilename,
            contentType: $mimeType,
            defaultEnableZeroHeader: false,
            enableZip64: false,
        );
        // the mimetype file must be the first entry and can not be compressed.
        $zipstream->addFile('mimetype', $mimeType, compressionMethod: CompressionMethod::STORE);
        $zipstream->addFile('META-INF/manifest.xml', self::manifest($mimeType));
        $zipstream->addFile('meta.xml', self::meta($title));
        $zipstream->addFile('content.xml', $contentXml);
        $zipstream->finish();

        return $resource;
    }

    private static function manifest(string $mimeType): string
    {
        return self::XML
            . '<manifest:manifest xmlns:manifest="urn:oasis:names:tc:opendocument:xmlns:manifest:1.0" manifest:version="1.2">'
            . '<manifest:file-entry manifest:full-path="/" manifest:version="1.2" manifest:media-type="' . $mimeType . '"/>'
            . '<manifest:file-entry manifest:full-path="content.xml" manifest:media-type="text/xml"/>'
            . '<manifest:file-entry manifest:full-path="meta.xml" manifest:media-type="text/xml"/>'
            . '</manifest:manifest>';
    }

    private static function meta(string $title): string
    {
        return self::XML
            . '<office:document-meta xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0"'
            . ' xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0"'
            . ' xmlns:dc="http://purl.org/dc/elements/1.1/" office:version="1.2"><office:meta>'
            . '<dc:title>' . self::escape($title) . '</dc:title>'
            . '<meta:initial-creator>Apie faker</meta:initial-creator>'
            . '<meta:creation-date>' . gmdate('Y-m-d\TH:i:s') . '</meta:creation-date>'
            . '</office:meta></office:document-meta>';
    }
}
