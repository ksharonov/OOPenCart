<div class="popup" id="smsSubmitBonus" data-modal style="display:none;">
    <div class="popup__cover">
        <div class="popup__block popup__block_apply">
            <button class="popup__close" data-m-target="#smsSubmitBonus" data-pjax="0"
                    data-m-dismiss="modal">Закрыть
            </button>
            <form class="apply" id="sms" method="POST" enctype="multipart/form-data">
                <div>
                    <h5 class="apply__spec _vacancy_title"></h5>
                    <h6 class="apply__title">Подтверждение СМС</h6>
                </div>
                <div class="apply__block">
                    <input type="text" name="code" placeholder="Код" class="apply__input">
                </div>
                <div class="apply__block">
                    <input type="button" value="Подтвердить" class="apply__submit btn _submit_sms_message">
                </div>
            </form>
        </div>
    </div>
</div>