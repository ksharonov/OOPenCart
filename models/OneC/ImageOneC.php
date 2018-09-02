<?php

namespace app\models\OneC;
use app\models\db\Client;
use app\models\db\ProductUnit;
use app\models\db\Unit;
use app\system\base\OneCLoader;
use app\models\db\OuterRel;
use app\helpers\ModelRelationHelper;
use app\models\db\Product;
use app\models\db\ProductToCategory;
use app\models\db\ProductCategory;
use app\models\db\StorageBalance;
use app\models\db\File;

/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 01.02.2018
 * Time: 18:02
 */

class ImageOneC extends OneCLoader
{
    public $source = 'load_image';

    public function addImageOneC () {

        echo 'Начало выгрузки изображений номенклатуры  ========> ';
        $data = $this->load();
        //dump($data);
        //exit();

        foreach ($data as $m) {
            foreach ($m as $image) {
                $findProduct = Product::findByGuid(strval($image['ТоварGUID']));
                $filename = $image['НаименованиеКартинки'].'.'.$image['РасширениеКартинки'];
                $check_file = File::findOne([
                    'relModelId' => $findProduct->id,
                    'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
                    'path' => '/files/uploads/sdEl/' . $findProduct->id . '/' . $filename,
                    'size' => $image['РазмерКартинки']
                ]);

                $absPath = './web/files/uploads/sdEl/'.$findProduct->id;
                //$absPath = $_SERVER['DOCUMENT_ROOT'] . '/files/uploads/sdEl/' . $findProduct->id;
                if (!is_dir($absPath)) {
                    mkdir($absPath, 0777, true);
                }
                $content = $image['base64'];
                $scontent = str_replace(array("\r", "\n"), "", $content);
                $scontent = str_replace('"', '', $scontent);
                $img = base64_decode($scontent);

                if (empty($check_file)) {
                    $newFile = new File();
                    $newFile->type = File::TYPE_IMAGE;
                    $newFile->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
                    $newFile->relModelId = $findProduct->id;
                    $newFile->path = '/files/uploads/sdEl/' . $findProduct->id . '/' . $filename;
                    $newFile->status = File::FILE_PUBLISHED;
                    $newFile->size = $image['РазмерКартинки'];
                    $newFile->save(false);
                    $success = file_put_contents($absPath . '/' . $filename, $img);
                }
            }
        }
        echo 'Выгрузка изображений номенклатуры завершена'.PHP_EOL;
    }

}