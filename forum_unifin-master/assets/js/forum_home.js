$('#editarTema').on('show.bs.modal', function(e) {
    var temId = $(e.relatedTarget).data('tem-cod');
    var temDesc = $(e.relatedTarget).data('tem-desc');
    var catId = $(e.relatedTarget).data('cat-id');

    $(e.currentTarget).find('input[name="tem_codigo_edit"]').val(temId);
    $(e.currentTarget).find('input[name="tem_descricao_edit"]').val(temDesc);
    $(e.currentTarget).find('select[name="cat_codigo_edit"]').val(catId);
});

$('#desativarTema').on('show.bs.modal', function(e) {
    var temId = $(e.relatedTarget).data('tem-cod');

    $(e.currentTarget).find('input[name="tem_codigo_desativar"]').val(temId);
});

$('#ativarTema').on('show.bs.modal', function(e) {
    var temId = $(e.relatedTarget).data('tem-cod');

    $(e.currentTarget).find('input[name="tem_codigo_ativar"]').val(temId);
});
