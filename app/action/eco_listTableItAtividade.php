<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdAtividade = $_POST['cdAtividade'];

$atv 		 = new cAtividade($cdAtividade);

$atv->ListarItAtividade();

?>
<script type="text/javascript">
	$("tr.viewAndamento").click(function(){
		$("#cardViewAndamento").removeClass('hide');

		$('#divAndamento').animate({
            scrollTop: $("#cardViewAndamento").offset().top
        }, 500);

		$.ajax({
			url: 'action/eco_viewFormAndamento.php',
			type: 'POST',
			data: {cdItAtividade: $(this).data('cod')},
			success: function(data){
				$("#cardViewAndamento").html(data);
			}
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});


	});
</script>