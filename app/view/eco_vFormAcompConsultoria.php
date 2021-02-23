<?php
$fase = new cFaseAtividade;
?>
<div class="container-fluid">
	<br>
	<!-- Consultoria fases -->
	<div class="row">
		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
			<a href="javascript:void(0)" class="btn-popover pull-right" id="popoverAddFases" data-container="body" data-toggle="popover" data-placement="bottom" title="Adicionar nova fase" data-content="<form><label>Selecione a fase:</label><select class='form-control' name='cdFase' onchange='addFaseAtividade(this)' data-live-search='true'><option>&nbsp;</option><?php $fase->listOption(); ?></select><br><div class='text-center'><a class='btn-block waves-effect' onclick='closePopOverAddFases()' sytle='text-decoration: none;'>Fechar</a></div></form>">
				<i class="material-icons col-green">add</i>
			</a>
			<h4 class="panel-title">
				Fases do Item
			</h4>
			<br>
			<div id="listaFases">
				<?php $atv->listFasesAtividade(); ?>
			</div>
		</div>
		<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
			<div id="comentarioFase">
				&nbsp;
			</div>
		</div>
	</div>
</div>
<div id="resultFase"></div>
<script type="text/javascript">

	$(".btn-popover").popover({
		html: true
	});

	var options = {
		valueNames: [ 'cod', 'name', 'email', 'phone', 'status' ],
		page: 5,
		pagination: true
	};

	var userList = new List('listUsers', options);

	function closePopOverAddFases() {
		$("#popoverAddFases").popover('hide');
	}

	function viewComentarioFase(c){

		$.ajax({
			url: 'action/eco_viewFormComentarioFase.php',
			type: 'POST',
			data: { cdAtividadeFase: $(c).data('cod') },
			success: function(data){
				$("#comentarioFase").html(data);
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

	}

	$("#listaFases input[type=checkbox]").change(function(){
		let checked = $(this).prop('checked');
		let cod 	= $(this).data('cod');

		$.ajax({
			url: 'action/eco_alterStatusFase.php',
			type: 'POST',
			data: { status: checked, cdAtividadeFase: cod },
			success: function(data) {
				$("#resultFase").html(data);
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

	function addFaseAtividade(a)
	{
		var val = $(a).val();
		closePopOverAddFases();

		$.ajax({
			url: 'action/eco_addFaseAtividade.php',
			type: 'POST',
			data: {cdFase: val, cdAtividade: '<?php echo base64_encode($cdAtividade); ?>'},
			success: function(data) {
				$("#resultFase").html(data);
			}
		})
		.done(function() {
			$.ajax({
				url: 'action/eco_viewResumoItServico.php',
				type: 'POST',
				data: {
					cdAtividade: '<?php echo $cdAtividade; ?>'
				},
				success: function(data){
					$("#formAlterAtividade").html(data);
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
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

	}

	function removerFaseAtividade(e){

		var cod	= $(e).data("cod");

		swal({
			title: "Tem certeza?",
			text: "Após remover uma fase, não poderá voltar atrás. Realmente concorda?",
			icon: "warning",
			buttons: ["Talvez mais tarde", "Sim, tenho"],
			dangerMode: true
		})
		.then((willDelete) => {
			if (willDelete) {

				$.ajax({
					url: 'action/eco_removerFaseAtividade.php',
					type: 'POST',
					data: {
						cdAtividadeFase: cod
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					$.ajax({
						url: 'action/eco_viewResumoItServico.php',
						type: 'POST',
						data: {
							cdAtividade: '<?php echo $cdAtividade; ?>'
						},
						success: function(data){
							$("#formAlterAtividade").html(data);
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
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			} else {
				swal({
					icon: "info",
					title: "Nada foi alterado",
					text: "A fase não foi deletada!"
				});
			}
		});
	}
</script>