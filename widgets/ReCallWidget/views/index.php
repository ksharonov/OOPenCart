<?php
use yii\widgets\ActiveForm;

?>

<div class="recall-trigger">
    <div class="recall-trigger__inner" data-m-target="#recallModal" data-m-toggle="modal"></div>
</div>
<div class="popup" id="recallModal" data-modal style="display:none">
    <div class="popup__cover">
        <div class="popup__block popup__block_recall">
            <button class="popup__close popup__close_recall" data-m-target="#recallModal" data-m-dismiss="modal">
                Закрыть
            </button>
            <?php $form = ActiveForm::begin([
                'id' => 'recall-form',
                'action' => '/',
                'errorCssClass' => '',
                'options' => [
                    'class' => 'recall'
                ]
            ]) ?>
            <div class="row">
                <div class="col-md-48">
                    <h4 class="recall__title">
                        Заполните форму и мы перезвоним!
                    </h4>
                    <p class="recall__text">
                        Заполните форму прямо сейчас,
                        и мы перезвоним вам в ближайшее* время
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="recall__formblock">
                    <label class="recall__label">Ваше имя*</label>
                    <input type="text" name="name" placeholder="Иван Иванович" class="recall__input" required>
                </div>
                <div class="recall__formblock">
                    <label class="recall__label">Телефон*</label>
                    <input type="tel" name="phone" placeholder="+79999999999" class="recall__input" required>
                </div>
                <div class="recall__formblock">
                    <label class="recall__label">Email*</label>
                    <input type="email" name="email" placeholder="email@email.com" class="recall__input" required>
                </div>
                <div class="recall__formblock">
                    <label class="recall__label">Город</label>
                    <input type="text" name="city" placeholder="Уфа" class="recall__input">
                </div>
                <div class="recall__formblock">
                    <label class="recall__label">Комментарий</label>
                    <textarea class="recall__input" name="comment" rows="5" placeholder="Дополнительная информация"></textarea>
                </div>
                <?php //$form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::class,[
                    //'options' => [
                    //    'class' => 'recall__input',
                    //    'placeholder' => 'Введите код'
                    //]
                //])
                ?>
                
                <?php echo $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className());  
                ?>
                
                <div class="recall__formblock">
                    <div class="check check_login">
                        <input id="check-mail" type="checkbox" hidden name="check" class="check__input">
                        <label class="check__label" for="check-mail">
                            Нажимая на кнопку, я принимаю
                            условия пользовательского
                            соглашения и даю согласие
                            на обработку моих
                            персональных данных.
                        </label>
                    </div>
                </div>
                <div class="recall__formblock">
                    <input class="recall__submit" type="submit" value="Отправить">
                </div>
            </div>
            <div class="row">
                <div class="col-md-48">
                    <p class="recall__text">
                        * - Время работы операторов call-центра:
                        будние рабочие дни с 09:00 до 18:00 Уральского времени
                    </p>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>