<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$empreendimento = new cEmpreendimento();
$porteEmpre     = new cPorteEmpreendimento();
$pPoluidor      = new cPotencialPoluidor();
$cliente 		= new cCliente();
$tpArea 		= new cTpArea();
$tpRevisao 		= new cTpRevisao();
$tipografia		= new cTipografia();

$nmArquivo 		= basename($_SERVER['PHP_SELF']);

?>
<div class="container-fluid">
	<div class="col-white">
		<h4>Cadastro de Empreendimento</h4>
	</div>
	<br />
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<h2>
						Lista de Empreendimentos
					</h2>
					<ul class="header-dropdown m-r--5">
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">more_vert</i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a data-toggle="modal" href="#modalFormCadEmpreendimento" class="waves-effect waves-block"><i class="material-icons">add</i> Adicionar</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="body">
					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
							<thead>
								<tr>
									<th width="30px">Código</th>
									<th>Nome do Empreendimento</th>
									<th>Cliente</th>
									<th>Potencial Poluidor</th>
									<th>Tipografia</th>
									<th>Porte</th>
									<th width="50px">Status</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th width="30px">Código</th>
									<th>Nome do Empreendimento</th>
									<th>Cliente</th>
									<th>Potencial Poluidor</th>
									<th>Tipografia</th>
									<th>Porte</th>
									<th width="50px">Status</th>
								</tr>
							</tfoot>
							<tbody>
								<?php
								$empreendimento->listTable();
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modalFormCadEmpreendimento">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="formCadEmpreendimento">
				<div class="modal-header bg-deep-purple-gr">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Cadastro de Empreendimentos</h4>
				</div>
				<div class="modal-body">
					<br />
					<div class="row clearfix">
						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Nome do empreendimento:</label>
									<input type="text" name="nmEmpreendimento" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Cliente:</label>
									<select class="select2 form-control" name="cdCliente" data-live-search="true">
										<option value=""></option>
										<?php $cliente->listOption(); ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<div class="form-line">
									<label>Porte:</label>
									<select class="select2 form-control" name="cdPorteEmpreendimento" data-live-search="true">
										<option value=""></option>
										<?php $porteEmpre->listOption(); ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<div class="form-line">
									<label>Tipografia:</label>
									<select class="select2 form-control" name="cdTipografia" data-live-search="true">
										<option value=""></option>
										<?php $tipografia->ListarOption(); ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<div class="form-line">
									<label>Potencial Poluidor:</label>
									<select class="select2 form-control" name="cdPotencialPoluidor" data-live-search="true">
										<option value=""></option>
										<?php $pPoluidor->listOption(); ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

							<label>Dormitórios:</label>
							<div class="input-group spinner" data-trigger="spinner">
								<div class="form-line">
									<input type="text" name="qtdDormitorios" class="form-control text-center" value="0" data-rule="quantity" data-min="0">
								</div>
								<span class="input-group-addon">
									<a href="javascript:void(0)" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
									<a href="javascript:void(0)" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
								</span>
							</div>

						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

							<label>Banheiros:</label>
							<div class="input-group spinner" data-trigger="spinner">
								<div class="form-line">
									<input type="text" name="qtdBanheiros" class="form-control text-center" value="0" data-rule="quantity" data-min="0">
								</div>
								<span class="input-group-addon">
									<a href="javascript:void(0)" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
									<a href="javascript:void(0)" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
								</span>
							</div>

						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

							<label>Unidades:</label>
							<div class="input-group spinner" data-trigger="spinner">
								<div class="form-line">
									<input type="text" name="qtdUnidades" class="form-control text-center" value="0" data-rule="quantity" data-min="0">
								</div>
								<span class="input-group-addon">
									<a href="javascript:void(0)" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
									<a href="javascript:void(0)" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
								</span>
							</div>

						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<div class="form-line">
									<label>Tamanho das Unidades:</label>
									<input type="text" name="dsTamanhoUnidade" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">

							<div class="form-group">
								<div class="form-line">
									<label>Abastecimento:</label>
									<select class="select2 form-control show-tick" data-live-search="true" name="cdAbastecimento[]" multiple>
										<?php cAbastecimento::ListarOption(); ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<div class="form-line">
									<label>Outro tipo de abastecimento:</label>
									<textarea name="dsAbastecimento" class="form-control" rows="3"></textarea>
								</div>
							</div>

						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

							<label>Outorga?</label>
							<div class="switch">
								<label><input type="checkbox" name="snOutorgaAbastecimento" value="S"><span class="lever switch-col-green"></span></label>
							</div>

						</div>
						<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">

							<div class="form-group">
								<div class="form-line">
									<label>Tratamento de Afluentes:</label>
									<select class="select2 form-control show-tick" data-live-search="true" name="cdTratamento[]" multiple>
										<?php cTratamento::ListarOption(); ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<div class="form-line">
									<label>Outro tipo de tratamento:</label>
									<textarea name="dsTratamentoAfluentes" class="form-control" rows="3"></textarea>
								</div>
							</div>

						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

							<div class="form-group">
								<label>Outorga?</label>
								<div class="switch">
									<label><input type="checkbox" name="snOutorgaTratamento" value="S"><span class="lever switch-col-green"></span></label>
								</div>
							</div>

						</div>
						<div class="clearfix">

						</div>
						<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
							<div class="form-group">
								<label>Haverá terraplanagem?</label>
								<div class="switch">
									<label><input type="checkbox" name="snTerraplanagem" value="S"><span class="lever switch-col-green"></span></label>
								</div>
							</div>

						</div>
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

							<div class="form-group">
								<label>Haverá supresão/erradicação?</label>
								<div class="switch">
									<label><input type="checkbox" name="snSuspensaoErradicacao" value="S"><span class="lever switch-col-green"></span></label>
								</div>
							</div>

						</div>
						<div class="clearfix">

						</div>

						<!-- Endereço do empreendimento -->
						<div class="col-xs-2">
							<div class="form-group">
								<div class="form-line">
									<label>CEP:</label>
									<input type="text" name="cdCep" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group">
								<div class="form-line">
									<label>Endereço:</label>
									<input type="text" id="rua" name="dsEndereco" class="form-control autoRua">
								</div>
							</div>
						</div>
						<div class="col-xs-2">
							<div class="form-group">
								<div class="form-line">
									<label>Bairro:</label>
									<input type="text" id="bairro" name="nmBairro" class="form-control autoBairro">
								</div>
							</div>
						</div>
						<div class="col-xs-2">
							<div class="form-group">
								<div class="form-line">
									<label>Cidade:</label>
									<input type="text" id="cidade" name="nmCidade" class="form-control autoCidade">
								</div>
							</div>
						</div>
						<div class="col-xs-2">
							<div class="form-group">
								<div class="form-line">
									<label>UF:</label>
									<input type="text" id="uf" name="uf" class="form-control autoUf">
								</div>
							</div>
						</div>

						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Descrição:</label>
									<textarea class="form-control" name="dsEmpreendimento"></textarea>
								</div>
							</div>
						</div>

						<div role="tabpanel">

							<!-- Nav tabs -->
							<ul class="nav nav-tabs tab-col-deep-purple" role="tablist">
								<li role="presentation" class="active">
									<a href="#tabAreaEmpreendimento" aria-controls="tabAreaEmpreendimento" role="tab" data-toggle="tab"><i class="material-icons col-deep-purple">landscape</i> Área do empreendimento</a>
								</li>
								<li role="presentation">
									<a href="#tabRevisaoEmpreendimento" aria-controls="tabRevisaoEmpreendimento" role="tab" data-toggle="tab"><i class="material-icons col-deep-purple">wrap_text</i> Revisões</a>
								</li>
								<li role="presentation">
									<a href="#tabContatos" aria-controls="tabContatos" role="tab" data-toggle="tab"><i class="material-icons col-deep-purple">people</i> Contatos</a>
								</li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content" style="padding: 0 0 10px 0;">
								<div role="tabpanel" class="tab-pane active" id="tabAreaEmpreendimento">
									<div class="mdc-bg-grey-100 p-a-10 text-right">

										<button type="button" class="btn bg-green btn-sm waves-effect" onclick="addTpAreaEmpreendimento('c')"><i class="material-icons">add</i>&nbsp; Adicionar</button>

									</div>
									<br />
									<table class="table">
										<thead>
											<tr>
												<th>Tipo da Área</th>
												<th>Valor</th>
												<th width="30px">Remover</th>
											</tr>
										</thead>
										<tbody id="listCadTpArea">
											<tr id="infoTableTpArea">
												<td colspan="3" class="text-center col-deep-purple">
													<i class="material-icons">info</i><br> Nenhuma área foi inserida
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div role="tabpanel" class="tab-pane" id="tabRevisaoEmpreendimento">
									<div class="mdc-bg-grey-100 p-a-10 text-right">

										<button type="button" class="btn bg-green btn-sm waves-effect" onclick="addTipoRevisao('c')"><i class="material-icons">add</i>&nbsp; Adicionar</button>

									</div>
									<br />
									<table class="table">
										<thead>
											<tr>
												<th>Item de Revisão</th>
												<th>Valor</th>
												<th width="30px">Remover</th>
											</tr>
										</thead>
										<tbody id="listCadRevisao">
											<tr class="infoTableRevisao">
												<td colspan="5" class="text-center col-deep-purple">
													<i class="material-icons">info</i><br> Nenhum item de revisão foi inserido
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div role="tabpanel" class="tab-pane" id="tabContatos">
									<div class="mdc-bg-grey-100 p-a-10 text-right">

										<button type="button" class="btn bg-green btn-sm waves-effect" onclick="addContatoEmpreendimento('c')"><i class="material-icons">add</i>&nbsp; Adicionar</button>

									</div>
									<br />
									<table class="table">
										<thead>
											<tr>
												<th>Nome do contato</th>
												<th>Departamento</th>
												<th>Cargo</th>
												<th>Telefone</th>
												<th width="30px">Remover</th>
											</tr>
										</thead>
										<tbody id="listCadContato">
											<tr class="infoTableContato">
												<td colspan="5" class="text-center col-deep-purple">
													<i class="material-icons">info</i><br> Nenhum contato foi inserido
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons col-red">close</i> Fechar</button>
					<button type="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i> Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modalFormAlterEmpreendimento">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="formAlterEmpreendimento">
				<div id="idDadosForm"></div>
				<div class="modal-header bg-deep-purple">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de Alteração de Empreendimentos</h4>
				</div>
				<div class="modal-body">
					<br />
					<div class="row clearfix">
						<div class="col-sm-3">
							<div class="form-group">
								<div class="form-line">
									<label>Cód. Empreendimento:</label>
									<input type="text" name="cdEmpreendimento" class="form-control" readonly>
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<div class="form-line">
									<label>Ativo?</label>
									<select class="form-control show-tick" name="snAtivo" data-live-search="true" autofocus="off">
										<option value="S">Sim</option>
										<option value="N">Não</option>
									</select>
								</div>
							</div>
						</div>
						<div class="clearfix">

						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Nome do empreendimento:</label>
									<input type="text" name="nmEmpreendimento" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Cliente:</label>
									<select class="select2 form-control" name="cdCliente" data-live-search="true">
										<option value=""></option>
										<?php $cliente->listOption(); ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<div class="form-line">
									<label>Porte:</label>
									<select class="select2 form-control" name="cdPorteEmpreendimento" data-live-search="true">
										<option value=""></option>
										<?php $porteEmpre->listOption(); ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<div class="form-line">
									<label>Tipografia:</label>
									<select class="select2 form-control" name="cdTipografia" data-live-search="true">
										<option value=""></option>
										<?php $tipografia->ListarOption(); ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<div class="form-line">
									<label>Potencial Poluidor:</label>
									<select class="select2 form-control" name="cdPotencialPoluidor" data-live-search="true">
										<option value=""></option>
										<?php $pPoluidor->listOption(); ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

							<label>Dormitórios:</label>
							<div class="input-group spinner" data-trigger="spinner">
								<div class="form-line">
									<input type="text" name="qtdDormitorios" class="form-control text-center" value="0" data-rule="quantity" data-min="0">
								</div>
								<span class="input-group-addon">
									<a href="javascript:void(0)" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
									<a href="javascript:void(0)" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
								</span>
							</div>

						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

							<label>Banheiros:</label>
							<div class="input-group spinner" data-trigger="spinner">
								<div class="form-line">
									<input type="text" name="qtdBanheiros" class="form-control text-center" value="0" data-rule="quantity" data-min="0">
								</div>
								<span class="input-group-addon">
									<a href="javascript:void(0)" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
									<a href="javascript:void(0)" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
								</span>
							</div>

						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

							<label>Unidades:</label>
							<div class="input-group spinner" data-trigger="spinner">
								<div class="form-line">
									<input type="text" name="qtdUnidades" class="form-control text-center" value="0" data-rule="quantity" data-min="0">
								</div>
								<span class="input-group-addon">
									<a href="javascript:void(0)" class="spin-up" data-spin="up"><i class="glyphicon glyphicon-chevron-up"></i></a>
									<a href="javascript:void(0)" class="spin-down" data-spin="down"><i class="glyphicon glyphicon-chevron-down"></i></a>
								</span>
							</div>

						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<div class="form-line">
									<label>Tamanho das Unidades:</label>
									<input type="text" name="dsTamanhoUnidade" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">

							<div class="form-group">
								<div class="form-line">
									<label>Abastecimento:</label>
									<select class="select2 form-control show-tick" data-live-search="true" name="cdAbastecimento[]" multiple>
										<?php cAbastecimento::ListarOption(); ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<div class="form-line">
									<label>Outro tipo de abastecimento:</label>
									<textarea name="dsAbastecimento" class="form-control" rows="3"></textarea>
								</div>
							</div>

						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

							<label>Outorga?</label>
							<div class="switch">
								<label><input type="checkbox" name="snOutorgaAbastecimento" value="S"><span class="lever switch-col-green"></span></label>
							</div>

						</div>
						<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">

							<div class="form-group">
								<div class="form-line">
									<label>Tratamento de Afluentes:</label>
									<select class="select2 form-control show-tick" data-live-search="true" name="cdTratamento[]" multiple>
										<?php cTratamento::ListarOption(); ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<div class="form-line">
									<label>Outro tipo de tratamento:</label>
									<textarea name="dsTratamentoAfluentes" class="form-control" rows="3"></textarea>
								</div>
							</div>

						</div>
						<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">

							<div class="form-group">
								<label>Outorga?</label>
								<div class="switch">
									<label><input type="checkbox" name="snOutorgaTratamento" value="S"><span class="lever switch-col-green"></span></label>
								</div>
							</div>

						</div>
						<div class="clearfix">

						</div>
						<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
							<div class="form-group">
								<label>Haverá terraplanagem?</label>
								<div class="switch">
									<label><input type="checkbox" name="snTerraplanagem" value="S"><span class="lever switch-col-green"></span></label>
								</div>
							</div>

						</div>
						<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">

							<div class="form-group">
								<label>Haverá supresão/erradicação?</label>
								<div class="switch">
									<label><input type="checkbox" name="snSuspensaoErradicacao" value="S"><span class="lever switch-col-green"></span></label>
								</div>
							</div>

						</div>
						<div class="clearfix">

						</div>
						<div class="col-xs-2">
							<div class="form-group">
								<div class="form-line">
									<label>CEP:</label>
									<input type="text" name="cdCep" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group">
								<div class="form-line">
									<label>Endereço:</label>
									<input type="text" id="rua" name="dsEndereco" class="form-control autoRua">
								</div>
							</div>
						</div>
						<div class="col-xs-2">
							<div class="form-group">
								<div class="form-line">
									<label>Bairro:</label>
									<input type="text" id="bairro" name="nmBairro" class="form-control autoBairro">
								</div>
							</div>
						</div>
						<div class="col-xs-2">
							<div class="form-group">
								<div class="form-line">
									<label>Cidade:</label>
									<input type="text" id="cidade" name="nmCidade" class="form-control autoCidade">
								</div>
							</div>
						</div>
						<div class="col-xs-2">
							<div class="form-group">
								<div class="form-line">
									<label>UF:</label>
									<input type="text" id="uf" name="uf" class="form-control autoUf">
								</div>
							</div>
						</div>

						<div class="col-sm-12">
							<div class="form-group">
								<div class="form-line">
									<label>Descrição:</label>
									<textarea class="form-control" name="dsEmpreendimento"></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div role="tabpanel">

					<!-- Nav tabs -->
					<ul class="nav nav-tabs tab-col-deep-purple" role="tablist">
						<li role="presentation" class="active">
							<a href="#tabAlterAreaEmpreendimento" aria-controls="tabAlterAreaEmpreendimento" role="tab" data-toggle="tab"><i class="material-icons col-deep-purple">landscape</i> Área do empreendimento</a>
						</li>
						<li role="presentation">
							<a href="#tabAlterRevisaoEmpreendimento" aria-controls="tabAlterRevisaoEmpreendimento" role="tab" data-toggle="tab"><i class="material-icons col-deep-purple">wrap_text</i> Revisões</a>
						</li>
						<li role="presentation">
							<a href="#tabAlterContatos" aria-controls="tabAlterContatos" role="tab" data-toggle="tab"><i class="material-icons col-deep-purple">people</i> Contatos</a>
						</li>
						<li role="presentation">
							<a href="#tabAlterHistoricoNomes" aria-controls="tabAlterHistoricoNomes" role="tab" data-toggle="tab"><i class="material-icons col-deep-purple">description</i> Histórico de Nomes</a>
						</li>
					</ul>
					<!-- Tab panes -->
					<div class="tab-content" style="padding: 0 0 10px 0;">
						<div role="tabpanel" class="tab-pane active" id="tabAlterAreaEmpreendimento">
							<div class="mdc-bg-grey-100 p-a-10 text-right">

								<button type="button" class="btn bg-green btn-sm waves-effect" onclick="addTpAreaEmpreendimento('a')"><i class="material-icons">add</i>&nbsp; Adicionar</button>

							</div>
							<br />
							<table class="table">
								<thead>
									<tr>
										<th>Tipo da Área</th>
										<th>Valor</th>
										<th width="30px">Remover</th>
									</tr>
								</thead>
								<tbody id="listAlterTpArea">

								</tbody>
							</table>
						</div>

						<div role="tabpanel" class="tab-pane" id="tabAlterRevisaoEmpreendimento">
							<div class="mdc-bg-grey-100 p-a-10 text-right">

								<button type="button" class="btn bg-green btn-sm waves-effect" onclick="addTipoRevisao('a')"><i class="material-icons">add</i>&nbsp; Adicionar</button>

							</div>
							<br />
							<table class="table">
								<thead>
									<tr>
										<th>Item de Revisão</th>
										<th>Valor</th>
										<th width="30px">Remover</th>
									</tr>
								</thead>
								<tbody id="listAlterRevisao">
									<tr class="infoTableRevisao">
										<td colspan="5" class="text-center col-deep-purple">
											<i class="material-icons">info</i><br> Nenhum item de revisão foi inserido
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- FIM TAB REVISOES -->


						<div role="tabpanel" class="tab-pane" id="tabAlterContatos">
							<div class="mdc-bg-grey-100 p-a-10 text-right">

								<button type="button" class="btn bg-green btn-sm waves-effect" onclick="addContatoEmpreendimento('a')"><i class="material-icons">add</i>&nbsp; Adicionar</button>

							</div>
							<br />
							<table class="table">
								<thead>
									<tr>
										<th>Nome do contato</th>
										<th>Departamento</th>
										<th>Cargo</th>
										<th>Telefone</th>
										<th width="30px">Remover</th>
									</tr>
								</thead>
								<tbody id="listAlterContato">
									<tr class="infoTableContato">
										<td colspan="5" class="text-center col-deep-purple">
											<i class="material-icons">info</i><br> Nenhum contato foi inserido
										</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div role="tabpanel" class="tab-pane" id="tabAlterHistoricoNomes">
							<table class="table">
								<thead>
									<tr>
										<th>Nome Antigo</th>
										<th>Modificado Para</th>
										<th>Data da Modificação</th>
										<th>Modificado Por:</th>
									</tr>
								</thead>
								<tbody id="listAlterHistoricoNome">
									<tr class="infoTableContato">
										<td colspan="5" class="text-center col-deep-purple">
											<i class="material-icons">info</i><br> Nenhum contato foi inserido
										</td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="material-icons col-red">close</i> Fechar</button>
						<button type="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i> Salvar</button>
					</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	$.AdminBSB.input.activate();
	$.AdminBSB.select.activate();

	$("#formAlterRevisaoEmpre").hide();

	$(".spinner")
		.spinner('delay', 200) //delay in ms
		.spinner('changed', function(e, newVal, oldVal) {
			// trigger lazed, depend on delay option.
		})
		.spinner('changing', function(e, newVal, oldVal) {
			// trigger immediately
		});

	var path = '<?php echo $nmArquivo; ?>';

	function refresh(p) {
		$("#divConteudo").load("view/" + p);
		$("div.overlay").trigger('click');
	}

	$('.js-basic-example').DataTable({
		responsive: true,
		"columnDefs": [{
				"type": "num",
				"targets": 0
			},
			{
				"type": "html",
				"targets": 1
			}
		]
	});

	$(document).ready(function() {
		$("div.form-line.focused").removeClass("focused");
	});

	$("#formCadEmpreendimento").validate({
		rules: {
			nmEmpreendimento: "required",
			cdCliente: "required",
			dsEmpreendimento: "required"
		},
		messages: {
			nmEmpreendimento: "O nome do empreendimento não pode ser vazio",
			cdCliente: "Você precisa selecionar um cliente",
			dsEmpreendimento: "Escreva uma breve descrição sobre o empreendimento"
		},
		highlight: function(input) {
			$(input).parents('.form-line').addClass('error');
		},
		unhighlight: function(input) {
			$(input).parents('.form-line').removeClass('error');
		},
		errorPlacement: function(error, element) {
			$(element).parents('.form-group').append(error);
		},
		success: "valid",
		submitHandler: function(form) {

			$.ajax({
					url: 'action/g_cadEmpreendimento.php',
					type: 'POST',
					data: $(form).serialize(),
					success: function(data) {
						$("#divResult").html(data);
						$("#modalFormCadEmpreendimento").modal("hide");
						setTimeout(function() {
							refresh(path);
						}, 1500);
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

			return false;
		}
	});

	$("#formAlterEmpreendimento").validate({
		rules: {
			cdEmpreendimento: "required",
			nmEmpreendimento: "required",
			cdCliente: "required",
			snAtivo: "required",
			dsEmpreendimento: "required"
		},
		messages: {
			cdEmpreendimento: "O código do empreendimento não pode ser vazio",
			nmEmpreendimento: "O nome do empreendimento não pode ser vazio",
			cdCliente: "Você precisa selecionar um cliente",
			snAtivo: "Você tem que escolher o status do empreendimento",
			dsEmpreendimento: "Escreva uma breve descrição sobre o empreendimento"
		},
		highlight: function(input) {
			$(input).parents('.form-line').addClass('error');
		},
		unhighlight: function(input) {
			$(input).parents('.form-line').removeClass('error');
		},
		errorPlacement: function(error, element) {
			$(element).parents('.form-group').append(error);
		},
		success: "valid",
		submitHandler: function(form) {

			$.ajax({
					url: 'action/g_alterEmpreendimento.php',
					type: 'POST',
					data: $(form).serialize(),
					success: function(data) {
						$("#divResult").html(data);
						$("#modalFormAlterEmpreendimento").modal("hide");
						setTimeout(function() {
							refresh(path);
						}, 1500);
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

			return false;
		}
	});

	function preencheFormAlterEmpreendimento(empre) {

		console.log(empre);

		$("#formAlterEmpreendimento input[name=cdEmpreendimento]").val(empre.cd_empreendimento);
		$("#formAlterEmpreendimento input[name=nmEmpreendimento]").val(empre.nm_empreendimento);
		$("#formAlterEmpreendimento select[name=cdCliente]").val(empre.encode_cd_cliente).trigger("change");
		$("#formAlterEmpreendimento select[name=cdPotencialPoluidor]").val(empre.encode_cd_potencial_poluidor).trigger("change");
		$("#formAlterEmpreendimento select[name=cdTipografia]").val(empre.encode_cd_tipografia).trigger("change");
		$("#formAlterEmpreendimento select[name=cdPorteEmpreendimento]").val(empre.encode_cd_porte_empreendimento).trigger("change");
		$("#formAlterEmpreendimento input[name=dsArea]").val(empre.ds_area);
		$("#formAlterEmpreendimento input[name=cdCep]").val(empre.cd_cep);
		$("#formAlterEmpreendimento input[name=dsEndereco]").val(empre.ds_endereco);
		$("#formAlterEmpreendimento input[name=nmBairro]").val(empre.nm_bairro);
		$("#formAlterEmpreendimento input[name=nmCidade]").val(empre.nm_cidade);
		$("#formAlterEmpreendimento input[name=uf]").val(empre.uf);
		$("#formAlterEmpreendimento textarea[name=dsEmpreendimento]").val(empre.ds_empreendimento);
		$("#formAlterEmpreendimento input[name=qtdDormitorios]").val(empre.qtd_domitorios);
		$("#formAlterEmpreendimento input[name=qtdBanheiros]").val(empre.qtd_banheiros);
		$("#formAlterEmpreendimento input[name=qtdUnidades]").val(empre.qtd_unidades);
		$("#formAlterEmpreendimento input[name=dsTamanhoUnidade]").val(empre.ds_tamanho_unidade);
		$("#formAlterEmpreendimento textarea[name=dsAbastecimento]").val(empre.ds_outro_tipo_abastecimento);
		$("#formAlterEmpreendimento textarea[name=dsTratamentoAfluentes]").val(empre.ds_outro_tipo_tratamento_afluente);
		(empre.terraplanagem == '1') ? $("#formAlterEmpreendimento input[name=snTerraplanagem]").prop('checked', 'checked'): $("#formAlterEmpreendimento input[name=snTerraplanagem]").prop('checked', '');
		(empre.suspencao_erradicacao == '1') ? $("#formAlterEmpreendimento input[name=snSuspensaoErradicacao]").prop('checked', 'checked'): $("#formAlterEmpreendimento input[name=snSuspensaoErradicacao]").prop('checked', '');
		(empre.outorga_abastecimento == '1') ? $("#formAlterEmpreendimento input[name=snOutorgaAbastecimento]").prop('checked', 'checked'): $("#formAlterEmpreendimento input[name=snOutorgaAbastecimento]").prop('checked', '');
		(empre.outorga_tratamento == '1') ? $("#formAlterEmpreendimento input[name=snOutorgaTratamento]").prop('checked', 'checked'): $("#formAlterEmpreendimento input[name=snOutorgaTratamento]").prop('checked', '');
		$("#formAlterEmpreendimento select[name=snAtivo]").val(empre.snAtivo).trigger("change");
		$("#formAlterEmpreendimento select[name=snAtivo]").selectpicker('refresh');
		$("#formAlterEmpreendimento select[name=cdPotencialPoluidor]").selectpicker('refresh');
		$("#formAlterEmpreendimento select[name=cdTipografia]").selectpicker('refresh');
		$("#formAlterEmpreendimento select[name=cdPorteEmpreendimento]").selectpicker('refresh');

		$("#viewFormRevisaoEmpre select[name=cdTpEmpreendimento]").val(null).trigger("change");
		$("#viewFormRevisaoEmpre input[name=qtdDormitorios]").val(null);
		$("#viewFormRevisaoEmpre input[name=qtdBanheiros]").val(null);
		$("#viewFormRevisaoEmpre input[name=qtdUnidades]").val(null);
		$("#viewFormRevisaoEmpre textarea[name=dsTamanhoUnidade]").val(null);
		$("#viewFormRevisaoEmpre select[name=cdAbastecimento]").val(null).trigger("change");
		$("#viewFormRevisaoEmpre textarea[name=dsAbastecimento]").val(null);
		$("#viewFormRevisaoEmpre select[name=cdTratamento]").val(null).trigger("change");
		$("#viewFormRevisaoEmpre textarea[name=dsTratamentoAfluentes]").val(null);
		$("#viewFormRevisaoEmpre input[name=snTerraplanagem]").prop('checked', '');
		$("#viewFormRevisaoEmpre input[name=snSuspensaoErradicacao]").prop('checked', '');
		$("#viewFormRevisaoEmpre input[name=snOutorgaAbastecimento]").prop('checked', '');
		$("#viewFormRevisaoEmpre input[name=snOutorgaTratamento]").prop('checked', '');

		$.ajax({
				url: 'action/g_listarAreasEmpreendimento.php',
				type: 'POST',
				data: {
					cdEmpreendimento: empre.cd_empreendimento
				},
				success: function(data) {
					$("#listAlterTpArea").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$.AdminBSB.input.activate();
				$.AdminBSB.select.activate();
				$("div.form-line.focused").removeClass("focused");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		$.ajax({
				url: 'action/g_listarTpRevisaoEmpreendimento.php',
				type: 'POST',
				data: {
					cdEmpreendimento: empre.cd_empreendimento
				},
				success: function(data) {
					$("#listAlterRevisao").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$.AdminBSB.input.activate();
				$.AdminBSB.select.activate();
				$("div.form-line.focused").removeClass("focused");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		$.ajax({
				url: 'action/g_listarContatosEmpreendimento.php',
				type: 'POST',
				data: {
					cdEmpreendimento: empre.cd_empreendimento
				},
				success: function(data) {
					$("#listAlterContato").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$.AdminBSB.input.activate();
				$.AdminBSB.select.activate();
				$("div.form-line.focused").removeClass("focused");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		$.ajax({
				url: 'action/g_listarHistoricoAlteracaoNome.php',
				type: 'POST',
				data: {
					cdEmpreendimento: empre.cd_empreendimento
				},
				success: function(data) {
					$("#listAlterHistoricoNome").html(data);
				}
			})
			.done(function() {
				console.log("success");
				$.AdminBSB.input.activate();
				$.AdminBSB.select.activate();
				$("div.form-line.focused").removeClass("focused");
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

		// ListJs
		var ListOptions = {
			valueNames: ['nm_usuario', 'dh_registro', {
				data: ['cd_rev_empreendimento']
			}],
			// item: '<li class="list-group-item cursorPointer" data-toggle="modal" href="#modalAlterAtividade" onclick="viewFormAlterAtividade(this)"><p class="tp_status pull-right"></p><h4 class="ds_tp_atividade col-deep-purple"></h4><h5 class="ds_atividade"></h5><ul class="list-inline list-unstyled font-12"><li><strong>Início:</strong></li><li class="dh_registro"></li><li><strong>Previsão:</strong></li><li class="dt_prev_entrega"></li><li><strong>Responsável:</strong></li><li class="nm_usuario"></li></ul></li>'
			item: '<li class="list-group-item cursorPointer m-b-10" data-toggle="modal" href="javascript:void(0)" onclick="carregaDadosRevisao(this)"><h5><span></span> Revisão em: <span class="dh_registro"></span></h5><h5 class="nm_usuario col-deep-purple"></h5></li>'
		};

		var ListValues;
		var revisaoList;

		$("#formEditListRev ul.list").empty();

		$.ajax({
				url: 'action/eco_returnJsonRevisoes.php',
				type: 'GET',
				datatype: 'json',
				data: {
					cdEmpreendimento: empre.cd_empreendimento
				},
				success: function(data) {
					ListValues = data;
				}
			})
			.done(function() {
				// console.log("Lista de revisoes carregada!");
				revisaoList = new List('formEditListRev', ListOptions, ListValues);
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});

	}

	function addRevisaoEmpreendimento() {
		$("#viewFormRevisaoEmpre, #formAlterRevisaoEmpre").toggle();
	}

	function carregaDadosRevisao(r) {
		let cdRev = $(r).data('cd_rev_empreendimento');
		let dados;

		$.ajax({
				url: 'action/eco_returnJsonRevisoes.php',
				type: 'GET',
				datatype: 'json',
				data: {
					cdRevEmpreendimento: cdRev
				},
				success: function(data) {
					dados = data;
					// console.log(dados);
					// console.log(dados[0].ds_tratamento_afluente);
				}
			})
			.done(function() {
				$("#viewFormRevisaoEmpre select[name=cdTpEmpreendimento]").val(dados[0].encode_cd_tp_empreendimento).trigger("change");
				$("#viewFormRevisaoEmpre select[name='cdAbastecimento[]']").val(dados[0].abastecimento).trigger("change");
				$("#viewFormRevisaoEmpre select[name='cdTratamento[]']").val(dados[0].tratamento).trigger("change");
				$("#viewFormRevisaoEmpre select[name='cdAbastecimento[]'], #viewFormRevisaoEmpre select[name='cdTratamento[]']").selectpicker('refresh');
				$("#viewFormRevisaoEmpre input[name=qtdDormitorios]").val(dados[0].nr_dormitorios);
				$("#viewFormRevisaoEmpre input[name=qtdBanheiros]").val(dados[0].nr_banheiros);
				$("#viewFormRevisaoEmpre input[name=qtdUnidades]").val(dados[0].nr_unidades);
				$("#viewFormRevisaoEmpre textarea[name=dsTamanhoUnidade]").val(dados[0].ds_tamanho_unidades);
				$("#viewFormRevisaoEmpre textarea[name=dsAbastecimento]").val(dados[0].ds_abastecimento);
				$("#viewFormRevisaoEmpre textarea[name=dsTratamentoAfluentes]").val(dados[0].ds_tratamento_afluente);
				(dados[0].sn_outorga_abastecimento == 'S') ? $("#viewFormRevisaoEmpre input[name=snOutorgaAbastecimento]").prop('checked', 'checked'): $("#viewFormRevisaoEmpre input[name=snOutorgaAbastecimento]").prop('checked', '');
				(dados[0].sn_outorga_tratamento == 'S') ? $("#viewFormRevisaoEmpre input[name=snOutorgaTratamento]").prop('checked', 'checked'): $("#viewFormRevisaoEmpre input[name=snOutorgaTratamento]").prop('checked', '');
				(dados[0].sn_terraplanagem == 'S') ? $("#viewFormRevisaoEmpre input[name=snTerraplanagem]").prop('checked', 'checked'): $("#viewFormRevisaoEmpre input[name=snTerraplanagem]").prop('checked', '');
				(dados[0].sn_suspensao_erradicacao == 'S') ? $("#viewFormRevisaoEmpre input[name=snSuspensaoErradicacao]").prop('checked', 'checked'): $("#viewFormRevisaoEmpre input[name=snSuspensaoErradicacao]").prop('checked', '');
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
	}


	//Quando o campo cep perde o foco.
	$("input[name=cdCep]").blur(function() {
		var form = $(this).closest("form").prop("id");

		//Nova variável "cep" somente com dígitos.
		var cep = $(this).val().replace(/\D/g, '');

		//Verifica se campo cep possui valor informado.
		if (cep != "") {

			//Expressão regular para validar o CEP.
			var validacep = /^[0-9]{8}$/;

			//Valida o formato do CEP.
			if (validacep.test(cep)) {

				//Preenche os campos com "..." enquanto consulta webservice.
				$("#" + form + " .autoRua").val("...");
				$("#" + form + " .autoBairro").val("...");
				$("#" + form + " .autoCidade").val("...");
				$("#" + form + " .autoUf").val("...");

				//Consulta o webservice viacep.com.br/
				$.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {

					if (!("erro" in dados)) {
						//Atualiza os campos com os valores da consulta.
						$("#" + form + " .autoRua").val(dados.logradouro);
						$("#" + form + " .autoBairro").val(dados.bairro);
						$("#" + form + " .autoCidade").val(dados.localidade);
						$("#" + form + " .autoUf").val(dados.uf);
					} //end if.
					else {
						//CEP pesquisado não foi encontrado.
						alert("CEP não encontrado.");
					}
				});
			} //end if.
			else {
				//cep é inválido.
				alert("Formato de CEP inválido.");
			}
		} //end if.
		else {
			//cep sem valor, limpa formulário.
		}
	});

	function addTpAreaEmpreendimento(tp) {
		var element = (tp == 'c') ? $("#listCadTpArea") : $("#listAlterTpArea");

		$("#infoTableTpArea").remove();
		element.append('<tr><td><div class="form-group"><div class="form-line"><select class="select2 form-control" name="cdTpArea[]"><option value=""></option><?php $tpArea->listOption(); ?></select></div></div></td><td><div class="form-group"><div class="form-line"><input type="text" class="form-control" name="vlArea[]" placeholder="Ex: 1000" /></div></div></td><td class="text-center"><a href="javascript:void(0)" onclick="removerArea(this)"><i class="material-icons col-red">delete</i>&nbsp;</a></td></tr>');
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
		$("div.form-line.focused").removeClass("focused");
	}

	function addContatoEmpreendimento(tp) {

		var element = (tp == 'c') ? $("#listCadContato") : $("#listAlterContato");

		element.find(".infoTableContato").remove();
		element.append('<tr><td><input type="hidden" name="cdEmpreendimentoContato[]"/><div class="form-group"><div class="form-line"><input type="text" class="form-control" name="nmContato[]" placeholder="Ex: José" /></div></div></td><td><div class="form-group"><div class="form-line"><input type="text" class="form-control" name="nmDepartamento[]" placeholder="Ex: Recursos Humanos" /></div></div></td><td><div class="form-group"><div class="form-line"><input type="text" class="form-control" name="nmCargo[]" placeholder="Ex: Analista" /></div></div></td><td><div class="form-group"><div class="form-line"><input type="text" class="form-control" name="nrTelefone[]" placeholder="Ex: (81) 99999-9999" /></div></div></td><td class="text-center"><a href="javascript:void(0)" onclick="removerContato(this)"><i class="material-icons col-red">delete</i>&nbsp;</a></td></tr>');
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
		$("div.form-line.focused").removeClass("focused");
	}

	function addTipoRevisao(tp) {

		var element = (tp == 'c') ? $("#listCadRevisao") : $("#listAlterRevisao");

		element.find(".infoTableRevisao").remove();
		element.append('<tr><td><input type="hidden" name="cdEmpreendimentoRevisao[]"/><div class="form-group"><div class="form-line"><select class="form-control" name="cdTpRevisao[]"><option value=""></option><?php $tpRevisao->listOption(); ?></select></div></div></td><td><div class="form-group"><div class="form-line"><input type="text" class="form-control" name="vlRevisao[]" placeholder="Ex: 1" /></div></div></td><td class="text-center"><a href="javascript:void(0)" onclick="removerRevisao(this)"><i class="material-icons col-red">delete</i>&nbsp;</a></td></tr>');
		$.AdminBSB.input.activate();
		$.AdminBSB.select.activate();
		$("div.form-line.focused").removeClass("focused");
	}


	function removerArea(a) {
		$(a).closest("tr").remove();
	}

	function removerContato(a) {
		$(a).closest("tr").remove();
	}

	function removerRevisao(a) {
		$(a).closest("tr").remove();
	}
</script>