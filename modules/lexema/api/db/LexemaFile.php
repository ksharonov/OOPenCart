<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 23.05.2018
 * Time: 12:39
 */

namespace app\modules\lexema\api\db;


use app\helpers\ModelRelationHelper;
use app\models\db\File;
use app\models\db\Order;
use app\modules\lexema\api\base\Mapper;
use app\system\db\ActiveRecord;

class LexemaFile extends File
{
	use Mapper;

	public $label = 'Файл';
	public $labelAttribute ='_fileName';

	public $_fileName;
	public $_fileType;
	public $_fileData;

	static $fileTypes = [
		'47061' => File::TYPE_CERTIFICATE,
		'47062' => File::TYPE_IMAGE,
	];

	public $basePath = '';
	public $dbBasePath = '';

	public $realDirPath;
	public $realFilePath;

	/** @var LexemaProduct */
	public $currentProduct;

	/** @var ActiveRecord */
	public $model;

	public $map = [
		'fname1' => '_fileName',
		'ftype' => 'setFileType',
		'ftypename' => null,
		'file1' => 'setFileData',
		'GlobalIdMaterial' => 'setProduct',
	];

	public function setOwner(ActiveRecord $owner) {
		// файлы заказа
		if ($owner instanceof Order) {
			$this->map = [
				'vcode' => null,
				'fname1' => '_filename',
				'file1' => 'setFileData',
				'text1' => null,
				'prcode' => null,
				'typdoc' => null,
			];

			$this->_fileType = File::TYPE_DOCUMENT;
			$this->loadModel($owner);
		}


	}

	public function init()
	{
		$this->basePath = \Yii::getAlias('@app/web/files/import/products/');
		$this->dbBasePath = "files/import/products/";
		parent::init();
	}

	public function rules()
	{
		return [
			[['path'], 'unique'],
			[['relModel', 'relModelId', 'path'], 'required']
		];
	}

	public function setProduct($key, $value)
	{

	}

	public function setPaths()
	{

		if ($this->model) {
			$this->realDirPath = $this->basePath . $this->model->id . '/';
			$this->realFilePath = $this->realDirPath . $this->_fileName;
			$this->path = $this->dbBasePath . $this->model->id . '/' . $this->_fileName;
		} else 	if ($this->currentProduct) {
			// на всякий случай проверка на наличие vcode
			if (isset($this->currentProduct->backCode)) {
				$vcode = $this->currentProduct->id;
				$this->realDirPath = $this->basePath . $vcode . '/';
				$this->realFilePath = $this->realDirPath . $this->_fileName;
				$this->path = $this->dbBasePath . $vcode . '/' . $this->_fileName;
			} else {
				// TODO если нет vcode, то я хз
				throw new \Exception('У переданного товара нет VCODE. GUID: ' . $this->currentProduct->guid);
			}
		} else {
			// TODO тут настройка под отдельный импорт файлов
			throw new \Exception('Не передан товар для импорта файлов.');
		}

		if (!is_dir($this->realDirPath)) {
			mkdir($this->realDirPath, 0777, true);
		}

		return true;
	}

	public function setFileType($key, $value)
	{
		$this->type = self::$fileTypes[$value];
	}

	public function setFileData($key, $value)
	{
		if (!is_null($value)) {
			$this->_fileData = base64_decode($value);
		}
	}

	public function writeToFile()
	{
		$result = file_put_contents($this->realFilePath, $this->_fileData);

		return $result;
	}

	public function loadModel(ActiveRecord $model, $attributes)
	{
		$this->model = $model;
		$modelIndex = array_search($model->relModel, ModelRelationHelper::$model);

		if ($modelIndex === false) {
			return false;
		}

		$modelName = lcfirst($modelIndex);

		$this->basePath = \Yii::getAlias("@app/web/files/import/{$modelName}/");
		$this->dbBasePath = "/files/import/{$modelName}/";
		$this->_fileName = $attributes[0] ?? null;
		$this->_fileData = base64_decode($attributes[1]) ?? null;
		$this->_fileType = $attributes[2] ?? null;
	}

	public function beforeValidate()
	{
		if ($this->model) {
			if (is_null($this->_fileType) || !$this->_fileData || !$this->_fileName) {
				return false;
			}

			if ($this->setPaths()) {
				$this->relModel = $this->model->relModel;
				$this->relModelId = $this->model->id;
				$this->status = File::FILE_PUBLISHED;
				$this->title = $this->_fileName;

				$result = $this->writeToFile();

				if ($result) {
					return true;
				} else {
					throw new \Exception('Не удалось сохранить файл ' . $this->realFilePath);
				}
			}
		}

		/**				  ------------------------- 			 **/

		$this->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
		$this->relModelId = $this->currentProduct->id;
		$this->status = File::FILE_PUBLISHED;
		$this->title = $this->_fileName;

		if (!$this->_fileName || !$this->_fileData || $this->_fileType) {
			return false;
		}

		if ($this->setPaths()) {
			//if (file_exists($this->realFilePath)) {
			// TODO действия если такой файл уже есть
			// пока полная перезапись
			//return true;
			//} else {
			$result = $this->writeToFile();

			if ($result) {
				return true;
			} else {
				// TODO временно
				dump($this);
				throw new \Exception('Почему-то не удалось сохранить файл. GUID: '. $this->currentProduct->guid);
			}
			//}

		} else {
			// TODO временно
			throw new \Exception('Что то пошло не так с путями или папками. GUID ' . $this->currentProduct->guid);
		}

		return parent::beforeValidate(); // TODO: Change the autogenerated stub
	}
}