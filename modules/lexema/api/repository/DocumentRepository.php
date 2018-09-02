<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 16.05.2018
 * Time: 11:49
 */

namespace app\modules\lexema\api\repository;


use app\modules\lexema\api\base\BaseRepository;

class DocumentRepository extends BaseRepository
{
	const TDOC_DGV = 'ДГВ';
	const TDOC_EZK = 'EZK';

	protected $url = [
		'model' => 'ESI_GetDocumentFiles',
	];

	public function findByVcodeAndTdoc($vcode, $tdoc)
	{
		$data = $this->source->request($this->url['model'] . "&Vcode={$vcode}" . "&Tdoc={$tdoc}");
		$this->data['index'] = json_decode($data, true);
		$this->foundData = 'index';
		return $this;
	}
}