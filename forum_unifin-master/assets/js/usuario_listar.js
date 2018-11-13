$('#desativarUsuario').on('show.bs.modal', function(e) {
    var usuLogin = $(e.relatedTarget).data('usu-login');

    $(e.currentTarget).find('input[name="usu_login_desativar"]').val(usuLogin);
});

$('#ativarUsuario').on('show.bs.modal', function(e) {
    var usuLogin = $(e.relatedTarget).data('usu-login');

    $(e.currentTarget).find('input[name="usu_login_ativar"]').val(usuLogin);
});