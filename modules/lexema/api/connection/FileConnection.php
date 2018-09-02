<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 17.05.2018
 * Time: 12:08
 */

namespace app\modules\lexema\api\connection;


use app\modules\lexema\api\base\IConnection;
use yii\base\Component;

class FileConnection extends Component implements IConnection
{
	protected $basePath = '/runtime/temp/';

	protected $extension = '.json';

	protected static $instance;

	public function init()
	{
		$this->basePath = \Yii::getAlias('@app') . $this->basePath;
		parent::init();
	}

	/**
	 * @return FileConnection
	 */
	public static function get()
	{
		if (self::$instance == null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param $url
	 * @return bool|string
	 */
	public function request($url)
	{
		if (is_string($url)) {
			$filename = $url;
		} else {
			$filename = $url['model'];
		}

		$result = file_get_contents($this->basePath . $filename . $this->extension);

		return $result;
	}

	/**
	 * @param $url
	 * @param null $data
	 * @return bool
	 */
	public function send( $url, $data = null )
	{
		return false;
	}
}