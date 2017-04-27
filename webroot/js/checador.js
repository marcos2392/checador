jQuery(function($) {
	$('#forma_crear_empleado,#forma_crear_usuario,#forma_crear_sucursal').on('keyup keypress', function(event) {
			var codigo_tecla = event.keyCode || event.which;
			if (codigo_tecla === 13) {
				event.preventDefault();
			}
		});

	$('.link_imprimir').click(function(event) {
		event.preventDefault();
		window.print();
	});
});