jQuery(function($) {
	$('#forma_crear_empleado,#forma_crear_usuario,#forma_crear_sucursal').on('keyup keypress', function(event) {
			var codigo_tecla = event.keyCode || event.which;
			if (codigo_tecla === 13) {
				event.preventDefault();
			}
		});

	window.onload=function() {
        var horarios=$('#horarios');

        horarios.addClass("hidden");
            horarios.attr("required",false);
    }

	$('.link_imprimir').click(function(event) {
		event.preventDefault();
		window.print();
	});

	$('#mostrar').change(function(event) {
		var valor=$(this).val();
		var opcion=$(this).find("option[value="+valor+"]");
		var mixto=opcion.data("mixto");
		var entrada_existente=opcion.data("entrada");

		var horarios=$('#horarios');

		if(mixto)
		{
			if(!entrada_existente)
			{
				horarios.removeClass("hidden");
				horarios.attr("required",true);
			}
			else
			{
				horarios.addClass("hidden");
				horarios.attr("required",false);
			}
		}
		else
		{
			horarios.addClass("hidden");
			horarios.attr("required",false);
		}
	});

	$('form.disable').submit(function() {
		var boton = $(this).find("[type='submit']");
  		boton.prop('disabled',true);
		if ($(this).data('disable-with')) {
			boton.text($(this).data('disable-with'));
		}
	});

	
});