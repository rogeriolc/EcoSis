<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$snViewAssessoria 	= cPermissao::validarPermissao(21, false);
$snViewConsultoria 	= cPermissao::validarPermissao(20, false);

$usuarios 		= new cUsuario;
$empreendimento = new cEmpreendimento;
$cliente 		= new cCliente;
$objLicenca		= new cObjetoLicenca;
$tpLicenca		= new cTpLicencaAmbiental;
$prop			= new cPropostaLicencaAmb;
$orgLicenciado  = new cOrgaoLicenciado;
$fase  			= new cFaseObjeto;
$tpAtividade	= new cTpAtividade;
$tpDocumento	= new cTpDocumento;

$cdServico 		= (isset($_POST['cdServico'])) ? base64_decode($_POST['cdServico']) : null;

$serv			= new cServico($cdServico);
$atv			= new cAtividade(null, $cdServico);

$cdCliente 			  = null;
$tpStatus 			  = null;
$cdEmpreendimento 	  = null;
$nrProcesso 		  = null;
$nmOrgaoLicenciado 	  = null;
$arrayAtvAssessoria   = array();
$arrayAtvConsultoria  = array();
$qtdAtvAbertas		  = 0;
$qtdAtvConcluidas	  = 0;
$percentualAtividades = 0;
$percentualAtividades = 0;
$maiorDataPrevista    = NULL;
$arrayAtividades	  = array();
$atividades	  		  = array();

// var_dump($_SESSION);

if(!empty($cdServico) && !is_null($cdServico)) {
	$serv->Dados();


	$cdCliente 			= $serv->getCdCliente();
	$cdEmpreendimento 	= $serv->getCdEmpreendimento();
	$nrProcesso 		= $serv->getNrProcesso();
	$cdOrgaoLicenciado	= $serv->getCdOrgaoLicenciado();
	$tpStatus 			= $serv->getTpStatus();
	$atividades 		= $serv->getAtividades();

	$cliente->setCdCliente($cdCliente);
	$cliente->Dados();
	$nmCliente 			= $cliente->nmCliente;

	$representante      = $cliente->getRepresentante();

	$empreendimento->setCdCliente($cdCliente);
	$empreendimento->setCdEmpreendimento($cdEmpreendimento);
	$empreendimento->Dados();
	$nmEmpreendimento	= $empreendimento->nmEmpreendimento;

	if (!is_null($cdOrgaoLicenciado)) {
		$orgLicenciado->setCdOrgaoLicenciado($cdOrgaoLicenciado);
		$orgLicenciado->Dados();
		$nmOrgaoLicenciado	= $orgLicenciado->nmOrgaoLicenciado;
	} else {
		$nmOrgaoLicenciado	= null;
	}


	$arrayAtividades	  = $atv->returnArrayAtividade();
	$arrayAtvDtPrev		  = array();

	if(count($arrayAtividades) > 0){
		foreach ($arrayAtividades as $key => $value) {

			//Separa as concluídas e em andamento/reaberto
			switch ($value["tp_status"]) {
				case 'E':
				case 'R':
				$qtdAtvAbertas++;
				break;

				case 'O':
				$qtdAtvConcluidas++;
				break;
			}

			//alimenta o array de datas previstas
			$arrayAtvDtPrev[] = $value["dt_prev_entrega"];


			//Separa em assessoria e consultoria
			if($value["tp_atividade"] == 'A'){
				$arrayAtvAssessoria[] = $value["cd_atividade"];
			}else if($value["tp_atividade"] == 'C'){
				$arrayAtvConsultoria[] = $value["cd_atividade"];
			}else{

			}
		}

		//calcula o percentual de realização das atividades
		$percentualAtividades = number_format(($qtdAtvConcluidas*100)/count($arrayAtividades),0,',','.');

	}

	//verifica a maior posição do array e atribui a maior data
	$maiorDataPrevista = (count($arrayAtvDtPrev) > 0) ? max($arrayAtvDtPrev) : null;

}

/*
E - Em andamento
C - Cancelado
A - Atrasado
O - Concluído
N - Cadastro não finalizado
R - Reaberta
*/

switch ($tpStatus) {
	case 'C':
	case 'O':
	case 'S':
	$propBtnSalvar   = 'disabled';
	$propBtnConcluir = 'disabled';
	$propBtnCancelar = 'disabled';
	$propInputsForm  = 'disabled';
	break;

	default:
	$propBtnSalvar   = null;
	$propBtnConcluir = null;
	$propBtnCancelar = null;
	$propInputsForm  = null;
	break;
}

$animate = (isset($_GET['animate']) && $_GET['animate'] == 'N') ? null : 'animated bounceInRight';

$produtos = cAtividade::getProdutosAssessoria(null, $cdServico);
$documentosServico = cServico::getDocumentosServico(null, $cdServico);

?>

