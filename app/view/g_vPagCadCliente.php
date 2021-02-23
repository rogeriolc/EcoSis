<?php
include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cCliente  = new cCliente;

$nmArquivo = basename($_SERVER['PHP_SELF']);

?>
<div class="container-fluid">
    <div class="col-white">
        <h4>Cadastro de Cliente</h4>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Lista de Clientes
                    </h2>
                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a data-toggle="modal" href="#modalFormCadCliente" class="waves-effect waves-block">
                                    <i class="fas fa-user-plus"></i> Adicionar
                                </a>
                            </li>
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
                                <th class="col-md-4">Nome do cliente</th>
                                <th>CPF/CNPJ</th>
                                <th>Telefones</th>
                                <th width="50px">Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th width="30px">Código</th>
                                <th>Nome do cliente</th>
                                <th>CPF/CNPJ</th>
                                <th>Telefones</th>
                                <th width="50px">Status</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $cCliente->listTable(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<div class="modal fade" id="modalFormCadCliente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formCadCliente" method="POST">
                <div class="modal-header bg-deep-purple">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">
                        <i class="fas fa-lg fa-user-plus"></i> &nbsp;Formulário de Cadastro de Clientes
                    </h4>
                </div>
                <div class="modal-body">

                    <div role="tabpanel">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tab-col-deep-purple" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tabCadCliente" aria-controls="tabCadCliente" role="tab" data-toggle="tab">Dados do cliente</a>
                            </li>
                            <li role="presentation">
                                <a href="#tabCadRepresentante" aria-controls="tabCadRepresentante" role="tab" data-toggle="tab">Dados do Representante Legal</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="tabCadCliente">
                                <div class="row clearfix">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Nome:</label>
                                                <input type="text" name="nmCliente" class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Pessoa:</label>
                                                <select name="tpPessoa" class="form-control show-tick" data-live-search="true">
                                                    <option value="FÍSICA">Física</option>
                                                    <option value="JURÍDICA">Jurídica</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>RG:</label>
                                                <input type="text" name="nrRg" class="form-control inputRg">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>CPF/CNPJ:</label>
                                                <input type="text" name="cpfCnpj" class="form-control inputCpfCnpj">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Inscrição Estadual:</label>
                                                <input type="text" name="nrInscricaoEstadual" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Inscrição Municipal:</label>
                                                <input type="text" name="nrInscricaoMunicipal" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Cadastro Técnico Federal:</label>
                                                <input type="text" name="dsCtf" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Telefone:</label>
                                                <input type="text" name="nrTelefone" class="form-control inputPhone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Celular:</label>
                                                <input type="text" name="nrCelular" class="form-control inputCelPhone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>E-mail:</label>
                                                <input type="text" name="dsEmail" class="form-control inputEmail">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Site:</label>
                                                <input type="text" name="dsSite" class="form-control">
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
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tabCadRepresentante">
                                <div class="row clearfix">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Representante Legal:</label>
                                                <input type="text" name="nmRepresentanteLegal" class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Pessoa:</label>
                                                <select name="tpPessoaRepresentante" class="form-control show-tick" data-live-search="true">
                                                    <option value="FÍSICA">Física</option>
                                                    <option value="JURÍDICA">Jurídica</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>RG:</label>
                                                <input type="text" name="nrRgRepresentante" class="form-control inputRg">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>CPF/CNPJ:</label>
                                                <input type="text" name="cpfCnpjRepresentante" class="form-control inputCpfCnpj">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Inscrição Estadual:</label>
                                                <input type="text" name="nrInscricaoEstadualRepresentante" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Inscrição Municipal:</label>
                                                <input type="text" name="nrInscricaoMunicipalRepresentante" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Cadastro Técnico Federal:</label>
                                                <input type="text" name="dsCtfRepresentante" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Telefone:</label>
                                                <input type="text" name="nrTelefoneRepresentante" class="form-control inputPhone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Celular:</label>
                                                <input type="text" name="nrCelularRepresentante" class="form-control inputCelPhone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>E-mail:</label>
                                                <input type="text" name="dsEmailRepresentante" class="form-control inputEmail">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Site:</label>
                                                <input type="text" name="dsSiteRepresentante" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>CEP:</label>
                                                <input type="text" name="cdCepRepresentante" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Endereço:</label>
                                                <input type="text" name="dsEnderecoRepresentante" class="form-control autoRua">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Bairro:</label>
                                                <input type="text" name="nmBairroRepresentante" class="form-control autoBairro">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Cidade:</label>
                                                <input type="text" name="nmCidadeRepresentante" class="form-control autoCidade">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>UF:</label>
                                                <input type="text" name="ufRepresentante" class="form-control autoUf">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i
                        class="material-icons">close</i> Fechar
                    </button>
                    <button type="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFormAlterCliente">
    <form id="formAlterCliente">
        <input type="hidden" name="cdCliente" />
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-deep-purple">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="material-icons pull-left" style="margin-top: -3px; padding-right: 5px;">add</i> Formulário de
                    Alteração de Cliente</h4>
                </div>
                <div class="modal-body">
                    <div role="tabpanel">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tab-col-deep-purple" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tabAlterCliente" aria-controls="tabAlterCliente" role="tab" data-toggle="tab">Dados do cliente</a>
                            </li>
                            <li role="presentation">
                                <a href="#tabAlterRepresentante" aria-controls="tabAlterRepresentante" role="tab" data-toggle="tab">Dados do Representante Legal</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="tabAlterCliente">
                                <div class="row clearfix">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Nome:</label>
                                                <input type="text" name="nmCliente" class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Pessoa:</label>
                                                <select name="tpPessoa" class="form-control show-tick" data-live-search="true">
                                                    <option value="FÍSICA">Física</option>
                                                    <option value="JURÍDICA">Jurídica</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>RG:</label>
                                                <input type="text" name="nrRg" class="form-control inputRg">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>CPF/CNPJ:</label>
                                                <input type="text" name="cpfCnpj" class="form-control inputCpfCnpj">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Inscrição Estadual:</label>
                                                <input type="text" name="nrInscricaoEstadual" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Inscrição Municipal:</label>
                                                <input type="text" name="nrInscricaoMunicipal" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Cadastro Técnico Federal:</label>
                                                <input type="text" name="dsCtf" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Telefone:</label>
                                                <input type="text" name="nrTelefone" class="form-control inputPhone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Celular:</label>
                                                <input type="text" name="nrCelular" class="form-control inputCelPhone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>E-mail:</label>
                                                <input type="text" name="dsEmail" class="form-control inputEmail">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Site:</label>
                                                <input type="text" name="dsSite" class="form-control">
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
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tabAlterRepresentante">
                                <div class="row clearfix">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Representante Legal:</label>
                                                <input type="text" name="nmRepresentanteLegal" class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Pessoa:</label>
                                                <select name="tpPessoaRepresentante" class="form-control show-tick" data-live-search="true">
                                                    <option value="FÍSICA">Física</option>
                                                    <option value="JURÍDICA">Jurídica</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>RG:</label>
                                                <input type="text" name="nrRgRepresentante" class="form-control inputRg">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>CPF/CNPJ:</label>
                                                <input type="text" name="cpfCnpjRepresentante" class="form-control inputCpfCnpj">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Inscrição Estadual:</label>
                                                <input type="text" name="nrInscricaoEstadualRepresentante" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Inscrição Municipal:</label>
                                                <input type="text" name="nrInscricaoMunicipalRepresentante" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Cadastro Técnico Federal:</label>
                                                <input type="text" name="dsCtfRepresentante" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Telefone:</label>
                                                <input type="text" name="nrTelefoneRepresentante" class="form-control inputPhone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Celular:</label>
                                                <input type="text" name="nrCelularRepresentante" class="form-control inputCelPhone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>E-mail:</label>
                                                <input type="text" name="dsEmailRepresentante" class="form-control inputEmail">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Site:</label>
                                                <input type="text" name="dsSiteRepresentante" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix">

                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>CEP:</label>
                                                <input type="text" name="cdCepRepresentante" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Endereço:</label>
                                                <input type="text" name="dsEnderecoRepresentante" class="form-control autoRua">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Bairro:</label>
                                                <input type="text" name="nmBairroRepresentante" class="form-control autoBairro">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>Cidade:</label>
                                                <input type="text" name="nmCidadeRepresentante" class="form-control autoCidade">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <label>UF:</label>
                                                <input type="text" name="ufRepresentante" class="form-control autoUf">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- fim tabs -->
                        <div class="row">
                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
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
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i
                        class="material-icons">close</i> Fechar
                    </button>
                    <button type="submit" class="btn bg-green waves-effect"><i class="material-icons">save</i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $.AdminBSB.input.activate();
    $.AdminBSB.select.activate();

    var path = '<?php echo $nmArquivo;?>';

    function refresh(p){
        $("#divConteudo").load("view/"+p);
        $("div.overlay").trigger('click');
    }

    $('.js-basic-example').DataTable({
        responsive: true,
        "columnDefs": [
            { "type": "num", "targets": 0 }
        ]
    });

    $(document).ready(function () {
        $("div.form-line.focused").removeClass("focused");
    });

    $(".inputMoney").maskMoney({prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: false});

    $(".inputPhone").inputmask('(99) 9999-9999', {placeholder: '(__) ____-____'});
    $(".inputCelPhone").inputmask({mask: '(99) 99999-9999', placeholder: '(__) _____-____', greedy: false});
    $(".inputAgencia").inputmask({mask: '9999[-9]|[-a]', greedy: false});
    $(".inputConta").inputmask({mask: '99999[9]|[a][9999]', greedy: false});
    $(".inputCpfCnpj").inputmask({
        mask: ['999.999.999-99', '99.999.999/9999-99'],
        keepStatic: true
    });
    $(".inputRg").inputmask({mask: '9.999-999', greedy: false});
    $('.inputEmail').inputmask({alias: "email"});

    $("#formCadCliente").validate({
        rules: {
            nmCliente: "required"
            // cpfCnpj: "required"
        },
        messages: {
            nmCliente: "O nome do cliente não pode ser vazio"
            // cpfCnpj: "O cpf/cnpj não pode ser vazio"
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
        submitHandler: function (form) {

            $.ajax({
                url: 'action/g_cadCliente.php',
                type: 'POST',
                data: $(form).serialize(),
                success: function (data) {
                    $("#divResult").html(data);
                }
            })
            .done(function () {
                console.log("success");
                $("#modalFormCadCliente").modal("hide");
                setTimeout(function(){
                    refresh(path);
                },1500);
            })
            .fail(function () {
                console.log("error");
            })
            .always(function () {
                console.log("complete");
            });

            return false;
        }
    });

    $("#formAlterCliente").validate({
        rules: {
            cdCliente: "required",
            nmCliente: "required",
            snAtivo: "required"
        },
        messages: {
            cdCliente: "O código do cliente não pode ser vazio",
            nmCliente: "O nome do cliente não pode ser vazio",
            snAtivo: "O status do cadastro não pode ser vazio"
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
        submitHandler: function (form) {

            $.ajax({
                url: 'action/g_alterCliente.php',
                type: 'POST',
                data: $(form).serialize(),
                success: function (data) {
                    $("#divResult").html(data);
                }
            })
            .done(function () {
                console.log("success");
                $("#modalFormAlterCliente").modal("hide");
                setTimeout(function(){
                    refresh(path);
                },1500);
            })
            .fail(function () {
                console.log("error");
            })
            .always(function () {
                console.log("complete");
            });

            return false;
        }
    });


    function preencheFormAlterCliente(c, n, pes, rg, cpf, isEsta, isMuni, ctf, tel, cel, e, site, cep, end, bar, cid, uf, sn) {
        $("#formAlterCliente input[name=cdCliente]").val(c);
        $("#formAlterCliente input[name=nmCliente]").val(n);
        $("#formAlterCliente select[name=tpPessoa]").val(pes).trigger("change");
        $("#formAlterCliente input[name=nrRg]").val(rg);
        $("#formAlterCliente input[name=cpfCnpj]").val(cpf);
        $("#formAlterCliente input[name=nrInscricaoEstadual]").val(isEsta);
        $("#formAlterCliente input[name=nrInscricaoMunicipal]").val(isMuni);
        $("#formAlterCliente input[name=dsCtf]").val(ctf);
        $("#formAlterCliente input[name=nrTelefone]").val(tel);
        $("#formAlterCliente input[name=nrCelular]").val(cel);
        $("#formAlterCliente input[name=dsEmail]").val(e);
        $("#formAlterCliente input[name=dsSite]").val(site);
        $("#formAlterCliente input[name=cdCep]").val(cep);
        $("#formAlterCliente input[name=dsEndereco]").val(end);
        $("#formAlterCliente input[name=nmBairro]").val(bar);
        $("#formAlterCliente input[name=nmCidade]").val(cid);
        $("#formAlterCliente input[name=uf]").val(uf);
        $("#formAlterCliente select[name=snAtivo]").val(sn).trigger("change");
    }


    function preencheFormAlterClienteResp(n, pes, rg, cpf, isEsta, isMuni, ctf, tel, cel, e, site, cep, end, bar, cid, uf) {
        $("#formAlterCliente input[name=nmRepresentanteLegal]").val(n);
        $("#formAlterCliente select[name=tpPessoaRepresentante]").val(pes).trigger("change");
        $("#formAlterCliente input[name=nrRgRepresentante]").val(rg);
        $("#formAlterCliente input[name=cpfCnpjRepresentante]").val(cpf);
        $("#formAlterCliente input[name=nrInscricaoEstadualRepresentante]").val(isEsta);
        $("#formAlterCliente input[name=nrInscricaoMunicipalRepresentante]").val(isMuni);
        $("#formAlterCliente input[name=dsCtfRepresentante]").val(ctf);
        $("#formAlterCliente input[name=nrTelefoneRepresentante]").val(tel);
        $("#formAlterCliente input[name=nrCelularRepresentante]").val(cel);
        $("#formAlterCliente input[name=dsEmailRepresentante]").val(e);
        $("#formAlterCliente input[name=dsSiteRepresentante]").val(site);
        $("#formAlterCliente input[name=cdCepRepresentante]").val(cep);
        $("#formAlterCliente input[name=dsEnderecoRepresentante]").val(end);
        $("#formAlterCliente input[name=nmBairroRepresentante]").val(bar);
        $("#formAlterCliente input[name=nmCidadeRepresentante]").val(cid);
        $("#formAlterCliente input[name=ufRepresentante]").val(uf);
    }

    function limpa_formulário_cep() {
        // Limpa valores do formulário de cep.
        $(".autoRua").val("");
        $(".autoBairro").val("");
        $(".autoCidade").val("");
        $(".autoUf").val("");
    }

    //Quando o campo cep perde o foco.
    $("input[name=cdCep]").blur(function () {

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
                $("#"+form+" .autoRua").val("...");
                $("#"+form+" .autoBairro").val("...");
                $("#"+form+" .autoCidade").val("...");
                $("#"+form+" .autoUf").val("...");

                //Consulta o webservice viacep.com.br/
                $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#"+form+" .autoRua").val(dados.logradouro);
                        $("#"+form+" .autoBairro").val(dados.bairro);
                        $("#"+form+" .autoCidade").val(dados.localidade);
                        $("#"+form+" .autoUf").val(dados.uf);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    });

</script>