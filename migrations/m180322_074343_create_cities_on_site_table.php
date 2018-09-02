<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cities_on_site`.
 */
class m180322_074343_create_cities_on_site_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cities_on_site', [
            'id' => $this->primaryKey(),
            'city_id' => $this->integer(11)
        ]);

        $anotherCity = new \app\models\db\City();
        $anotherCity->title = "Другой город";
        $anotherCity->countryId = 0;
        $anotherCity->regionId = 0;
        $anotherCity->save();

        $this->insert('cities_on_site', [
            'city_id' => $anotherCity->id,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cities_on_site');
    }
}
