<?php

namespace App\Services;

use App\Entity\Pictures;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploaderService
{
    public function __construct(
        private $uploadsFolder,
        private $uploadsFolderPublic,
        private SluggerInterface $slugger,
        private Filesystem $filesystem,
    ) {
    }

    public function upload(UploadedFile $file, Pictures $oldPicturePath = null): Pictures
    {
        $folder = $this->uploadsFolder;
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
            if ($oldPicturePath) {
                $this->filesystem->remove($folder . '/' . pathinfo($oldPicturePath->getPath(), PATHINFO_BASENAME));
            }
            $uploadPicture = new Pictures();
            $uploadPicture->setPath($fileName);

            return $uploadPicture;
        } catch (FileException $e) {
            throw new \RuntimeException('Une erreur est survenue lors du téléchargement du fichier : ' . $e->getMessage());
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->uploadsFolderPublic;
    }
}
