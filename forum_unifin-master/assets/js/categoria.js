$('#editarCategoria').on('show.bs.modal', function(e) {
    var catId = $(e.relatedTarget).data('cat-id');
    var catDesc = $(e.relatedTarget).data('cat-desc');

    $(e.currentTarget).find('input[name="cat_codigo_edit"]').val(catId);
    $(e.currentTarget).find('input[name="cat_descricao_edit"]').val(catDesc);
});

$('#desativarCategoria').on('show.bs.modal', function(e) {
    var catId = $(e.relatedTarget).data('cat-id');

    $(e.currentTarget).find('input[name="cat_codigo_desativar"]').val(catId);
});

$('#ativarCategoria').on('show.bs.modal', function(e) {
    var catId = $(e.relatedTarget).data('cat-id');

    $(e.currentTarget).find('input[name="cat_codigo_ativar"]').val(catId);
});