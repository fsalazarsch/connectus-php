$(document).ready(function(){

    var error_registro = false;


    $(document).on('click', '#submit_btn', function(e){

        //e.preventDefault();

        var estado = true;

        var username    = $("#username").val();     if(username == '')  { estado = false;}
        var nombre      = $("#nombre").val();       if(nombre == '')    { estado = false;}
        var apellidos   = $("#apellidos").val();    if(apellidos == '') { estado = false;}
        var fono        = $("#fono").val();         if(fono == '')      { estado = false;}
        var email       = $("#email").val();        if(email == '')     { estado = false;}
        var pass        = $("#pass").val();         if(pass == '')      { estado = false;}
        var token       = $("#_token").val();       if(token == '')     { estado = false;}

        if(estado){

            e.preventDefault();

            var datos = {
                'username': username,
                'nombre':nombre,
                'apellidos':apellidos,
                'fono':fono,
                'email': email,
                'pass':pass,
                '_token':token
            }

            $.ajax({
                async:true,
                type: 'POST',
                data: datos,
                url: 'Controllers/registro.php',
                dataType: 'json',
                success: function(data) {
                    
                    if(data.estado){
                        
                        window.location.href = "/registrado/";
                    }else{
                        alert("hemos tenido problemas para registrarte, favor intenta nuevamente");
                        console.log(data.error);
                    }

                }
            });

        }


    });

    $(document).on('change', '#username', function(){
        valida_username(this);
    });

    $(document).on('keyup', '#username', function(){
        valida_username(this);
    });

    $(document).on('change', '#email', function(){
        valida_email(this);
    });

    $(document).on('keyup', '#email', function(){
        valida_email(this);
    });

    function valida_username(input)
    {

        var username   = $("#username").val();

        var datos = {
            'valida':'username',
            'username':username
        }

        var $div_padre = $(input).parent().parent();

        $.ajax({
            async:true,
            type: 'POST',
            data: datos,
            url: 'Controllers/valida.php',
            dataType: 'json',
            success: function(data) {
                
                if(data.estado){
                    $("#error_input_username").remove();
                    $($div_padre).addClass('has-error');
                    $($div_padre).append('<span id="error_input_username" class="help-block">este nombre de usuario no se encuentra disponible.</span>');
                    error_registro = true;
                }else{
                    $($div_padre).removeClass('has-error');
                    $("#error_input_username").remove();
                    error_registro = false;
                }

            }
        });
    }

    function valida_email(input)
    {
        var email   = $("#email").val();

        var datos = {
            'valida':'email',
            'email':email
        }

        var $div_padre = $(input).parent().parent();

        $.ajax({
            async:true,
            type: 'POST',
            data: datos,
            url: 'Controllers/valida.php',
            dataType: 'json',
            success: function(data) {

                if(data.estado){
                    $("#error_input_email").remove();
                    $($div_padre).addClass('has-error');
                    $($div_padre).append('<span id="error_input_email" class="help-block">'+data.error+'</span>');
                    error_registro = true;
                }else{
                    $($div_padre).removeClass('has-error');
                    $("#error_input_email").remove();
                    error_registro = false;
                }

            }
        });
    }

});