<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 22.05.2018
 * Time: 9:30
 */

namespace app\modules\lexema\api\db;


use app\models\db\File;
use app\models\db\Manufacturer;
use app\modules\lexema\api\base\Mapper;

class LexemaManufacturer extends Manufacturer
{
	use Mapper;

	public $label = 'Производитель';
	public $labelAttribute = 'title';

	public $fileData;

	public $map = [
		'GlobalBrendId' => 'guid',
		'ParentGlobalId' => null,
		'BrandName' => 'setTitle',
		'file1' => 'setFile',
	];

	public function setFile($key, $value)
	{
		if (!is_null($value)) {
			$this->fileData = $value;
		}
	}

	public function setTitle($key, $value)
	{
		$this->title = $value;

		$slug = str_replace(" ", "_", $value);
		$slug = mb_ereg_replace("[^А-Яа-яA-Za-z0-9_\.\-]","", $slug);
		$this->slug = $slug;
	}

	public function afterSave( $insert, $changedAttributes )
	{
		if ($this->fileData) {
			$fileName = trim(preg_replace("/[^a-zA-Zа-яА-Я0-9]/u", '', $this->title)) . '.jpg';

			$attributes = [$fileName, $this->fileData, File::TYPE_IMAGE];

			$dbFile = new LexemaFile();
			$dbFile->loadModel($this, $attributes);
			$dbFile->save();
		}

		$this->output($insert, $changedAttributes);

		parent::afterSave($insert, $changedAttributes);
	}
}