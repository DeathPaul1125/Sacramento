$(document).on('click', '.widgetTextBtn button', function(){
    let input = $(this).closest('.widgetTextBtn').find('input');
    let value = $(input).val();
    let jsfile = $(input).attr('jsfile');
    let action = $(input).attr('action');

    if (action === '' || value === '') {
        return;
    }

    if (jsfile === '') {
        jsfile = window.location.href;
    }


    $.ajax({
        method: 'POST',
        url: jsfile,
        data: {
            'value': value,
            'action': action
        },
        dataType: 'json',
        success: function(results) {
            if (results.nombre !== '') {
                /*Agregamos el id de la pestaña a modificar para utilizar un campo especifico*/
                $('#formEditCalculoInteres input[name="frente"]').val(results.frente);
                $('#formEditCalculoInteres input[name="fondo"]').val(results.fondo);
                $('#formEditCalculoInteres input[name="metros2"]').val(results.metros2);
                $('#formEditCalculoInteres input[name="varas2"]').val(results.varas2);
                $('#formEditCalculoInteres input[name="preciom2"]').val(results.preciom2);
                $('#formEditCalculoInteres input[name="costolote"]').val(results.costolote);
                $('#formEditCalculoInteres input[name="tasaint"]').val(results.tasaint);
            }
            if (results.message) {
                alert(results.message);
            }
        },
        error: function(msg) {
            alert(msg.status + ' ' + msg.responseText);
        }
    });
});