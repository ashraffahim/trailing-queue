<?php

namespace app\components;

use app\models\exceptions\common\CannotDeleteFileException;
use app\models\exceptions\common\CannotUploadFileException;
use app\models\exceptions\common\FileDoesNotExistException;
use yii\helpers\FileHelper;

class StorageManager {
    private const STORAGE_ROOT = __DIR__ . '/../storage/';

    public const READER_BUFFER_LENGTH = 512;

    /**
     * Upload file to storage
     * @param string $source
     * @param string $destination
     * @throws CannotUploadFileException
     */
    public static function uploadFile(string $source, string $destination) {
        $file = fopen(self::STORAGE_ROOT . $destination, 'w');

        if ($file === false) throw new CannotUploadFileException();

        $content = fopen($source, 'r');

        if ($content === false) throw new CannotUploadFileException();
        
        while (!feof($content)) {
            if (fwrite($file, fread($content, self::READER_BUFFER_LENGTH)) === false) throw new CannotUploadFileException();
        }

        if (fclose($file) === false) throw new CannotUploadFileException();
    }

    /**
     * Upload content as file to storage
     * @param string $content
     * @param string $destination
     * @throws CannotUploadFileException
     */
    public static function uploadFileContent($content, string $destination) {
        $file = fopen(self::STORAGE_ROOT . $destination, 'w');

        if ($file === false) throw new CannotUploadFileException();
        
        if (fwrite($file, $content) === false) throw new CannotUploadFileException();

        if (fclose($file) === false) throw new CannotUploadFileException();
    }

    /**
     * Get file resource
     * @param string $name
     * @return bool
     */
    public static function fileExists(string $name) {
        return file_exists(self::STORAGE_ROOT . $name);
    }

    /**
     * Get file resource
     * @param string $name
     * @return resource
     * @throws FileDoesNotExistException
     */
    public static function getFileResource(string $name) {
        $file = fopen(self::STORAGE_ROOT . $name, 'r');

        if ($file === false) {
            throw new FileDoesNotExistException();
        }

        return $file;
    }

    /**
     * Close resource
     * @param resource $stream
     * @return resource|false
     */
    public static function closeFile($stream) {
        return fclose($stream);
    }

    /**
     * Get file content
     * @param string $name
     * @return string
     * @throws FileDoesNotExistException
     */
    public static function getFileContent(string $name)
    {
        $file = self::getFileResource($name);
        
        $content = '';
        
        while (!feof($file)) {
            $content .= fread($file, self::READER_BUFFER_LENGTH);
        }

        self::closeFile($file);

        return $content;
    }

    /**
     * Create directories if does not exist
     * @param string $paths
     */
    private static function createDirsIfDoesNotExist(string $path) {
        if (!is_dir($path)) {
            FileHelper::createDirectory(self::STORAGE_ROOT . $path, 0777, true);
        }
    }

    /**
     * Upload article html file to storage
     * @param string $uuid
     * @throws CannotUploadFileException
     */
    public static function uploadArticleContent(string $content, string $uuid) {
        $path = self::getArticleContentFilePath($uuid);

        self::uploadFileContent($content, $path);
    }

    /**
     * Upload media file to storage
     * @param string $source
     * @param string $uuid
     * @param string $extension
     * @throws CannotUploadFileException
     */
    public static function uploadMediaFile(string $source, string $uuid, string $extension) {
        $path = self::getMediaFilePath($uuid, $extension);

        self::uploadFile($source, $path);
    }

    /**
     * Get article html file content
     * @param string $uuid
     * @return string
     * @throws FileDoesNotExistException
     */
    public static function getArticleContent(string $uuid) {
        $path = self::getArticleContentFilePath($uuid);

        return self::getFileContent($path);
    }

    /**
     * Delete media file
     * @param string $uuid
     * @param string $extension
     * @throws CannotDeleteFileException
     */
    public static function deleteMediaFile(string $uuid, string $extension) {
        $path = self::getMediaFilePath($uuid, $extension);

        if (self::fileExists($path)) {
            if (!unlink($path)) {

                \Yii::info([
                    'path' => $path
                ], 'media_file');

                throw new CannotDeleteFileException();
            }
        }
    }
}

?>