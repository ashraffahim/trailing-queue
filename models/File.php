<?php

namespace app\models;

use app\components\StorageManager;

class File extends databaseObjects\File
{
    /**
     * Upload media file
     * @param string $source
     * @param string $extension
     * @throws exceptions\common\CannotUploadFileException
     */
    public function uploadMedia($source, $extension)
    {
        StorageManager::uploadMediaFile($source, $this->uuid, $extension);
    }

    /**
     * Delete media file
     * @throws exceptions\common\CannotDeleteFileException
     */
    public function unlinkMedia()
    {
        StorageManager::deleteMediaFile($this->uuid, pathinfo($this->name, PATHINFO_EXTENSION));
    }
}
