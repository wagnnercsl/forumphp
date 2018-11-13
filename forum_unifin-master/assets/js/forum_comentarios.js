$('#desativarComentario').on('show.bs.modal', function(e) {
    var comId = $(e.relatedTarget).data('com-cod');

    $(e.currentTarget).find('input[name="com_codigo_desativar"]').val(comId);
});