<?php

use yii\db\Migration;

/**
 * Class m180426_044317_insert_block_footer_social_links
 */
class m180426_044317_insert_block_footer_social_links extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('block', [
            'title' => 'Описание  поля "Наименование" организации в личном кабинете',
            'blockKey' => 'FOOTER.SOCIAL.LINKS',
            'blockValue' => 'Мы в соцсетях:<a href="#" target="_blank" class="footer__soc footer__soc_vk"></a><a href="#" target="_blank" class="footer__soc footer__soc_facebook"></a>',
            'type' => \app\models\db\Block::TYPE_RAW
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180426_044317_insert_block_footer_social_links cannot be reverted.\n";

        return false;
    }
    */
}
