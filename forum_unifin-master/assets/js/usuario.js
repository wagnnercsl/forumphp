$(document).ready(function() { 
    $('#fotoPerfil').change(changeFotoPerfil);

    $('#btnFotoPerfil').bind('click', () => {
        $('#fotoPerfil').click();
    });

});

changeFotoPerfil = () => {
    let split = $('#fotoPerfil').val().split('\\');
    $('#txtAvatar').val(split[split.length - 1]);
};