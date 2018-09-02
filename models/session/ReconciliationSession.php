<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 03.04.2018
 * Time: 12:13
 */

namespace app\models\session;


use app\system\db\ActiveRecordSession;

/**
 * Class ReconciliationSession
 * @property string $clientGuid
 * @property string $from
 * @property string $to
 * @package app\models\session
 */
class ReconciliationSession extends ActiveRecordSession
{
    /** @var array */
    public $acts = null;

    /** @var array */
    public $await =null;

    public function rules()
    {
        return [
            [['from', 'to', 'clientGuid'], 'string'],
            [['from', 'to'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'from' => 'Дата начала',
            'to' => 'Дата окончания'
        ];
    }

    /** Добавляет акт(по периоду) к массиву запросов
     * @param $clientId
     * @param $data
     * @return bool
     */
    public function addAct($clientId, $data)
    {
        $date = $data['from'] . "-" . $data['to'];
        //$date = $this->from . "-" . $this->to;
        if (!isset($this->acts[$clientId])) {
            $this->acts[$clientId][] = $date;
            return true;
        } else {
            if (in_array($date, $this->acts[$clientId])) {
                return false;
            } else {
                $this->acts[$clientId][] = $date;
                return true;
            }
        }
    }

    /** Возвращает массив текущих запросов на акты (идентифицируются по периоду)
     *  (1.1.2018-31.3.2018)
     * @param $clientId
     * @return array|null
     */
    public function getActs( $clientId)
    {
        if (isset($this->acts[$clientId])) {
            return $this->acts[$clientId];
        } else return null;
    }

    /** Удаляет из массива запрос акта по указанному периоду
     * @param $clientId
     * @param $period
     * @return bool
     */
    public function deleteAct( $clientId, $period)
    {
        if (isset($this->acts[$clientId])) {
            $index = array_search($period, $this->acts[$clientId]);
            if (isset($index)) {
                unset($this->acts[$clientId][$index]);
                if (!$this->acts[$clientId]) {
                    unset($this->acts[$clientId]);
                } else {
                    sort($this->acts[$clientId]);
                }

                return true;
            }
        }

        return false;
    }

    /** Добавление непросмотренного акта
     * @param $clientId
     * @param $param
     * @return bool
     */
    public function addAwait($clientId, $param)
    {
        if (!isset($this->await[$clientId])) {
            $this->await[$clientId][] = $param;
            return true;
        } else {
            if (in_array($param, $this->await[$clientId])) {
                return false;
            } else {
                $this->await[$clientId][] = $param;
                return true;
            }
        }
    }

    /**
     * @param $clientId
     * @param $param
     * @return bool
     */
    public function deleteAwait($clientId, $param)
    {
        if (isset($this->await[$clientId])) {
            $index = array_search($param, $this->await[$clientId]);
            if (isset($index)) {
                unset($this->await[$clientId][$index]);
                if (!$this->await[$clientId]) {
                    unset($this->await[$clientId]);
                } else {
                    sort($this->await[$clientId]);
                }

                return true;
            }
        }
        return false;
    }

    /** Удаление из массива непросмотренных актов все элементы
     * @param $clientId
     */
    public function deleteAwaitAll($clientId)
    {
        unset($this->await[$clientId]);
    }

    /** Есть ли непросмотренные акты
     * @param $clientId
     * @return bool
     */
    public function hasAwait($clientId)
    {
        if (isset($this->await[$clientId])) {
            return true;
        } else {
            return false;
        }
    }
}