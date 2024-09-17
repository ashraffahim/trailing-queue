<?php
namespace app\models\forms;

use yii\base\Model;
use yii\web\UploadedFile;

class AdsUploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules()
    {
        return [
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg,png,jpeg,webp,gif,jfif,mp4,mov,wmv,avi,webm,mpeg-2', 'maxFiles' => 10],
        ];
    }
    
    public function upload()
    {
        $baseUrl = \Yii::getAlias('@app/web/data/ads/');

        if ($this->validate()) { 
            foreach ($this->files as $file) {
                $file->saveAs($baseUrl . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}
?>