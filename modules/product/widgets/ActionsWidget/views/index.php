<table class="table">
    <tbody>
    <tr>
        <td colspan="2">
            <input type="text" class="form-control" data-model="count" data-product-id="<?= $model->id ?>"
                   placeholder="Количество">
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">
            <button class="btn btn-primary"
                    onclick="cart.add(<?= $model->id ?>)">
                В корзину
            </button>
            <button class="btn btn-primary" onclick="compare.add(<?= $model->id ?>)">
                В сравнение
            </button>
        </td>
    </tr>
    </tbody>
</table>