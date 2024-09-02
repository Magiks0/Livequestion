<?php

namespace App\DataFixtures;

use App\Entity\File;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

class FileFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'file_';

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 12; $i++)
        {
        $image = 'media_'.$i.'.jpg';
        // Chemin vers l'image dans le répertoire public/images
        $filePath = __DIR__ . '/../../public/images/' .$image;

        // Créez une instance de File
        $fileEntity = new File();

        // Assignez le fichier
        $fileEntity->setImageFile(new SymfonyFile($filePath));

        // Définir le nom du fichier pour le stockage (peut être généré ou extrait du nom original)
        $fileEntity->setName($image);
        $fileEntity->setOriginalName($image);
        $fileEntity->setMimeType(mime_content_type($filePath));
        $fileEntity->setSize(filesize($filePath));
        $this->addReference(self::REFERENCE_IDENTIFIER . $i, $fileEntity);

        // Persistez l'entité
        $manager->persist($fileEntity);
        }

        // Enregistrer les changements
        $manager->flush();
    }
}