<div class="row <?php echo $animate; ?>">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<form id="formCadServico">
			<input type="hidden" name="cdServico" value="<?php echo $cdServico; ?>" />
			<div class="card">
				<div class="header">
					<ul class="m-r--5 pull-right" style="list-style: none; margin-bottom: 0;">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons mdc-text-grey-600">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="cadServico(this)"><i class="material-icons" style="color: #3F51B5 !important;">save</i> Salvar</a></li>
								<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="concluirLicenca(this)"><i class="material-icons" style="color: #4CAF50 !important;">check</i> Concluir</a></li>
								<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="cancelarLicenca(this)"><i class="material-icons" style="color: #F44336 !important;">block</i> Cancelar</a></li>
							</ul>
						</li>
					</ul>
					<h2><i class="material-icons pull-left p-r-10 m-t--3 col-deep-purple">assignment</i> Dados do Serviço</h2>
				</div>

				<!-- Resumo do Serviço -->

				<div class="body p-a-0">

					<div role="tabpanel">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs tab-col-deep-purple" role="tablist">
							<li role="presentation" class="active">
								<a href="#resumoServico" aria-controls="resumoServico" role="tab" data-toggle="tab"><i class="material-icons mdc-text-deep-purple-600">info</i> Resumo do Serviço</a>
							</li>
							<li role="presentation">
								<a href="#tabDocumentosServ" aria-controls="tabDocumentosServ" role="tab" data-toggle="tab"><i class="material-icons mdc-text-deep-purple-600">file_copy</i> Documentos do Serviço <span class="label-count mdc-bg-green" id="labelTabDocServico" style="color: #fff" ;=""><?php echo count($documentosServico); ?></span></a>
							</li>
							<li role="presentation">
								<a href="#tabDocumentosConsultoria" aria-controls="tabDocumentosConsultoria" role="tab" data-toggle="tab"><i class="material-icons mdc-text-deep-purple-600">how_to_vote</i> Documentos da Consultoria <span class="label-count mdc-bg-green" id="labelTabDocConsultoria" style="color: #fff" ;="">0</span></a>
							</li>
							<li role="presentation">
								<a href="#tabDocumentosAssessoria" aria-controls="tabDocumentosAssessoria" role="tab" data-toggle="tab"><i class="material-icons mdc-text-deep-purple-600">how_to_vote</i> Documentos da Assessoria <span class="label-count mdc-bg-green" id="labelTabDocAssessoria" style="color: #fff" ;=""><?php echo count($produtos); ?></span></a>
							</li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content p-a-0">
							<div role="tabpanel" class="tab-pane active" id="resumoServico" style="padding: 0;">
								<div class="row clearfix">
									<div class="col-sm-6" style="margin-bottom: 0; padding-left: 30px; padding-top: 20px;">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
												<strong>Cliente<span class="col-red">*</span>:</strong>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
												<select name="cdCliente" style="width:100%" class="form-control" data-live-search="true" <?php echo $propInputsForm;?> >
													<option></option>
													<?php
													$cliente->listOption($cdCliente);
													?>
												</select>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
												<strong>Empreendimento<span class="col-red">*</span>:</strong>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
												<select name="cdEmpreendimento" style="width:100%" class="form-control" data-live-search="true" <?php echo $propInputsForm;?>>
													<option></option>
													<?php (!empty($cdServico) && !is_null($cdServico)) ? $empreendimento->listOption($cdEmpreendimento) : ''; ?>
												</select>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
												<strong>Orgão Licenciador<span class="col-red">*</span>:</strong>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
												<select name="cdOrgaoLicenciado" style="width:100%" class="form-control" data-live-search="true" <?php echo $propInputsForm;?>>
													<option></option>
													<?php $orgLicenciado->listOption($cdOrgaoLicenciado); ?>
												</select>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
												<strong>Nº do processo:</strong>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
												<div class="form-group">
													<div class="form-line">
														<input type="text" name="nrProcesso" class="form-control" value="<?php echo $nrProcesso;?>" <?php echo $propInputsForm;?> />
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6" style="margin-bottom: 0; padding-right: 30px;">
										<div class="row" style="display: flex; justify-content: center; min-height: 16em;">
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mdc-bg-grey-200 p-t-5">

												<h4>Resumo do serviço</h4>
												<h1><?php echo $qtdAtvConcluidas; ?><small>/<?php echo count($arrayAtividades); ?></small></h1>
												<br/>
												<div class="progress">
													<div class="progress-bar bg-green" role="progressbar" aria-valuenow="<?php echo $percentualAtividades; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $percentualAtividades; ?>%;">
														<?php echo $percentualAtividades; ?>%
													</div>
												</div>

											</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 mdc-bg-grey-200 p-t-5">
												<?php
												if ($tpStatus != "S"){
													?>
													<h4>Conclusão prévia</h4>
													<h2><small><i class="material-icons pull-left p-r-10 m-t-5">event</i> <?php echo (is_null($maiorDataPrevista)) ? null : date("d/m/Y", strtotime($maiorDataPrevista)); ?></small></h2>
												<?php } else { ?>
													<h4 class="text-center col-red">
														<i class="material-icons">block</i>
														<br/>
														Este serviço foi suspenso!
													</h4>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
								<div class="header mdc-bg-grey-100">
									<h2><i class="material-icons pull-left p-r-10 m-t--3 col-deep-purple font-30">assessment</i>&nbsp; Relatórios Rápidos</h2>
								</div>

								<!-- Fim do Resumo do Serviço -->

								<div class="body">
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<ul class="list-inline">
												<li class="text-center">
													<a href="action/eco_geraRelatorioAndamento.php?cdCliente=<?php echo base64_encode($cdCliente); ?>&cdServico=<?php echo base64_encode($cdServico); ?>&tpStatus=<?php echo base64_encode('"E","R"'); ?>" target="_blank" class="btn bg-deep-purple">
														<i class="material-icons">print</i>
														<br>
														Andamentos
													</a>
												</li>
												<li class="text-center">
													<a href="action/eco_geraRelatorioAndamento.php?cdCliente=<?php echo base64_encode($cdCliente); ?>&cdServico=<?php echo base64_encode($cdServico); ?>" target="_blank" class="btn bg-deep-purple">
														<i class="material-icons">print</i>
														<br>
														Histórico de Andamentos
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>

								<div class="header mdc-bg-grey-100">
									<ul class="m-r--5 pull-right" style="list-style: none; margin-bottom: 0;">
										<li class="dropdown">
											<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
												<i class="material-icons mdc-text-grey-600">more_vert</i>
											</a>
											<ul class="dropdown-menu pull-right">
												<!-- <li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="addAtividade()"><i class="material-icons" style="color: #4CAF50 !important;">add</i> Adicionar</a></li> -->
												<li><a data-toggle="modal" href="#modalCadAtividade" class="waves-effect waves-block"><i class="material-icons" style="color: #4CAF50 !important;">add</i> Novo item</a></li>
											</ul>
										</li>
									</ul>
									<h2><i class="material-icons pull-left p-r-10 m-t--3 col-deep-purple">assignment_turned_in</i> Itens do Serviço</h2>
								</div>

								<!-- Tabela de Atividades -->
								<div class="body">
									<div class="row clearfix">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding" id="tabsAtividades">
											<div role="tabpanel">
												<!-- Nav tabs -->
												<ul class="nav nav-tabs tab-col-deep-purple" id="tabAtividades" role="tablist">
												<!-- <li role="presentation">
													<a href="#tabAssessoria" aria-controls="tabAssessoria" role="tab" data-toggle="tab">Assessoria <span class="label-count mdc-bg-green" style="color: #fff;"><?php echo count($arrayAtvAssessoria); ?></span></a>
												</li> -->
												<!-- <li role="presentation">
													<a href="#tabConsultoria" aria-controls="tabConsultoria" role="tab" data-toggle="tab">Consultoria <span class="label-count mdc-bg-green" style="color: #fff";><?php echo count($arrayAtvConsultoria); ?></span></a>
												</li> -->

												<?php if ($snViewAssessoria){?>

													<li role="presentation" class="active">
														<a href="#tabAssessoria" aria-controls="tabAssessoria" role="tab" data-tp="A" data-toggle="tab">Assessoria <span class="label-count mdc-bg-green" style="color: #fff";><?php echo count($arrayAtvAssessoria); ?></span></a>
													</li>
												<?php } ?>
												<?php if ($snViewConsultoria){?>
													<li role="presentation">
														<a href="#tabConsultoria" aria-controls="tabConsultoria" role="tab" data-tp="C" data-toggle="tab">Consultoria <span class="label-count mdc-bg-green" style="color: #fff";><?php echo count($arrayAtvConsultoria); ?></span></a>
													</li>
												<?php } ?>
											</ul>
											<!-- Tab panes -->
											<div class="tab-content no-padding">
												<div role="tabpanel" class="tab-pane" id="tabAssessoriaOld">
													<br>
													<div class="table-responsive" style="min-height: 45em">
														<table class="table table-striped table-bordered dataTableSimple" id="tableAssessoria">
															<thead>
																<tr>
																	<th class="text-center mdc-bg-grey-100"><small>Descrição da atividade</small></th>
																	<th class="text-center mdc-bg-grey-100"><small>Responsável</small></th>
																	<th class="text-center mdc-bg-grey-100 text-center"><small>Prev. Entrega</small></th>
																	<th class="text-center mdc-bg-grey-100 text-center"><small>Opções</small></th>
																</tr>
															</thead>
															<tbody>
																<?php $atv->ListarTableForm('A'); ?>
															</tbody>
														</table>
													</div>
												</div>
												<div role="tabpanel" class="tab-pane" id="tabConsultoriaOld">
													<br>
													<div class="table-responsive" style="min-height: 45em">
														<table class="table table-striped table-bordered dataTableSimple" id="tableConsultoria">
															<thead>
																<tr>
																	<th class="text-center mdc-bg-grey-100"><small>Descrição da atividade</small></th>
																	<th class="text-center mdc-bg-grey-100"><small>Responsável</small></th>
																	<th class="text-center mdc-bg-grey-100 text-center"><small>Prev. Entrega</small></th>
																	<th class="text-center mdc-bg-grey-100 text-center"><small>Opções</small></th>
																</tr>
															</thead>
															<tbody>
																<?php $atv->ListarTableForm('C'); ?>
															</tbody>
														</table>
													</div>
												</div>
												<div role="tabpanel" class="tab-pane active" id="tabAssessoria">
													<br>
													<div class="form-group">
														<div class="input-group">
															<span class="input-group-addon">
																<i class="material-icons">search</i>
															</span>
															<div class="form-line">
																<input class="search form-control" placeholder="Digite para buscar..." />
															</div>
														</div>
													</div>
													<div class="p-a-10 mdc-bg-grey-100">
													<!-- <button type="button" class="sort btn bg-deep-purple waves-effect" data-sort="dt_prev_entrega">
														Sort
													</button> -->

													<ul class="list list-group list-margin-10 listAtividades"></ul>
												</div>
											</div>
											<div role="tabpanel" class="tab-pane" id="tabConsultoria">
												<br>
												<div class="form-group">
													<div class="input-group">
														<span class="input-group-addon">
															<i class="material-icons">search</i>
														</span>
														<div class="form-line">
															<input class="search form-control" placeholder="Digite para buscar..." />
														</div>
													</div>
												</div>
												<div class="p-a-10 mdc-bg-grey-100">
												<!-- <button type="button" class="sort btn bg-deep-purple waves-effect" data-sort="dt_prev_entrega">
													Sort
												</button> -->

												<ul class="list list-group list-margin-10 listAtividades"></ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="tabDocumentosServ" style="padding: 0; min-height: 800px">

					<div class="p-a-10 mdc-bg-grey-100">
						<a href="#modalAddSolDoc" data-toggle="modal" class="col-green"><i class="material-icons pull-left">add</i> Adicionar documento</a>
						<div class="pull-right">
						    <a href="#modalSolDoc" onclick="openEmailSolDoc(<?php echo $cdServico; ?>)" data-toggle="modal" class="col-amber"><i class="material-icons pull-left">announcement</i> &nbsp;Solicitar documentos pendentes</a>
					    </div>
					</div>

					<div class="p-a-10">

						<table class="table">
							<thead>
								<th style="width: 100px" class="text-center">Recebido</th>
								<th>Documento</th>
								<th>Atividade</th>
								<th>Anexado em</th>
								<th>Anexado por</th>
								<th width="30%">Anexo</th>
								<th width="200px" class="text-center">Opções</th>
							</thead>
							<tbody>
								<?php $serv->listarTableDocumentosSolicitados(); ?>
							</tbody>
						</table>

					</div>

				</div>
				<div role="tabpanel" class="tab-pane" id="tabDocumentosConsultoria" style="min-height: 800px">
					<div class="p-a-10">
						<table class="table table-condensed table-striped">
							<thead>
								<tr>
									<th width="50%">Documento</th>
									<th>Publicado por</th>
									<th>Em</th>
									<th>Opções</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$docs = cAtividade::listarDocConsultoria($cdServico);

								if (count($docs) > 0) {

									foreach ($docs as $doc) {

										$dhPublicado 	= date("d/m/Y H:i:s", strtotime($doc->dh_publicado));
										$dsCaminho 		= 'repo'.DIRECTORY_SEPARATOR.'eco'.DIRECTORY_SEPARATOR.'chat'.DIRECTORY_SEPARATOR.$doc->cd_atividade_fase.DIRECTORY_SEPARATOR.$doc->cd_atividade_fase_comentario.DIRECTORY_SEPARATOR.$doc->ds_anexo;

										echo "
										<tr>
										<td><a href='{$dsCaminho}' target='_blank'>{$doc->ds_anexo}</a></td>
										<td>{$doc->nm_usuario}</td>
										<td>{$dhPublicado}</td>
										<td class='text-center'>
										<ul class='m-r--5' style='list-style: none; margin-bottom: 0;'>
										<li class='dropdown'>
										<a href='javascript:void(0);' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
										<i class='material-icons mdc-text-grey-600'>more_vert</i>
										</a>
										<ul class='dropdown-menu pull-right'>
										<li><a href='javascript:void(0)' onclick='removerDocConsultoria(this)' data-doc='{$doc->cd_doc_consultoria}' class='waves-effect waves-block'><i class='material-icons col-red'>delete</i> Excluir</a></li>
										</ul>
										</li>
										</ul>
										</td>
										</tr>
										";

									}

								} else {
									echo '<tr><td colspan="4" class="text-center">Nenhum documento foi publicado</td></tr>';
								}
								?>
							</tbody>

						</table>
					</div>
				</div>

				<div role="tabpanel" class="tab-pane" id="tabDocumentosAssessoria" style="min-height: 800px">
					<br>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<table class="table table-condensed table-hover">
								<thead>
									<tr>
										<th></th>
										<th class="text-left">Descrição</th>
										<th>Data de emissão</th>
										<th>Data de validade</th>
									</tr>
								</thead>
								<tbody>
									<?php

									foreach ($produtos as $produto) {

										$dtEmissao = (!is_null($produto->dt_emissao) && !empty($produto->dt_emissao)) ? date("d/m/Y", strtotime($produto->dt_emissao)) : null;
										$dtValidade = (!is_null($produto->dt_validade) && !empty($produto->dt_validade)) ? date("d/m/Y", strtotime($produto->dt_validade)) : null;

										echo '
										<tr>
										<td>
										<a href="repo/eco/assessoria/'.$produto->cd_it_atividade.'/'.$produto->cd_doc_assessoria.'/'.$produto->ds_anexo.'" target="_blank">
										<i class="material-icons">insert_drive_file</i>
										<br>
										'.$produto->ds_anexo.'
										</a>
										</td>
										<td style="vertical-align: middle !important">
										'.$produto->ds_documento.'
										</td>
										<td style="vertical-align: middle !important">
										'.$dtEmissao.'
										</td>
										<td style="vertical-align: middle !important">
										'.$dtValidade.'
										</td>
										</tr>
										';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

				<!-- <div class="header mdc-bg-grey-100">
					<h2><i class="material-icons pull-left p-r-10 m-t--3 col-deep-purple">chat</i> Comentários</h2>
				</div>
				<div class="body p-t-0">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
							<div style="height: 300px; border-bottom: 2px solid #eee; overflow-x: hidden;" id="listComentario" class="mdc-bg-grey-100 p-a-10">
								<?php $serv->ListarComentarios(); ?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
							<div class="m-t-5">
								<div class="form-group">
									<div class="form-line">
										<textarea name="dsComentario" class="form-control no-resize" rows="3" placeholder="Digite aqui sua mensagem..."></textarea>
									</div>
								</div>
								<br/>
								<div class="row">
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<input type="checkbox" id="checkSnAtividade" name="snAtividade" class="filled-in chk-col-green" />
										<label for="checkSnAtividade">Transformar em atividade?</label>
										<select name="tpAtividadeMsg" class="form-control" data-live-search="true">
											<option value=""></option>
											<option value="A">ASSESSORIA</option>
											<option value="C">CONSULTORIA</option>
										</select>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<button class="btn bg-deep-purple pull-right" onclick="comentarServico(this)" data-cod="<?php echo $cdServico; ?>" type="button"><i class="material-icons">send</i> Enviar</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> -->

				<div class="footer">
				</div>
			</div>
		</form>
	</div>
</div>



<div class="modal fade" id="modalFormAddFaseObjetoLicenca">
	<form id="formAddFaseObjeto" method="POST">
		<input type="hidden" name="cdItLicencaAmbiental" />
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left m-t--5 col-green">add</i> Adicionar Fase</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<select class="form-control" name="cdFase[]" data-live-search="true" multiple>
							<?php $fase->listOption(); ?>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="material-icons pull-left m-t--5 col-red">close</i> Fechar</button>
					<button type="submit" class="btn bg-green"><i class="material-icons pull-left m-t--5">add</i> Adicionar</button>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal fade" id="modalProtocolo">
	<form id="formAddAnexoObjeto" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="cdItLicencaAmbiental" />
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left m-t--5 col-green">add</i> Adicionar Fase</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<select class="form-control" name="cdFase[]" data-live-search="true" multiple>
							<?php $fase->listOption(); ?>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="material-icons pull-left m-t--5 col-red">close</i> Fechar</button>
					<button type="submit" class="btn bg-green"><i class="material-icons pull-left m-t--5">add</i> Adicionar</button>
				</div>
			</div>
		</div>
	</form>
</div>

<!-- <div class="modal fade" id="modalCadAtividade">
	<div class="modal-dialog modal-lg" style="width: 1000px;">
		<form id="formCadAtividade">
			<input type="hidden" name="cdServico" value="<?php echo $cdServico;?>" />
			<div class="modal-content">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons">info</i> Informações do item</h4>
				</div>
				<div class="mdc-bg-grey-100 p-a-10">
					<ul class="list-unstyled list-inline font-12">
						<li><strong>Iniciado em:</strong></li><li><?php echo date('d/m/Y H:i:s'); ?></li>
						<li><strong>Status:</strong></li><li>Trabalhando</li>
					</ul>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
										<strong>Item do serviço:</strong>
										<div class="form-line">
											<select name="cdTpAtividade" style="width:100%" class="form-control" data-live-search="true">
												<option></option>
												<?php $tpAtividade->listOption(); ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<strong>Descrição da atividade:</strong>
										<div class="form-line">
											<textarea class="form-control no-resize" name="dsAtividade" rows="10"></textarea>
										</div>
									</div>
									<div class="form-group">
										<strong>Responsável:</strong>
										<div class="input-group">
											<span class="input-group-addon">
												<i class="material-icons">person</i>
											</span>
											<select name="cdUsuario" style="width:100%" class="form-control" data-live-search="true">
												<option></option>
												<?php $usuarios->listOption(); ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<strong>Previsão de Entrega:</strong>
										<div class="input-group">
											<span class="input-group-addon">
												<i class="material-icons">calendar_today</i>
											</span>
											<div class="form-line">
												<input class="form-control datepicker" name="dtPrevEntrega"/>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
									<div style="height: 300px; border-bottom: 2px solid #eee; overflow-x: hidden;" id="listComentario" class="mdc-bg-grey-100 p-a-10">
										<?php $serv->ListarComentarios(); ?>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
									<div class="m-t-5">
										<div class="form-group">
											<div class="form-line">
												<textarea name="dsComentario" class="form-control no-resize" rows="3" placeholder="Digite aqui sua mensagem..."></textarea>
											</div>
										</div>
										<br/>
										<div class="row">
											<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
												<input type="checkbox" id="checkSnAtividade" name="snAtividade" class="filled-in chk-col-green" />
												<label for="checkSnAtividade">Transformar em atividade?</label>
												<select name="tpAtividadeMsg" class="form-control" data-live-search="true">
													<option value=""></option>
													<option value="A">ASSESSORIA</option>
													<option value="C">CONSULTORIA</option>
												</select>
											</div>
											<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
												<button type="button" class="btn bg-deep-purple pull-right" onclick="comentarServico(this)" data-cod="<?php echo $cdServico; ?>" type="button"><i class="material-icons">send</i> Enviar</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="material-icons pull-left m-t--5 col-red">close</i> Fechar</button>
		<button type="submit" class="btn bg-green"><i class="material-icons pull-left m-t--5">save</i> Salvar</button>
				</div>
			</div>
		</form>
	</div>
</div> -->

<div class="modal fade" id="modalCadAtividade">
	<div class="modal-dialog modal-lg" style="width: 1000px;">
		<form id="formCadAtividade">
			<input type="hidden" name="cdServico" value="<?php echo $cdServico;?>" />
			<div class="modal-content">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons">info</i> Informações do Novo Item</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<strong>Item do serviço:</strong>
								<div class="form-line">
									<select name="cdTpAtividade" style="width:100%" class="form-control" data-live-search="true">
										<option></option>
										<?php $tpAtividade->listOption(); ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<strong>Descrição da atividade:</strong>
								<div class="form-line">
									<textarea class="form-control no-resize" name="dsAtividade" rows="10"></textarea>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
								<strong>Responsável:</strong>
								<div class="input-group">
									<span class="input-group-addon">
										<i class="material-icons">person</i>
									</span>
									<select name="cdUsuario" style="width:100%" class="form-control" data-live-search="true">
										<option></option>
										<?php $usuarios->listOption(); ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
								<strong>Previsão de Entrega:</strong>
								<div class="input-group">
									<span class="input-group-addon">
										<i class="material-icons">calendar_today</i>
									</span>
									<div class="form-line">
										<input class="form-control datepicker" name="dtPrevEntrega"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><i class="material-icons pull-left m-t--5 col-red">close</i> Fechar</button>
					<button type="submit" class="btn bg-green"><i class="material-icons pull-left m-t--5">save</i> Salvar</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal full fade" id="modalAlterAtividade">
	<div class="modal-dialog">
		<form id="formAlterAtividade">

		</form>
	</div>
</div>

<div class="modal fade" id="modalAddSolDoc">
	<div class="modal-dialog">
		<form id="formAddSolDoc" method="POST">
			<input type="hidden" name="cdServico" value="<?php echo base64_encode($cdServico); ?>">
			<div class="modal-content">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Adicionar Solicitação de Documento</h4>
				</div>
				<div class="modal-body">
					<strong>Selecione o documento:</strong>
					<select name="cdTpDocumento" data-live-search="true" class="form-control selectpicker">
						<option value="">&nbsp;</option>
						<?php $tpDocumento->listOption(); ?>
					</select>

					<br /><br />

					<strong>Selecione a atividade:</strong>
					<select name="cdAtividade" data-live-search="true" class="form-control selectpicker">
						<option value="">&nbsp;</option>
						<?php
							foreach ($atividades as $key => $atividade) {
								echo '<option value="'. base64_encode($atividade->cd_atividade) .'">'.$atividade->ds_tp_atividade.'</option>';
							}
						?>
					</select>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					<button type="submit" class="btn btn-success">Adicionar</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="modalAddAnexoSolDoc">
	<div class="modal-dialog">
		<form id="formAnexoDocumento" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="cdSolDoc">
			<input type="hidden" name="cdServico" value="<?php echo base64_encode($cdServico); ?>">
			<div class="modal-content">
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Anexar Documento</h4>
				</div>
				<div class="modal-body">
					<input type="file" name="fileAnexo">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					<button type="submit" class="btn btn-success">Adicionar</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="modalSolDoc">
	<div class="modal-dialog">
		<div class="modal-content">
		    <form id="formSolDocPendente" method="POST">
		        <input type="hidden" name="cdServico" value="<?php echo $cdServico; ?>" />
    			<div class="modal-header">
    				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    				<h4 class="modal-title">Email de Solicitação de Documentos</h4>
    			</div>
    			<div class="modal-body">
    				<div class="form-group">
    					<div class="form-line">
    						<label>Atividades: </label>
    						<select class="form-control" name="cdAtividadeSolDoc"></select>
    					</div>
    				</div>
    				<div class="form-group">
    					<div class="form-line">
    						<label>Emails do cliente: </label>
    						<select class="form-control" name="dsEmail[]" multiple>
    						    <?php
    						        echo (!empty($cliente->dsEmail)) ? '<option value="'.$cliente->dsEmail.'">'.$cliente->dsEmail.'</option>' : null;
    						        echo (!empty($representante->ds_email)) ? '<option value="'.$representante->ds_email.'">'.$representante->ds_email.'</option>' : null;
    						    ?>
    						</select>
    					</div>
    				</div>
    				<div>
    				    <p>Selecione os documentos pendentes:</p>
    				    <div id="listaDocPendentesSolicitacao">
    				        <table class="table table-condensed">
    				            <tbody>

    				            </tbody>
    				        </table>
    				    </div>
    				</div>
    			</div>
    			<div class="modal-footer">
    				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
    				<button type="submit" class="btn bg-deep-purple">Solicitar</button>
    			</div>
			</form>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="modalHistPrevData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Alterações da Previsão da Atividade</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	$('.datepicker').datetimepicker({
		format: 'DD/MM/YYYY'
	});

	var objPosition;
	var tpStatus 	= '<?php echo $tpStatus;?>';

	var tblAssessoria = $("#tableAssessoria").DataTable({
		"bLengthChange": false,
		"bInfo": false,
		"columns": [
		{ "width": "45%" },
		{ "width": "30%" },
		null,
		{ "width": "5%" }
		],
		responsive: true,
		pageLength: 5
	});

	var tblConsultoria = $("#tableConsultoria").DataTable({
		"bLengthChange": false,
		"bInfo": false,
		"columns": [
		{ "width": "45%" },
		{ "width": "30%" },
		null,
		{ "width": "5%" }
		],
		pageLength: 5
	});

	$("#tabAssessoria .listAtividades").sortable({
		placeholder: "ui-state-highlight",
		update: function( event, ui ) {
			let cards 	= $('#tabAssessoria .listAtividades li.list-group-item[data-cd_atividade]');
			let obj 	= Object.keys(cards);
			let itens 	= [];

			obj.map(function(key){

				if (isNaN(key)){

				} else {
					itens.push(cards[key].dataset.cd_atividade);
				}

			});

			$.ajax({
				url: 'action/eco_alterOrdemAtividade.php',
				type: 'POST',
				data: {cdAtividade: itens},
				success: function(data){
					$("#divResult").html(data);
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
	});

	$("#tabConsultoria .listAtividades").sortable({
		placeholder: "ui-state-highlight",
		update: function( event, ui ) {
			let cards 	= $('#tabConsultoria .listAtividades li.list-group-item[data-cd_atividade]');
			let obj 	= Object.keys(cards);
			let itens 	= [];

			obj.map(function(key){

				if (isNaN(key)){

				} else {
					itens.push(cards[key].dataset.cd_atividade);
				}

			});



			$.ajax({
				url: 'action/eco_alterOrdemAtividade.php',
				type: 'POST',
				data: {cdAtividade: itens},
				success: function(data){
					$("#divResult").html(data);
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
	});

	// ListJs
	var ListOptions = {
		valueNames: [ 'ds_tp_atividade', 'ds_atividade', 'dt_prev_entrega', 'dh_registro', 'nm_usuario', 'tp_status', 'nr_ordem', 'total_it_atividade', { data: ['cd_atividade', 'cd_tp_atividade'] } ],
		// item: '<li class="list-group-item cursorPointer" data-toggle="modal" href="#modalAlterAtividade" onclick="viewFormAlterAtividade(this)"><p class="tp_status pull-right"></p><h4 class="ds_tp_atividade col-deep-purple"></h4><h5 class="ds_atividade"></h5><ul class="list-inline list-unstyled font-12"><li><strong>Início:</strong></li><li class="dh_registro"></li><li><strong>Previsão:</strong></li><li class="dt_prev_entrega"></li><li><strong>Responsável:</strong></li><li class="nm_usuario"></li></ul></li>'
		item: '<li class="list-group-item cursorPointer m-b-10" data-toggle="modal" href="#modalAlterAtividade" onclick="viewResumoItServico(this)"><div class="pull-right"><div class="text-right"><label class="label label-default">Prioridade <span class="nr_ordem"></span></label></div><span class="tp_status"></span></div><h4 class="ds_tp_atividade col-deep-purple"></h4><h5 class="ds_atividade"></h5><ul class="list-inline list-unstyled font-12"><li><strong>Início:</strong></li><li class="dh_registro"></li><li><strong>Previsão:</strong></li><li class="dt_prev_entrega"></li><li><strong>Responsável:</strong></li><li class="nm_usuario"></li><li><strong>Andamentos:</strong></li><li><span class="badge bg-deep-purple total_it_atividade"></span></li></ul></li>'
	};

	var ListValues;
	var assessoriaList;
	var consultoriaList;

	$(document).ready(function(){
		ListValues = "";
		assessoriaList = "";
		consultoriaList = "";

		$("div.form-line.focused").removeClass("focused");

		// $('.listComentariosFase').slimScroll({
		// 	height: '20em'
		// });

		$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
			$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
		});

		$.ajax({
			url: 'action/eco_returnJsonAtividades.php',
			type: 'GET',
			datatype: 'json',
			data: { cdServico: '<?php echo $cdServico; ?>', tpAtividade: 'A'},
			success: function(data){

				console.log(data);

				for (var i = 0; i < data.length; i++) {
					console.log(data[i]['cd_tp_atividade']);
					$("#modalSolDoc select[name=cdAtividadeSolDoc]").append(`<option value="`+data[i]['cd_tp_atividade']+`">`+data[i]['ds_tp_atividade']+`</option>`);
				}

				$("#modalSolDoc select[name=cdAtividadeSolDoc]").selectpicker('refresh');

			}
		})
		.done(function() {
			assessoriaList = new List('tabAssessoria', ListOptions, ListValues);
		})
		.fail(function() {
		})
		.always(function() {
		});

		//assessoria
		$.ajax({
			url: 'action/eco_returnJsonAtividades.php',
			type: 'GET',
			datatype: 'json',
			data: { cdServico: '<?php echo $cdServico; ?>', tpAtividade: 'A' },
			success: function(data){
				ListValues = data;
				assessoriaData = data;

				console.log(data);
			}
		})
		.done(function() {
			assessoriaList = new List('tabAssessoria', ListOptions, ListValues);
		})
		.fail(function() {
		})
		.always(function() {
		});

		//consultoria
		$.ajax({
			url: 'action/eco_returnJsonAtividades.php',
			type: 'GET',
			datatype: 'json',
			data: { cdServico: '<?php echo $cdServico; ?>', tpAtividade: 'C' },
			success: function(data){
				ListValues = data;
				consultoriaList = data;
			}
		})
		.done(function() {
			consultoriaList = new List('tabConsultoria', ListOptions, ListValues);
		})
		.fail(function() {
		})
		.always(function() {
		});

		exibirQtdDocsConsultoria();
	});

	function exibirQtdDocsConsultoria() {

		new Promise(function(resolve, reject){

			$.ajax({
				url: 'action/eco_documentosConsultoria.php',
				type: 'POST',
				dataType: 'json',
				data: {cdServico: '<?php echo $cdServico; ?>'},
				success: function(data){
					resolve(data);
				},
				error: function(){
					console.log('error');
				}
			});

		})
		.then(function(docs){
			if (docs !== null) {
				$("#labelTabDocConsultoria").html(docs.length);
			}
		});

	}

	function carregaListaAssessoria(){

		$.ajax({
			url: 'action/eco_returnJsonAtividades.php',
			type: 'GET',
			datatype: 'json',
			data: { cdServico: '<?php echo $cdServico; ?>', tpAtividade: 'A' },
			success: function(data){
				ListValues = data;
			}
		})
		.done(function() {
			assessoriaList.clear();
			assessoriaList = new List('tabAssessoria', ListOptions, ListValues);
		})
		.fail(function() {
		})
		.always(function() {
		});

	}

	function carregaListaConsultoria(){

		$.ajax({
			url: 'action/eco_returnJsonAtividades.php',
			type: 'GET',
			datatype: 'json',
			data: { cdServico: '<?php echo $cdServico; ?>', tpAtividade: 'C' },
			success: function(data){
				ListValues = data;
			}
		})
		.done(function() {
			consultoriaList.clear();
			consultoriaList = new List('tabConsultoria', ListOptions, ListValues);
		})
		.fail(function() {
		})
		.always(function() {
		});

	}

	function recarregaFormServico(){

		$(".modal").modal('hide');

		$.ajax({
			url: 'action/eco_viewFormServico.php?animate=N',
			type: 'POST',
			data: { cdServico: '<?php echo base64_encode($cdServico); ?>' },
			success: function(data){
				$("#viewFormServico").html(data);
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

	function anchor(t){
		$("html, body").animate({ scrollTop: t.offset().top - 80 }, 600);
	}


	function cadServico(){

		if(tpStatus == 'C' || tpStatus == 'O'){

			swal("Desculpe...", "Esta licença já foi cancelada ou concluida.","error");

		}else{

			$.ajax({
				type: "POST",
				url: "action/eco_cadServico.php",
				data: $("#formCadServico").serialize(),
				success: function(data){
					$("#divResult").html(data);
				}
			})
			.done(function(){
				console.log('done');
			});

		}
	}

	$('#formCadServico select[name=cdCliente]').change(function(){
		var valor = $(this).val();
		var empre = '<?php echo base64_encode($cdEmpreendimento); ?>';

		$.ajax({
			url: 'action/g_listOptionEmpreendimento.php',
			type: 'POST',
			data: {cdCliente: valor, cdEmpreendimento: empre},
			success: function(data){
				$("#formCadServico select[name=cdEmpreendimento]").html(data);
				$("#formCadServico select[name=cdEmpreendimento]").selectpicker('refresh');
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

	$("#formCadAtividade").validate({
		rules:{
			cdTpAtividade: 	"required",
			dsAtividade: 	"required",
			cdUsuario: 		"required",
			dtPrevEntrega: 	"required"
		},
		messages:{
			cdTpAtividade: 	"Escolha o tipo da atividade",
			dsAtividade: 	"A descrição não pode ser vazia",
			cdUsuario: 		"Selecione o responsável pela atividade",
			dtPrevEntrega: 	"Selecione a data previta para conclusão"
		},
		highlight: function (input) {
			$(input).parents('.form-line').addClass('error');
		},
		unhighlight: function (input) {
			$(input).parents('.form-line').removeClass('error');
		},
		errorPlacement: function (error, element) {
			$(element).parents('.form-group').append(error);
		},
		success: "valid",
		submitHandler: function(form){

			var tpAtividade = $("#tabAtividades").find("li.active > a").data('tp');

			// alert(tpAtividade);

			$.ajax({
				url: 'action/eco_cadAtividade.php?tpAtividade='+tpAtividade,
				type: 'POST',
				data: $(form).serialize(),
				success: function(data){
					$("#divResult").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$("#modalCadAtividade").modal("hide");
				setTimeout(function(){
					// refresh(path);
					// carregaListaAssessoria();
					// carregaListaConsultoria();
					recarregaFormServico();
				},1000);
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

			return false;
		}
	});

	$("#formAlterAtividade").validate({
		rules:{
			cdTpAtividade: 	"required",
			dsAtividade: 	"required",
			cdUsuario: 		"required",
			dtPrevEntrega: 	"required"
		},
		messages:{
			cdTpAtividade: 	"Escolha o tipo da atividade",
			dsAtividade: 	"A descrição não pode ser vazia",
			cdUsuario: 		"Selecione o responsável pela atividade",
			dtPrevEntrega: 	"Selecione a data previta para conclusão"
		},
		highlight: function (input) {
			$(input).parents('.form-line').addClass('error');
		},
		unhighlight: function (input) {
			$(input).parents('.form-line').removeClass('error');
		},
		errorPlacement: function (error, element) {
			$(element).parents('.form-group').append(error);
		},
		success: "valid",
		submitHandler: function(form){

			var tpAtividade = $("#tabAtividades").find("li.active > a").attr('href');

			$.ajax({
				url: 'action/eco_alterAtividade.php?tpAtividade='+tpAtividade,
				type: 'POST',
				data: $(form).serialize(),
				success: function(data){
					$("#divResult").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$("#modalAlterAtividade").modal("hide");
				setTimeout(function(){
					// refresh(path);
					// carregaListaAssessoria();
					// carregaListaConsultoria();
					recarregaFormServico();
				},1000);
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

			return false;
		}
	});

	//adicionar atividade
	function addAtividade(e){
		var tpAtividade = $("#tabAtividades").find("li.active > a").attr('href');
		var menuOptions = [
		'<div class="form-group"><input type="hidden" name="cdTpAtividade[]" class="inputCdAtividade" /><input type="hidden" name="cdAtividade[]" class="inputCdTpAtividade" /><input type="hidden" name="tpAtividade[]" value="'+tpAtividade+'" /><div class="form-line"><textarea class="form-control no-resize" name="dsAtividade[]"></textarea></div></div>',
		'<div class="form-group"><div class="form-line"><select class="form-control" style="width: 100%" name="cdUsuario[]" data-live-search="true"><?php cCliente::staticListOption(); ?></select></div></div>',
		'<div class="form-group"><div class="form-line"><input class="form-control datepicker" name="dtPrevEntrega[]" /></div></div>',
		'<div class="text-center"><div class="btn-group"><button type="button" class="btn bg-deep-purple dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="material-icons">more_vert</i></button><ul class="dropdown-menu dropdown-menu-right"><li><a href="javascript:void(0);" onclick="removerAtividade(this)" data-tptable="'+tpAtividade+'" class="waves-effect waves-block"><i class="material-icons mdc-text-red">delete</i> Excluir</a></li></ul></div></div>'
		];

		if(tpAtividade == "#tabAssessoria"){
			tblAssessoria.row.add(menuOptions).draw();
		}else{
			tblConsultoria.row.add(menuOptions).draw();
		}

		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();

		$('.datepicker').datetimepicker({
			format: 'DD/MM/YYYY'
		});

		$("div.form-line.focused").removeClass("focused");

	}

	function removerAtividade(a){

		var cod = $(a).data('cod');

		swal({
			title: "Remover Atividade",
			text: "Tem certeza que deseja concluir a atividade?\nSe remover a atividade todo os andamentos serão excluídos. \nESTA OPERAÇÃO NÃO PODERÁ SER DESFEITA!",
			icon: "warning",
			buttons: {
				cancel: {
					text: "Não.. Me tire daqui!",
					value: null,
					visible: true,
					className: "",
					closeModal: true,
				},
				confirm: {
					text: "Sim, estou certo!",
					value: true,
					visible: true,
					className: "bg-green",
					closeModal: true
				}
			}
		})
		.then((willDelete) => {
			if (willDelete) {
				$.ajax({
					url: 'action/eco_removerAtividade.php',
					type: 'POST',
					data: {
						cdAtividade: cod
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {

					recarregaFormServico();
					// viewFormAlterAtividade(a);

				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
				});
			} else {
				swal({
					title: "Certo!",
					text: "Nada foi alterado",
					icon: "info"
				});
			}
		});

	}

	function concluirAtividade(a){

		var cod = $(a).data('cod');

		swal({
			title: "Concluir Atividade",
			text: "Tem certeza que deseja concluir a atividade?",
			icon: "warning",
			buttons: {
				cancel: {
					text: "Não.. Me tire daqui!",
					value: null,
					visible: true,
					className: "",
					closeModal: true,
				},
				confirm: {
					text: "Sim, estou certo!",
					value: true,
					visible: true,
					className: "bg-green",
					closeModal: true
				}
			}
		})
		.then((willDelete) => {
			if (willDelete) {
				$.ajax({
					url: 'action/eco_concluirAtividade.php',
					type: 'POST',
					data: {
						cdAtividade: cod
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {

					recarregaFormServico();
					// viewFormAlterAtividade(a);

				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
				});
			} else {
				swal({
					title: "Certo!",
					text: "Nada foi alterado",
					icon: "info"
				});
			}
		});

	}

	function reabrirAtividade(a){

		var cod = $(a).data('cod');

		swal({
			title: "Reabrir Atividade",
			text: "Tem certeza que deseja reabrir a atividade?",
			icon: "warning",
			buttons: ["Não.. Me tire daqui!", "Sim, estou certo!"],
			confirmButtonColor: '#683BB7'
		})
		.then((willDelete) => {
			if (willDelete) {
				$.ajax({
					url: 'action/eco_reabrirAtividade.php',
					type: 'POST',
					data: {
						cdAtividade: cod
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					// carregaListaAssessoria();
					// carregaListaConsultoria();
					// viewFormAlterAtividade(a);

					recarregaFormServico();
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
				});
			} else {
				swal({
					title: "Certo!",
					text: "Nada foi alterado",
					icon: "info"
				});
			}
		});

	}

	function tramiteAtividade(a){

		var cod = $(a).data('cod');

		swal({
			title: "Atividade em Trâmite",
			text: "Tem certeza que deseja colocar a atividade em trâmite?",
			icon: "warning",
			buttons: ["Não.. Me tire daqui!", "Sim, estou certo!"],
			confirmButtonColor: '#683BB7'
		})
		.then((willDelete) => {
			if (willDelete) {
				$.ajax({
					url: 'action/eco_tramiteAtividade.php',
					type: 'POST',
					data: {
						cdAtividade: cod
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					// carregaListaAssessoria();
					// carregaListaConsultoria();
					// viewFormAlterAtividade(a);

					recarregaFormServico();
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
				});
			} else {
				swal({
					title: "Certo!",
					text: "Nada foi alterado",
					icon: "info"
				});
			}
		});

	}

	function trabalharAtividade(a){

		var cod = $(a).data('cod');

		swal({
			title: "Trabalhar Atividade",
			text: "Tem certeza que deseja colocar a atividade em trabalho?",
			icon: "warning",
			buttons: ["Não.. Me tire daqui!", "Sim, estou certo!"],
			confirmButtonColor: '#683BB7'
		})
		.then((willDelete) => {
			if (willDelete) {
				$.ajax({
					url: 'action/eco_trabalharAtividade.php',
					type: 'POST',
					data: {
						cdAtividade: cod
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					// carregaListaAssessoria();
					// carregaListaConsultoria();
					// viewFormAlterAtividade(a);

					recarregaFormServico();
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
				});
			} else {
				swal({
					title: "Certo!",
					text: "Nada foi alterado",
					icon: "info"
				});
			}
		});

	}

	function comentarServico(c){

		$.ajax({
			url: 'action/eco_comentarServico.php',
			type: 'POST',
			data: $("#formCadServico").serialize(),
			success: function(data){
				$("#divResult").html(data);
			}
		})
		.done(function() {
			console.log("done");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {

			$("#formCadServico").find("textarea[name=dsComentario]").val('');

			$.ajax({
				url: 'action/eco_listaComentarios.php',
				type: 'POST',
				data: {cdServico: $(c).data("cod")},
				success: function(data){
					$("#listComentario").html(data);
				}
			})
			.done(function() {
				console.log("done");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
			})

			$.ajax({
				url: 'action/eco_viewTabsAtividades.php',
				type: 'POST',
				data: {cdServico: $(c).data("cod")},
				success: function(data){
					$("#tabsAtividades").html(data);
				}
			})
			.done(function() {
				console.log("done");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
			})
		})
	}

	function comentarAtividade(c){

		$.ajax({
			url: 'action/eco_comentarAtividade.php',
			type: 'POST',
			data: $("#formAlterAtividade").serialize(),
			success: function(data){
				$("#divResult").html(data);
			}
		})
		.done(function() {
			console.log("done");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {

			$("#formAlterAtividade").find("textarea[name=dsComentario]").val('');

			$.ajax({
				url: 'action/eco_listarComentarios.php',
				type: 'POST',
				data: {cdAtividade: $(c).data("cod")},
				success: function(data){
					$("#listaComentariosAtividade").html(data);
				}
			})
			.done(function() {
				console.log("done");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
			})

			$.ajax({
				url: 'action/eco_viewFormAlterAtividade.php',
				type: 'POST',
				data: {
					cdAtividade: $(c).data("cod")
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
	}

	function listarAnexos(a){

		var cdAtividade = $(a).data('cod');

		$("#frmFileUpload").find('input[type=hidden][name=cdAtividade]').val(cdAtividade);

		$.ajax({
			url: 'action/eco_listarAnexos.php',
			type: 'POST',
			data: {cdAtv: cdAtividade},
			success: function(data){
				$("#listAnexo > div.row").html(data);
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

	function excluirAnexo(a){

		swal({
			title: "Excluir Anexo",
			text: "Tem certeza que deseja excluir o anexo da atividade?",
			icon: "warning",
			buttons: {
				cancel: {
					text: "Não.. Me tire daqui!",
					value: null,
					visible: true,
					className: "",
					closeModal: true,
				},
				confirm: {
					text: "Sim, estou certo!",
					value: true,
					visible: true,
					className: "bg-green",
					closeModal: true
				}
			}
		})
		.then((isConfirm) => {
			if (isConfirm) {
				$.ajax({
					url: 'action/eco_excluirAnexo.php',
					type: 'POST',
					data: {dsDir: $(a).data('dir'), cdItAtividade: $(a).data('cod')},
					success: function(data){
						$("#listAnexoAndamento div.row").html(data);
					},
					error: function(){
						swal("ERRO!","NÃO FOI POSSÍVEL CARREGAR OS ANEXOS","error");
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
			} else {
				swal({
					title: "Certo!",
					text: "Nada foi alterado",
					icon: "info"
				});
			}
		});
	}

	function viewFormAlterAtividade(a){

		var atv = $(a).data('cd_atividade');

		$("ul.listAtividades li").removeClass('atvActive');
		$(a).addClass('atvActive');

		$.ajax({
			url: 'action/eco_viewFormAlterAtividade.php',
			type: 'POST',
			data: {
				cdAtividade: atv
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

	}

	function viewResumoItServico(a){

		var atv = $(a).data('cd_atividade');

		carregaHistAlterDataAtividade(atv);

		$("ul.listAtividades li").removeClass('atvActive');
		$(a).addClass('atvActive');

		$.ajax({
			url: 'action/eco_viewResumoItServico.php',
			type: 'POST',
			data: {
				cdAtividade: atv
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

	}

	function carregaHistAlterDataAtividade(atv)
	{
		$.ajax({
			url: 'action/eco_viewAlteracaoDataAtividade.php',
			type: 'POST',
			data: {
				cdAtividade: atv
			},
			success: function(data){
				console.log(data);
				$("#modalHistPrevData div.modal-body").html(data);
			}
		})
		.done(function() {

		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	$("#formAddSolDoc").submit(function(event) {

		$.ajax({
			url: 'action/eco_cadSolDocumentoServico.php',
			type: 'POST',
			data: $("#formAddSolDoc").serialize(),
			success: function(data){
				$("#divResult").html(data);
			}
		})
		.done(function() {
			console.log("success");
			$("#modalAddSolDoc").modal("hide");
			listarSolDoc();
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

		return false;
	});

	function listarSolDoc() {

		$.ajax({
			url: 'action/eco_listaDocumentosSolicitados.php',
			type: 'POST',
			data: { cdServico: '<?php echo base64_encode($cdServico); ?>' },
			success: function(data){
				$("#tabDocumentosServ table > tbody").html(data);
			}
		})
		.done(function() {
			console.log("success lista");
		})
		.fail(function() {
			console.log("error lista");
		})
		.always(function() {
			console.log("complete lista");
		});

	}

	function openEmailSolDoc(s) {

		$.ajax({
			url: 'action/eco_getDocumentoServico.php',
			type: 'POST',
			data: { cdServico: s },
			success: function(data){
				let lista = $("#listaDocPendentesSolicitacao table tbody");
				lista.empty();

				for (doc in data) {
				    console.log(doc);
				    lista.append(`<tr><td><input type="checkbox" name="docServico[]" id="docServ_`+data[doc].cd_tp_documento+`" class="filled-in chk-col-light-green" value="`+data[doc].ds_tp_documento+`" />
					<label for="docServ_`+data[doc].cd_tp_documento+`"></label></td><td class="text-left"><span class="text-left">`+data[doc].ds_tp_documento+`</span></td></tr>`);
				}

			}
		})
		.done(function() {
			console.log("success lista");
		})
		.fail(function() {
			console.log("error lista");
		})
		.always(function() {
			console.log("complete lista");
		});

	}

	function removerSolDoc(c) {

		swal({
			title: "Remover Solicitação",
			text: "Tem certeza que deseja remover esta solicitação de documento?",
			icon: "warning",
			buttons: ["Não.. Me tire daqui!", "Sim, estou certo!"]
		})
		.then((willDelete) => {
			if (willDelete) {

				$.ajax({
					url: 'action/eco_removerSolicitacaoDocumento.php',
					type: 'POST',
					data: {cdDocServico: c},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					console.log("success");
					listarSolDoc();
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			} else {
				swal({
					title: "Certo!",
					text: "Nada foi alterado",
					icon: "info"
				});
			}
		});

	}

	function receberDoc(e) {
		var doc = $(e).data("cod");
		var checked = $(e).prop('checked');

		$.ajax({
			url: 'action/eco_receberDocumento.php',
			type: 'POST',
			data: {
				cdDocServico: doc,
				recebido: checked,
			},
			success: function(data) {
				$("#divResult").html(data);
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

	function modalAnexoSolDoc(s){

		$("#formAnexoDocumento input[name=cdSolDoc]").val(s);

	}

	$("#formAnexoDocumento").submit(function(){

		var formData = new FormData(this);

		$.ajax({
			url: 'action/eco_anexaDocumentoSolicitacao.php',
			type: 'POST',
			enctype: 'multipart/form-data',
            processData: false, // impedir que o jQuery tranforma a "data" em querystring
            contentType: false, // desabilitar o cabeçalho "Content-Type"
            cache: false, // desabilitar o "cache"
            timeout: 600000, // definir um tempo limite (opcional)
			data: formData, //$(this).serialize(),
			success: function(data){
				$("#divResult").html(data);
			}
		})
		.done(function() {
			listarSolDoc();
			$("#modalAddAnexoSolDoc").modal('hide');
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

		return false;
	});

	function removerDocConsultoria(d){
		let cod = $(d).data('doc');

		swal({
			title: "Remover Documento",
			text: "Tem certeza que deseja remover este documento?\nTenha em mente que esta operação não poderá ser revertida!",
			icon: "warning",
			buttons: {
				cancel: {
					text: "Não.. Me tire daqui!",
					value: null,
					visible: true,
					className: "",
					closeModal: true,
				},
				confirm: {
					text: "Sim, estou certo!",
					value: true,
					visible: true,
					className: "bg-green",
					closeModal: true
				}
			}
		})
		.then((willDelete) => {
			if (willDelete) {
				$.ajax({
					url: 'action/eco_removerDocConsultoria.php',
					type: 'POST',
					data: {
						cdDocPublicado: cod
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {

					recarregaFormServico();

				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
				});
			} else {
				swal({
					title: "Certo!",
					text: "Nada foi alterado",
					icon: "info"
				});
			}
		});
	}

	$("#formSolDocPendente").submit(function() {

		swal({
			title: "Solicitar Documento",
			text: "Tem certeza que deseja enviar um e-mail para o cliente\nsolicitando o documento?",
			icon: "warning",
			input: 'select',
			inputOptions: {
				'1': 'Tier 1',
				'2': 'Tier 2',
				'3': 'Tier 3'
			},
			buttons: {
				cancel: {
					text: "Não.. Me tire daqui!",
					value: null,
					visible: true,
					className: "",
					closeModal: true,
				},
				confirm: {
					text: "Sim, estou certo!",
					value: true,
					visible: true,
					className: "bg-green",
					closeModal: true
				}
			}
		})
		.then((willSend) => {
			if (willSend) {
				$.ajax({
					url: 'action/eco_enviaEmailSolDoc.php',
					type: 'POST',
					data: $(this).serialize(),
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {

					recarregaFormServico();

				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
				});
			} else {
				swal({
					title: "Certo!",
					text: "Nada foi alterado",
					icon: "info"
				});
			}
		});

		return false;
	});






















































	$("#formAddFaseObjeto").submit(function(){

		var itLic = $("#formAddFaseObjeto input[name=cdItLicencaAmbiental]").val();

		$.ajax({
			url: 'action/eco_addFaseObjeto.php',
			type: 'POST',
			data: $("#formAddFaseObjeto").serialize(),
			success: function(data){
				// anchor("#tabObj"+objPosition);
			}
		})
		.done(function() {
			setTimeout(function(){
				$.AdminBSB.input.activate();
				$.AdminBSB.select.activate();

				$("#tabObj"+ (itLic) + "" + objPosition).load('action/eco_viewFaseObjeto.php?cdItLicenca='+itLic);
				$("#tabObj"+ (itLic) + "" + objPosition).addClass('in');

				$("#modalFormAddFaseObjetoLicenca").modal("hide");
			},1000);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
		});

		return false;
	});

	function selecionarObjeto(o,i){

		objPosition = o;

		$("#formAddFaseObjeto input[name=cdItLicencaAmbiental]").val(i);
	}

	function removerObjeto(o,i){

		objPosition = o;

		swal({
			title: "Só para confirmar...",
			text: "Tem certeza que deseja excluir o objeto? \n Lembre-se que excluindo o objeto suas FASES TAMBÉM SERÃO EXCLUÍDAS",
			icon: "warning",
			buttons: ["Talvez mais tarde", "Sim, tenho"],
			dangerMode: true
		})
		.then((willDelete) => {
			if (willDelete) {

				$(".page-loader-wrapper").fadeIn("fast");

				$.ajax({
					url: 'action/eco_removerObjetoLicenca.php',
					type: 'POST',
					data: {
						cdItLicencaAmbiental: i,
						objPosition: objPosition
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					console.log("success");
					$(".page-loader-wrapper").fadeOut("fast");
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

	function addObjetoLicenca(){

		if(tpStatus == 'C' || tpStatus == 'O'){

			swal("Desculpe...", "Esta licença já foi cancelada ou concluida.","error");

		}else{

			$.ajax({
				type: "POST",
				url: "action/eco_addObjetoLicenca.php",
				data: $("#formCadServico").serialize(),
				success: function(data){
					$("#tabsObj").html(data);
				}
			}).done(function() {
				$.AdminBSB.select.activate();
				$.AdminBSB.input.activate();
				$('.datepicker').datetimepicker({
					format: 'DD/MM/YYYY'
				});
				$("div.form-line.focused").removeClass("focused");
			});
		}
	}

	function salvarFase(f){

		var cdItLicencaFase	= $(f).find("input[name=cdItLicencaFase]").val();
		var dsFase 			= $(f).find("textarea[name=dsFase]").val();
		var dtPrevEntrega 	= $(f).find("input[name=dtPrevEntrega]").val();
		var cdResponsavel 	= $(f).find("select[name=cdResponsavel]").val();

		$.ajax({
			url: 'action/eco_salvarFaseObjeto.php',
			type: 'POST',
			data: {
				cdItLicencaFase: cdItLicencaFase,
				dsFase: dsFase,
				dtPrevEntrega: dtPrevEntrega,
				cdResponsavel: cdResponsavel
			},
			success: function(data){
				$("#divResult").html(data);
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

	function addFase(f){

		var cdItLicencaFase	= $(f).find("input[name=cdItLicencaFase]").val();

		$.ajax({
			url: 'action/eco_addFaseObjeto.php',
			type: 'POST',
			data: {
				cdItLicencaFase: cdItLicencaFase
			},
			success: function(data){
				$("#divResult").html(data);
			}
		})
		.done(function() {
			console.log("success");
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("always");
		});
	}

	function concluirFase(f){

		var cdItLicencaFase	= $(f).find("input[name=cdItLicencaFase]").val();

		//$.md5(cdItLicencaFase)

		swal({
			title: "Só para confirmar...",
			text: "Tem certeza que deseja concluir esta fase?",
			icon: "warning",
			buttons: ["Talvez mais tarde", "Sim, tenho"],
			dangerMode: true
		})
		.then((willDelete) => {
			if (willDelete) {

				$.ajax({
					url: 'action/eco_alterarStatusFase.php',
					type: 'POST',
					data: {
						cdItLicencaFase: cdItLicencaFase,
						status: 'O'
					},
					success: function(data){
						$("#divResult").html(data);
					}
				})
				.done(function() {
					console.log("success");
					$("#tabViewLicencaConsultoria").load("action/eco_viewListObjetoLicenca.php?cdItLicencaFase="+cdItLicencaFase+"&cdCatObjetoLicenca=1");
					$("#tabViewLicencaAssessoria").load("action/eco_viewListObjetoLicenca.php?cdItLicencaFase="+cdItLicencaFase+"&cdCatObjetoLicenca=2");
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

	function cancelarFase(f){

		var cdItLicencaFase	= $(f).find("input[name=cdItLicencaFase]").val();

		swal({
			title: "Só para confirmar...",
			text: "Tem certeza que deseja cancelar esta fase?",
			icon: "warning",
			buttons: ["Talvez mais tarde", "Sim, tenho"],
			dangerMode: true
		})
		.then((willDelete) => {
			if (willDelete) {

				$.ajax({
					url: 'action/eco_alterarStatusFase.php',
					type: 'POST',
					data: {
						cdItLicencaFase: cdItLicencaFase,
						status: 'C'
					},
					success: function(data){
						$("#divResult").html(data);
						refreshAbasObjetos(cdItLicencaFase);
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

			} else {
				swal({
					icon: "info",
					title: "Nada foi alterado",
					text: "A fase não foi deletada!"
				});
			}
		});
	}

	function reabrirFase(f){

		var cdItLicencaFase	= $(f).find("input[name=cdItLicencaFase]").val();

		swal({
			title: "Só para confirmar...",
			text: "Tem certeza que deseja REABIR esta fase?",
			icon: "warning",
			buttons: ["Talvez mais tarde", "Sim, tenho"],
			dangerMode: true
		})
		.then((willDelete) => {
			if (willDelete) {

				$.ajax({
					url: 'action/eco_alterarStatusFase.php',
					type: 'POST',
					data: {
						cdItLicencaFase: cdItLicencaFase,
						status: 'R'
					},
					success: function(data){
						$("#divResult").html(data);
						refreshAbasObjetos(cdItLicencaFase);
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

			} else {
				swal({
					icon: "info",
					title: "Nada foi alterado",
					text: "A fase não foi modificada!"
				});
			}
		});
	}

	function addComentarioFase(f){

		var it = $(f).data("cod");

		$.ajax({
			url: 'action/eco_addComentarioFase.php',
			type: 'POST',
			data: {cdItLicencaFase: it, dsComentario: $("#dsComentario"+it).val()},
			success: function(data){
				$("#divResult").html(data);
			}
		})
		.done(function() {
			$("div#listComentariosFase"+it).load("action/eco_viewListComentarioFase.php?cdItLicencaFase="+it);

			//limpa campo
			$("#dsComentario"+it).val('');
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}

	function refreshAbasObjetos(cdItLicencaFase){
		$("#tabViewLicencaConsultoria").load("action/eco_viewListObjetoLicenca.php?cdItLicencaFase="+cdItLicencaFase+"&cdCatObjetoLicenca=1");
		$("#tabViewLicencaAssessoria").load("action/eco_viewListObjetoLicenca.php?cdItLicencaFase="+cdItLicencaFase+"&cdCatObjetoLicenca=2");
	}

	function openFile(file) {
		window.open(`${window.location.pathname}action/g_openFile.php?id=${file}`)
	}

</script>