<?php
namespace ProjetNormandie\UserBundle\Service;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AvatarManager
{
    private string $prefix = 'user/';

    private array $extensions = array(
        'png' => 'image/png',
        'jpg' => 'image/jpeg'
    );

    private FilesystemOperator $pnUserStorage;


    public function __construct(FilesystemOperator $pnUserStorage)
    {
        $this->pnUserStorage = $pnUserStorage;
    }

    /**
     * @throws FilesystemException
     */
    public function write(string $filename, string $contents)
    {
         $this->pnUserStorage->write($this->prefix . $filename, $contents);
    }


    /**
     * @throws FilesystemException
     */
    public function read(string $filename): StreamedResponse
    {
        $path = $this->prefix . $filename;
        if (!$this->pnUserStorage->fileExists($path)) {
            $path = $this->prefix . 'default.png';
        }

        $stream = $this->pnUserStorage->readStream($path);
        return new StreamedResponse(function () use ($stream) {
            fpassthru($stream);
            exit();
        }, 200, ['Content-Type' => $this->getMimeType($path)]);
    }


    public function getAllowedMimeType(): array
    {
        return array_values($this->extensions);
    }

    public function getExtension($mimeType): string
    {
        $types = array_flip($this->extensions);
        return $types[$mimeType] ?? 'png';
    }

    private function getMimeType(string $file): string
    {
        $infos = pathinfo($file);
        return $this->extensions[$infos['extension']] ?? 'image/png';
    }
}
