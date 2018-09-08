
    $("#save-rol").click(function(){
    	var name = $("#name").val();
    	var tip  = $("#tipo").val();
        var parameters = {"nombre" : name,"tipo" : tip};

        $.ajax({
			data:  parameters, //
			url:   '/rol', //
			type:  'post', //
			beforeSend: function () {
			      $("#save-rol").html("Procesando, espere por favor...");
			},
			success:  function (response) {
					//alert(response);
					$(".error-message").css("display","none");
					$(".success-message").html("Rol guardado con exito");
					$(".success-message").css("display","block");
					window.setTimeout(function(){
						window.location.href = "/roles";
					}, 2000);

			},
			error: function (xhr, ajaxOptions, thrownError,response) {
				$(".success-message").css("display","none");
		        //alert(xhr.status);
		        //alert(thrownError);
		        var obj = $.parseJSON(xhr.responseText);

		        $("#save-rol").html("Guardar!");
		        $(".error-message").css("display","block");
		        $(".error-message").html(obj.errors[0].message);

		      }
      	});

    });


	$("#save-usu").click(function(){
		var rut  	   = $("#rut").val();
    	var name 	   = $("#use_name").val();
    	var apellido   = $("#apellido").val();
    	var nick  	   = $("#nick").val();
    	var rol_id     = $("#tipo").val();
        var parameters = {"nombre" : name, "rut" : rut, "apellido" : apellido, "usuario" : nick};
        var uri = "/rol/"+rol_id+"/usuario";
        if(rol_id==""){
        	$("#save-usu").html("Guardar!");
		    $(".error-message").css("display","block");
		    $(".error-message").html("Faltan datos");
		    return false;
        }
        $.ajax({
			data:  parameters, //
			url:   uri, //
			type:  'post', //
			beforeSend: function () {
			      $("#save-usu").html("Procesando, espere por favor...");
			},
			success:  function (response) {
					//alert(response);
					$(".error-message").css("display","none");
					$(".success-message").html("Usuario guardado con exito");
					$(".success-message").css("display","block");
					window.setTimeout(function(){
						window.location.href = "/usuarios";
					}, 2000);

			},
			error: function (xhr, ajaxOptions, thrownError,response) {
				$(".success-message").css("display","none");
		        alert(xhr.status);
		        alert(thrownError);
		        var obj = $.parseJSON(xhr.responseText);

		        $("#save-usu").html("Guardar!");
		        $(".error-message").css("display","block");
		        $(".error-message").html(obj.errors[0].message);

		      }
      	});

    });


	/*$("#edit-rol").click(function(){
		var name = $("#name_edit").val();
    	var tip  = $("#tipo_edit").val();
    	var rolid = $("#id_edit").val();
        var parameters = {"nombre" : name,"tipo" : tip};

        $.ajax({
			data:  parameters, //
			url:   '/rol/'+rolid, //
			type:  'post', //
			beforeSend: function () {
			      $("#save-rol").html("Procesando, espere por favor...");
			},
			success:  function (response) {
					//alert(response);
					$(".error-message").css("display","none");
					$(".success-message").html("Rol actualizado con exito");
					$(".success-message").css("display","block");
					window.setTimeout(function(){
						window.location.href = "/roles";
					}, 2000);

			},
			error: function (xhr, ajaxOptions, thrownError,response) {
				$(".success-message").css("display","none");
		        //alert(xhr.status);
		        //alert(thrownError);
		        var obj = $.parseJSON(xhr.responseText);

		        $("#save-rol").html("Guardar!");
		        $(".error-message").css("display","block");
		        $(".error-message").html(obj.errors[0].message);

		      }
      	});
	});*/

	$(".delrol").click(function(){
		//alert($(this).attr("id"));
		var idrol = $(this).attr("id");
		//var parameters = {"id" : name};
		$.ajax({
			//data:  parameters, //
			url:   '/rol/'+idrol, //
			type:  'delete', //
			beforeSend: function () {
			      //$("#save-rol").html("Procesando, espere por favor...");
			},
			success:  function (response) {
					//alert(response);
					$(".error-message").css("display","none");
					$(".success-message").html("Rol eliminado con exito");
					$(".success-message").css("display","block");
					window.setTimeout(function(){
						window.location.href = "/roles";
					}, 2000);

			},
			error: function (xhr, ajaxOptions, thrownError,response) {
				$(".success-message").css("display","none");
		        //alert(xhr.status);
		        //alert(thrownError);
		        var obj = $.parseJSON(xhr.responseText);

		        //$("#save-rol").html("Guardar!");
		        $("#error-message-del").css("display","block");
		        if(obj.errors)
		        	$("#error-message-del").html(obj.errors[0].message);
		        else
		        	$("#error-message-del").html(obj.message);

		      }
      	});

	});

	$(".deluser").click(function(){
		//alert($(this).attr("id"));
		var idrol = $(this).attr("name");
		var idusu = $(this).attr("id");
		//var parameters = {"id" : name};
		var uri = "/rol/"+idrol+"/usuario/"+idusu;
		$.ajax({
			//data:  parameters, //
			url:   uri, //
			type:  'delete', //
			beforeSend: function () {
			      //$("#save-rol").html("Procesando, espere por favor...");
			},
			success:  function (response) {
					//alert(response);
					$(".error-message").css("display","none");
					$(".success-message").html("Usuario eliminado con exito");
					$(".success-message").css("display","block");
					window.setTimeout(function(){
						window.location.href = "/usuarios";
					}, 1000);

			},
			error: function (xhr, ajaxOptions, thrownError,response) {
				$(".success-message").css("display","none");
		        //alert(xhr.status);
		        //alert(thrownError);
		        var obj = $.parseJSON(xhr.responseText);

		        //$("#save-rol").html("Guardar!");
		        $("#error-message-del").css("display","block");
		        if(obj.errors)
		        	$("#error-message-del").html(obj.errors[0].message);
		        else
		        	$("#error-message-del").html(obj.message);

		      }
      	});

	});