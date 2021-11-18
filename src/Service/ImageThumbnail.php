<?php

namespace App\Service;

use Imagick;
use CRUDlex\Entity;
use CRUDlex\Service;
use League\Flysystem\Filesystem;
use League\Flysystem\FileNotFoundException;
use ImagickException;
use phpDocumentor\Reflection\Types\Object_;

/**
 * Class ImageThumbnail
 * @package App\Service
 */
class ImageThumbnail
{
    /**
     * @param Entity $entity
     * @param Service $crud
     * @param Filesystem $fs
     * @param bool $overwriteExisting
     * @throws FileNotFoundException
     * @throws ImagickException
     */
    public function createThumbnailIfNotExists(
        Entity $entity,
        Service $crud,
        Filesystem $fs,
        $overwriteExisting = false
    ) {
        $thumbnailPath = $this->getThumbnailPath($entity);
        if ($entity->get('filename') === null  ||
            !$overwriteExisting &&
            $entity->get('thumbnail') !== null
        ) {
            return;
        }

        $path = $this->getImagePath($entity);
        $image = $fs->read($path);
        $thumbnail = $this->thumbnailFromImage($image);
        $entity->set('thumbnail', 'thumb_' . $entity->get('filename'));
        $fs->put($thumbnailPath, $thumbnail);
        $media = $crud->getData('media');
        $media->update($entity);
    }

    /**
     * @param object $entity
     * @return string
     */
    public function getImagePath(object $entity): string
    {
        return $entity->getDefinition()->getField('filename', 'path') . '/' .
            $entity->getDefinition()->getTable() . '/' .
            $entity->get('id') . '/' .
            'filename/' .
            $entity->get('filename');
    }

    /**
     * @param object $entity
     * @return string
     */
    public function getThumbnailPath(object $entity): string
    {
        return $entity->getDefinition()->getField('thumbnail', 'path') . '/' .
            $entity->getDefinition()->getTable() . '/' .
            $entity->get('id') . '/' .
            'thumbnail/' .
            'thumb_' . $entity->get('filename');
    }

    /**
     * @param string $imageString
     * @return string
     * @throws ImagickException
     */
    private function thumbnailFromImage(string $imageString): string
    {
        $img = new Imagick();
        $img->readImageBlob($imageString);
        $img->thumbnailImage(128, 128, true);

        return $img->getImageBlob();
    }
}

