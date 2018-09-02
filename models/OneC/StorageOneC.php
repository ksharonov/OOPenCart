<?php
/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 02.02.2018
 * Time: 12:11
 */

namespace app\models\OneC;
use app\models\db\OuterRel;
use app\helpers\ModelRelationHelper;
use app\models\db\Storage;


use app\system\base\OneCLoader;

class StorageOneC extends OneCLoader
{
    public $source = 'storage';

    public function addStorageOneC ()
    {
        echo 'Начало выгрузки складов ========> ';
        $data = $this->load();
        //print_r($data);
        foreach ($data as $count_array) {
            foreach ($count_array as $storage) {
                $guid_storage = OuterRel::findOne([
                    'guid' =>  $storage['СкладGUID'],
                    'relModel' => ModelRelationHelper::REL_MODEL_STORAGE,
                ]);

                if (empty($guid_storage)){
                    $new_storage = new Storage();
                    $new_storage->title = $storage['НаименованиеСклада'];
                    $new_storage->type = 0;
                    $new_storage->save(false);

                    $new_outer_rel = new OuterRel();
                    $new_outer_rel->guid = $storage['СкладGUID'];
                    $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_STORAGE;
                    $new_outer_rel->relModelId = $new_storage->id;
                    $new_outer_rel->save(false);
                } else {
                    $edit_storage = Storage::findOne([
                        'id' => $guid_storage->relModelId,
                    ]);
                    $edit_storage->title = $storage['НаименованиеСклада'];
                    $edit_storage->save(false);
                }
            }
        }
        echo 'Выгрузка складов завершена'.PHP_EOL;
        /*
        $data = $this->loadStorage();

        foreach ($data as $storage){
            $guid_storage = OuterRel::findOne([
                'guid' =>  $storage['id'],
                'relModel' => ModelRelationHelper::REL_MODEL_STORAGE,
            ]);

            if (empty($guid_storage)){
                $new_storage = new Storage();
                $new_storage->title = $storage['Наименование'];
                $new_storage->save(false);

                $new_outer_rel = new OuterRel();
                $new_outer_rel->guid = $storage['id'];
                $new_outer_rel->relModel = ModelRelationHelper::REL_MODEL_STORAGE;
                $new_outer_rel->relModelId = $new_storage->id;
                $new_outer_rel->save(false);
            }
        }*/
    }

    public function loadStorage() {
        $storages = [];
        foreach ($this->file->ПакетПредложений->Склады->Склад as $storage){
            ++$this->count;
            $storages[$this->count]['id'] = $storage->Ид;
            $storages[$this->count]['Наименование'] = $storage->Наименование;
            $storages[$this->count]['Адрес'] = $storage->Адрес->Представление;
        }
        return $storages;
    }
}