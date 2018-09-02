var adminProductCategory = new AdminCategoryClass();


/** Установка категории */
function AdminCategoryClass() {

    this.set = function (relationId, categoryId, relationModel, relationIdName) {
        var data = {
            relationId: relationId,
            categoryId: categoryId,
            relationModel: relationModel,
            relationIdName: relationIdName
        };

        console.log(data);

        this.request({
            data: data,
            type: 'set'
        });

    };

    this.delete = function (relationId, categoryId, relationModel, relationIdName) {
        var data = {
            relationId: relationId,
            categoryId: categoryId,
            relationModel: relationModel,
            relationIdName: relationIdName
        };

        this.request({
            data: data,
            type: 'delete'
        });
    };

    this.request = function (obj) {
        var self = this;
        $.post(
            '/admin/api/product-categories/' + obj.type,
            obj.data,
            function (success) {
                self.success();
            }
        );
    };

    this.success = function () {
        $.pjax.reload({container: '#categories'});
        $('#addCategoryModal').modal('hide');
        $('#addCategoryModal input').val("");
    };
}