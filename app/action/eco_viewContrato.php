<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdPropostaLicenca = isset($_POST['cdPropostaLicenca']) ? base64_decode(base64_decode(base64_decode($_POST['cdPropostaLicenca']))) : null;

if (empty($cdPropostaLicenca) || is_null($cdPropostaLicenca)) {
	echo 'Não conseguimos encontrar o código do proposta.';
	exit();
}

$cdPropostaLicenca = cPropostaLicencaAmb::getVersaoAtual($cdPropostaLicenca);

var_dump($cdPropostaLicenca);

$proposta = new cPropostaLicencaAmb($cdPropostaLicenca);
$proposta->Dados();

$itensProposta = $proposta->DadosItensProposta();

$cliente  = new cCliente($proposta->cdCliente);
$cliente->Dados();

$representante = cRepresentante::getByCliente($proposta->cdCliente);

?>
<div class="modal-body">
	<h3>Contrato</h3>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="form-group">
				<textarea class="tinymce form-control" rows="4">
					<table>
						<tr>
							<td width="70%">
							</td>
							<td align="justify">
								Proposta de Serviço Nº <?php echo $proposta->dsProtocolo; ?>
								Referente a serviços de consultoria e assessoria ambiental no município do Paulista/PE.
							</td>
						</tr>
					</table>

					<p>
						A(o) Sr(a)
						<br>
						<strong><?php echo $representante->nm_representante; ?></strong>
						<br>
						Diretor
						<br>
						<?php echo $cliente->nmCliente; ?>
					</p>


					<p align="justify">
						A Calango Meio Ambiente e Tecnologia Ltda. vem pela presente proposta encaminhar para apreciação de V. Sª. detalhamento dos serviços a serem contratados, com estimativa de preços, previsão das atividades e produtos, conforme descrito no tópico que segue.
					</p>

					<p>
						<strong>1.	SERVIÇOS PROPOSTOS E PRODUTOS FINAIS A SEREM ENTREGUES</strong>
					</p>

					<p align="justify">
						<br>
						<br>
						<br>
						<br>
						<br>
					</p>

					<p align="justify">
						Destaca-se que as obrigações da CONTRATADA junto a CONTRATANTE tem seu fim após a entrega dos produtos/conclusão dos serviços acima pontuados. A presente proposta não contempla o acompanhamento da análise dos estudos junto ao órgão licenciador, tampouco cumprimento de condicionantes das licenças e autorizações.
					</p>

					<p>
						<strong>2.	VALORES E FORMA DE PAGAMENTO</strong>
					</p>


					<p>
						<strong>3.	PRAZO DE EXECUÇÃO</strong>
					</p>


					<p align="justify">
						Importante salientar que dos prazos supramencionados iniciam-se após a entrega de toda documentação necessária para elaboração dos produtos.
					</p>

					<p>
						<strong>4.	DISPOSIÇÕES GERAIS</strong>
					</p>
					<p align="justify">

						Não estão incluídos na presente proposta a elaboração de outros estudos ambientais que porventura venham a ser exigidos pelos órgãos competentes.
						Todas as despesas eventuais necessárias para a realização dos serviços não estão contempladas na presente proposta e deverão ser pagas pela CONTRATANTE (taxas, custas, cópias além das pontuadas no item 1). Também é de responsabilidade da CONTRATANTE e incorrerão em adicional de valor, alterações dos produtos em virtude de, por exemplo, alteração de projeto.
					</p>

					<p>
						<strong>5.	VALIDADE DA PROPOSTA</strong>
					</p>

					A presente proposta tem validade de 30 (trinta) dias.

					<p align="right">
						Recife, <?php echo date("d").' de '.date("F").' de '.date("Y"); ?>
					</p>

					<br>
					<br>

					<p align="center">
						<strong>MAGDA SIMONE LEITE PEREIRA CRUZ</strong>
						<br>
						Sócio – Administradora
					</p>
				</textarea>
			</div>
		</div>
		<!-- <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
			<iframe src="action/eco_gerarContrato.php" frameborder="0" style="width: 100%; height: 90vh"></iframe>
		</div> -->
	</div>
</div>
<script type="text/javascript">
	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	tinymce.init({
		selector: "textarea.tinymce",
		theme: "modern",
		height: 500,
		plugins: [
		'advlist autolink lists link charmap print preview hr anchor pagebreak',
		'searchreplace wordcount visualblocks visualchars code fullscreen',
		'insertdatetime nonbreaking save table contextmenu directionality',
		'template paste textcolor colorpicker textpattern'
		],
		toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | print preview | forecolor backcolor',
		toolbar2: '',
		image_advtab: true
	});
</script>