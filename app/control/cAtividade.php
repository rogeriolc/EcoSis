<?php
// include_once('conf/interfaceBase.php');
/**
* Control Atividade
*/
class cAtividade extends mAtividade
{

	function __construct($cdAtividade=null, $cdServico=null, $dsAtividade=null, $tpAtividade=null, $cdUsuarioResponsavel=null, $dtPrevEntrega=null, $cdTpAtividade=null, $nrOrdem=null){

		parent::__construct($cdAtividade, $dsAtividade, $tpAtividade, $dtPrevEntrega, $cdUsuarioResponsavel);
		parent::setCdServico($cdServico);
		parent::setCdTpAtividade($cdTpAtividade);
		parent::setNrOrdem($nrOrdem);

	}

	//Realiza o cadastro
	public function Cadastrar(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "INSERT INTO `eco_atividade`(`cd_servico`,`ds_atividade`,`cd_tp_atividade`,`tp_atividade`, `cd_orgao_licenciado`, `nr_processo`,`cd_usuario`,`dt_prev_entrega`,`cd_empresa`,`cd_usuario_registro`) VALUES (:cdServico, :dsAtividade, :cdTpAtividade, :tpAtividade, :cdOrgaoLicenciado, :nrProcesso, :cdUsuarioResponsavel, :dtPrevEntrega, :cdEmpresa, :cdUsuarioSessao);";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		$stmt->bindParam(":dsAtividade", $this->dsAtividade);
		$stmt->bindParam(":cdTpAtividade", $this->cdTpAtividade);
		$stmt->bindParam(":tpAtividade", $this->tpAtividade);
		$stmt->bindParam(":cdOrgaoLicenciado", $this->cdOrgaoLicenciador);
		$stmt->bindParam(":nrProcesso", $this->nrProcesso);
		$stmt->bindParam(":cdUsuarioResponsavel", $this->cdUsuarioResponsavel);
		$stmt->bindParam(":dtPrevEntrega", $this->dtPrevEntrega);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();
			if($num > 0){
				return intval($mysql->lastInsertId());
			}else{
				return false;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}

	}

	//Altera o cadastro
	public function Alterar(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE `eco_atividade` SET `cd_tp_atividade` = :cdTpAtividade, `ds_atividade` = :dsAtividade, cd_orgao_licenciado = :cdOrgaoLicenciado, nr_processo = :nrProcesso, `cd_usuario` = :cdUsuarioResponsavel, `dt_prev_entrega` = :dtPrevEntrega, nr_ordem = :nrOrdem WHERE cd_atividade = :cdAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":cdTpAtividade", $this->cdTpAtividade);
		$stmt->bindParam(":dsAtividade", $this->dsAtividade);
		$stmt->bindParam(":cdOrgaoLicenciado", $this->cdOrgaoLicenciador);
		$stmt->bindParam(":nrProcesso", $this->nrProcesso);
		$stmt->bindParam(":cdUsuarioResponsavel", $this->cdUsuarioResponsavel);
		$stmt->bindParam(":dtPrevEntrega", $this->dtPrevEntrega);
		$stmt->bindParam(":nrOrdem", $this->nrOrdem);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();

			return $num;

		}else{
			$erro = $stmt->errorInfo();
			return $erro[2];
		}
	}

	//Excluir o cadastro
	public function Excluir(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "DELETE FROM `eco_atividade` WHERE cd_atividade = :cdAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();

			return $num;

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	//Concluir a atividade
	public function Concluir(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE `eco_atividade` SET `tp_status` = 'O', `cd_usuario_conclusao` = :cdUsuarioSessao, `dh_conclusao` = NOW() WHERE cd_atividade = :cdAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();

			return $num;

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	//Reabrir a atividade
	public function Reabrir(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE `eco_atividade` SET `tp_status` = 'R', `cd_usuario_reabertura` = :cdUsuarioSessao, `dh_reabertura` = NOW() WHERE cd_atividade = :cdAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();

			return $num;

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	//Em tramite a atividade
	public function EmTramite(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE `eco_atividade` SET `tp_status` = 'T', `cd_usuario_alteracao` = :cdUsuarioSessao, `dh_alteracao` = NOW() WHERE cd_atividade = :cdAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();

			return $num;

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	//Trabalhar
	public function Trabalhar(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE `eco_atividade` SET `tp_status` = 'E', `cd_usuario_alteracao` = :cdUsuarioSessao, `dh_alteracao` = NOW() WHERE cd_atividade = :cdAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();

			return $num;

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	//Lista os dados em formato de tabela
	public function ListarTable(){
	}

	//Listar os dados em formato de formulário em tabela
	public function ListarTableForm($tpAtividade){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "SELECT cd_atividade, ds_atividade, tp_atividade, cd_orgao_licenciado, nr_processo, cd_usuario, dt_prev_entrega, tp_status FROM `eco_atividade` WHERE cd_servico = :cdServico AND tp_atividade = :tpAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		$stmt->bindParam(":tpAtividade", $tpAtividade);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

					//Configura exibição do menu
					switch ($reg->tp_status) {
						//Concluído
						case 'O':
						$optMenu = '
						<li>
						<a href="javascript:void(0);" onclick="reabrirAtividade(this)" data-cod="'.$reg->cd_atividade.'"  data-codserv="'.$this->cdServico.'" data-tptable="'.$reg->tp_atividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-amber">refresh</i> Reabrir</a>
						</li>
						<li role="separator" class="divider"></li>
						<li>
						<a href="#modalAnexo" data-toggle="modal" onclick="listarAnexos(this)" data-cod="'.$reg->cd_atividade.'"  data-codserv="'.$this->cdServico.'" class="waves-effect waves-block"><i class="material-icons mdc-text-indigo">attach_file</i> Anexos</a>
						</li>';
						$disabled = 'disabled';
						break;

						//Atrasado
						case 'A':
						$optMenu = '<li>
						<a href="javascript:void(0);" onclick="concluirAtividade(this)" data-cod="'.$reg->cd_atividade.'" data-cod="'.$reg->cd_atividade.'"  data-codserv="'.$this->cdServico.'" data-tptable="'.$reg->tp_atividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-green">done</i> Concluir</a>
						</li>
						<li>
						<a href="javascript:void(0);" onclick="removerAtividade(this)" data-cod="'.$reg->cd_atividade.'" data-cod="'.$reg->cd_atividade.'"  data-codserv="'.$this->cdServico.'" data-tptable="'.$reg->tp_atividade.'" class="waves-effect waves-block"><i class="material-icons mdc-text-red">delete</i> Excluir</a>
						</li>
						<li role="separator" class="divider"></li>
						<li>
						<a href="#modalAnexo" data-toggle="modal" onclick="listarAnexos(this)" data-cod="'.$reg->cd_atividade.'" data-codserv="'.$this->cdServico.'" class="waves-effect waves-block"><i class="material-icons mdc-text-indigo">attach_file</i> Anexos</a>
						</li>';
						$disabled = null;
						break;

						default:
						//Reaberta
						case 'R':
						//Em andamento
						case 'E':
						$optMenu = '';
						$disabled = null;
						break;
					}

					echo '
					<tr>
					<td>
					<div class="form-group">
					<div class="form-line">
					<input type="hidden" name="cdAtividade[]" class="inputCdAtividade" value="'.$reg->cd_atividade.'" '.$disabled.' />
					<input type="hidden" name="tpAtividade[]" value="'.$reg->tp_atividade.'" '.$disabled.' />
					<textarea class="form-control no-resize" name="dsAtividade[]" '.$disabled.'>'.$reg->ds_atividade.'</textarea>
					</div>
					</div>
					</td>

					<td>
					<div class="form-group">
					<div class="form-line">
					<select class="form-control" style="width: 100%" name="cdUsuario[]" data-live-search="true" '.$disabled.'>
					';

					cCliente::staticListOption(array(0=>$reg->cd_usuario));

					echo '
					</select>
					</div>
					</div>
					</td>

					<td>
					<div class="form-group">
					<div class="form-line">
					<input class="form-control datepicker" name="dtPrevEntrega[]" value="'.implode("/",array_reverse(explode("-", $reg->dt_prev_entrega))).'" '.$disabled.' />
					</div>
					</div>
					</td>
					<td class="text-center">
					<div class="btn-group">
					<button type="button" class="btn bg-deep-purple dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
					<i class="material-icons">more_vert</i>
					</button>
					<ul class="dropdown-menu dropdown-menu-right">
					'.$optMenu.'
					</ul>
					</div>
					</td>
					</tr>
					';

				}
			}else{
				return 0;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public function returnArrayAtividade(){
		$mysql = MysqlConexao::getInstance();

		$data  = array();

		$sql = "SELECT cd_atividade, ds_atividade, cd_proposta_atividade, cd_tp_atividade, tp_atividade, cd_orgao_licenciado, nr_processo, cd_usuario, dt_prev_entrega, tp_status FROM `eco_atividade` WHERE cd_servico = :cdServico";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){

				$a = 0;

				while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

					$data[$a]['cd_atividade'] 		= $reg->cd_atividade;
					$data[$a]['ds_atividade'] 		= $reg->ds_atividade;
					$data[$a]['cd_tp_atividade'] 	= $reg->cd_tp_atividade;
					$data[$a]['tp_atividade'] 		= $reg->tp_atividade;
					$data[$a]['cd_usuario'] 		= $reg->cd_usuario;
					$data[$a]['dt_prev_entrega'] 	= $reg->dt_prev_entrega;
					$data[$a]['tp_status'] 			= $reg->tp_status;
					$data[$a]['cd_proposta_atividade'] = $reg->cd_proposta_atividade;
					$data[$a]['cd_orgao_licenciado'] = $reg->cd_proposta_atividade;
					$data[$a]['nr_processo'] 		= $reg->cd_proposta_atividade;

					$a++;
				}

				return $data;
			}
		}else{
			return $stmt->errorInfo();
		}
	}

	//Lista os dados em formato de select > option
	public function ListarOption(){

	}
	//Construtor genérico
	public function Dados(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "SELECT cd_atividade, cd_servico, ds_atividade, cd_tp_atividade, tp_atividade, cd_orgao_licenciado, nr_processo, cd_usuario, dt_prev_entrega, tp_status, dh_registro, nr_ordem FROM `eco_atividade` WHERE cd_atividade = :cdAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();

			if($num > 0){

				$data = array();

				while($reg  = $stmt->fetch(PDO::FETCH_OBJ)){

					$this->cdServico 			  = $reg->cd_servico;
					$this->tpAtividade 		  	  = $reg->tp_atividade;
					$this->cdTpAtividade 		  = $reg->cd_tp_atividade;
					$this->cdAtividade 			  = $reg->cd_atividade;
					$this->dsAtividade 			  = $reg->ds_atividade;
					$this->cdUsuarioResponsavel   = $reg->cd_usuario;
					$this->dtPrevEntrega   		  = $reg->dt_prev_entrega;
					$this->dhRegistro   		  = $reg->dh_registro;
					$this->dsStatus   		 	  = $reg->tp_status;
					$this->nrOrdem   		 	  = $reg->nr_ordem;
					$this->cdOrgaoLicenciador 	  = $reg->cd_orgao_licenciado;
					$this->nrProcesso  		 	  = $reg->nr_processo;

				}

			}
		}
	}

	//Listar anexos/protocolos da atividade
	function ListarAnexos(){

		$ds 		= DIRECTORY_SEPARATOR;

		// $path       = "..".$ds."repo".$ds."eco".$ds."protocoloAnexo".$ds.$this->cdAtividade.$ds;
		$path       = "..".$ds."repo".$ds."eco".$ds."protocoloAnexo".$ds.$this->cdItAtividade.$ds;
		$raiz       = "repo".$ds."eco".$ds."protocoloAnexo".$ds;
		@$diretorio = dir($path);

		$d = is_dir($path);

		if($diretorio == ''){
			@mkdir($path);
		}else{
			$a = 0;
			while($arquivo = $diretorio->read()){
				if($arquivo == '.' || $arquivo == '..'){

				}else{
					$ext = pathinfo($arquivo, PATHINFO_EXTENSION);

					switch ($ext) {
						case 'pdf':
						$dsIcon  = '<i class="material-icons">picture_as_pdf</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'doc':
						$dsIcon  = '<i class="material-icons">description</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'docx':
						$dsIcon  = '<i class="material-icons">description</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'jpg':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'png':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'gif':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'jpeg':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						default:
						$dsIcon  = '<i class="material-icons">insert_drive_file</i>';
						$colIcon = 'bg-deep-purple';
						break;
					}

					$caminho = $raiz.$this->cdItAtividade.$ds.$arquivo;

					echo '
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="info-box">
					<div class="icon '.$colIcon.'" style="min-width: 80px;">
					'.$dsIcon.'
					</div>
					<div class="content">
					<div class="text">
					<a target="_blank" href="'.$caminho.'" class="pointer">
					'.$arquivo.'
					</a>
					<br/>
					<a href="javascript:void(0)" onclick="excluirAnexo(this)" data-dir="'.base64_encode($caminho).'" data-cod="'.$this->cdItAtividade.'">
					<i class="material-icons col-red">delete</i>
					</a>
					</div>
					</div>
					</div>
					</div>
					';

					$a++;
				}
			}
			echo ($a == 0) ? '<p class="text-center"><br/><i class="material-icons">attach_file</i><br/>Nenhum anexo foi encontrado</p>' : '';

			$diretorio -> close();
		}
	}

	//retorna os dados dos anexos/protocolos da atividade
	function getAnexos(){

		$ds 		= DIRECTORY_SEPARATOR;

		// $path       = "..".$ds."repo".$ds."eco".$ds."protocoloAnexo".$ds.$this->cdAtividade.$ds;
		$path       = "..".$ds."repo".$ds."eco".$ds."protocoloAnexo".$ds.$this->cdItAtividade.$ds;
		$raiz       = "repo".$ds."eco".$ds."protocoloAnexo".$ds;
		@$diretorio = dir($path);

		$d = is_dir($path);

		$anexos = array();

		if($diretorio == ''){
			@mkdir($path);
		}else{
			$a = 0;
			while($arquivo = $diretorio->read()){
				if($arquivo == '.' || $arquivo == '..'){

				}else{
					$ext = pathinfo($arquivo, PATHINFO_EXTENSION);

					switch ($ext) {
						case 'pdf':
						$dsIcon  = '<i class="material-icons">picture_as_pdf</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'doc':
						$dsIcon  = '<i class="material-icons">description</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'docx':
						$dsIcon  = '<i class="material-icons">description</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'jpg':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'png':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'gif':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'jpeg':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						default:
						$dsIcon  = '<i class="material-icons">insert_drive_file</i>';
						$colIcon = 'bg-deep-purple';
						break;
					}

					$caminho = $raiz.$this->cdItAtividade.$ds.$arquivo;

					$anexos[] = array("url" => $caminho, "icon" => $dsIcon, "name" => $arquivo);

					$a++;
				}
			}

			$diretorio -> close();

			return $anexos;
		}
	}

	function renderAnexos() {
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT u.nm_usuario, iad.cd_it_atividade_doc, iad.ds_anexo, iad.file_data, iad.dh_registro, iad.cd_usuario_registro FROM `eco_it_atividade_doc` iad, `g_usuario` u WHERE iad.cd_usuario_registro = u.cd_usuario AND iad.cd_it_atividade = :cdItAtividade ORDER BY iad.cd_it_atividade_doc DESC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItAtividade", $this->cdItAtividade);
		$result = $stmt->execute();
		
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {
					$ext = pathinfo($reg->ds_anexo, PATHINFO_EXTENSION);

					switch ($ext) {
						case 'pdf':
						$dsIcon  = '<i class="material-icons">picture_as_pdf</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'doc':
						$dsIcon  = '<i class="material-icons">description</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'docx':
						$dsIcon  = '<i class="material-icons">description</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'jpg':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'png':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'gif':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						case 'jpeg':
						$dsIcon  = '<i class="material-icons">photo</i>';
						$colIcon = 'bg-deep-purple';
						break;

						default:
						$dsIcon  = '<i class="material-icons">insert_drive_file</i>';
						$colIcon = 'bg-deep-purple';
						break;
					}

					$file = json_decode($reg->file_data, true);
					$fileId = ($file['id']) ? $file['id'] : null;

					$fileLink = $fileId ? '<a href="javascript:void(0)" onclick="openFile(\''. base64_encode($fileId) .'\')">'.$reg->ds_anexo.'</a>' : '<a href="repo/eco/protocoloAnexo/'.$this->cdItAtividade.'/'.$reg->ds_anexo.'" target="_blank">'.$reg->ds_anexo.'</a>';

					echo '
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="info-box">
					<div class="icon '.$colIcon.'" style="min-width: 80px;">
					'.$dsIcon.'
					</div>
					<div class="content">
					<div class="text">
					'.$fileLink.'
					<br/>
					<a href="javascript:void(0)" onclick="excluirAnexo(\''. base64_encode($fileId) .'\', \''.base64_encode($reg->cd_it_atividade_doc).'\')">
					<i class="material-icons col-red">delete</i>
					</a>
					</div>
					</div>
					</div>
					</div>
					';
				}
			}
		}
	}

    //Cadastrar comentário
	public function Comentar($dsComentario, $snAtividade){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "INSERT INTO `eco_servico_comentario` (`cd_servico`, `cd_atividade`, `ds_comentario`,`sn_transforma_atividade`, `cd_usuario_registro`) VALUES (:cdServico, :cdAtividade, :dsComentario, :snAtividade, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":dsComentario", $dsComentario);
		$stmt->bindParam(":snAtividade", $snAtividade);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();
			return intval($num);

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}

	}

	//Cadastrar comentário
	public function ListarComentarios(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "SELECT u.nm_usuario, c.ds_comentario, c.dh_registro, c.cd_usuario_registro FROM `eco_servico_comentario` c, `g_usuario` u WHERE c.cd_usuario_registro = u.cd_usuario AND c.cd_atividade = :cdAtividade ORDER BY c.cd_servico_comentario DESC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {
					if($reg->cd_usuario_registro != $cdUsuarioSessao){
						echo '
						<div class="media">
						<div class="media-left">
						<a href="javascript:void(0);">
						<img src="../../lib/media/img/user-male.png" alt="Usuário" class="media-object img-circle" style="width: 50x; height:50px;">
						</a>
						</div>
						<div class="media-body bg-white p-a-10">
						<h5 class="media-heading">'.$reg->nm_usuario.'</h5>
						<p>
						'.$reg->ds_comentario.'
						</p>
						<p class="text-right pull-right" style="border-top: 1px solid #e9e9e9; padding-top: 3px; width: 50%;">
						'.date("d/m/Y H:i:s", strtotime($reg->dh_registro)).'
						</p>
						</div>
						</div>
						';
					}else{
						echo '
						<div class="media">
						<div class="media-body mdc-bg-green-300 p-a-10 text-white" style="color: white">
						<h5 class="media-heading">'.$reg->nm_usuario.'</h5>
						<p>
						'.$reg->ds_comentario.'
						</p>
						<p class="text-right pull-right" style="border-top: 1px solid #e9e9e9; padding-top: 3px; width: 50%;">
						'.date("d/m/Y H:i:s", strtotime($reg->dh_registro)).'
						</p>
						</div>
						<div class="media-right"> <a href="#">
						<img alt="64x64" class="media-object" data-src="holder.js/64x64" src="../../lib/media/img/user-female.png" data-holder-rendered="true" style="width: 50x; height:50px;">
						</a>
						</div>
						</div>
						';
					}
				}
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}

	}

	public function CadastrarItAtividade(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "INSERT INTO eco_it_atividade (cd_atividade, dt_protocolo, dt_prazo, cd_responsavel, cd_cliente, cd_orgao_licenciador, ds_andamento, cd_usuario_registro) VALUES (:cdAtividade, :dtProtocolo, :dtPrazo, :cdResponsavel, :cdCliente, :cdOrgaoLicenciador, :dsAndamento, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":dtProtocolo", $this->dtProtocolo);
		$stmt->bindParam(":dtPrazo", $this->dtPrazo);
		$stmt->bindParam(":cdResponsavel", $this->cdResponsavel);
		$stmt->bindParam(":cdCliente", $this->cdCliente);
		$stmt->bindParam(":cdOrgaoLicenciador", $this->cdOrgaoLicenciador);
		$stmt->bindParam(":dsAndamento", $this->dsAndamento);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){

				return intval($mysql->lastInsertId());

			}else{
				return 0;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}

	}

	public function ListarItAtividade(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$ds = DIRECTORY_SEPARATOR;

		$sql = "SELECT ia.cd_it_atividade, ia.cd_it_atividade, ia.dt_protocolo, ia.dt_prazo, ia.ds_andamento, ia.tp_status, u.nm_usuario, o.nm_orgao_licenciado, c.nm_cliente
		FROM eco_it_atividade ia
		LEFT JOIN g_usuario u ON ia.cd_responsavel = u.cd_usuario
		LEFT JOIN g_cliente c ON ia.cd_cliente     = c.cd_cliente
		LEFT JOIN g_orgao_licenciado o ON ia.cd_orgao_licenciador = o.cd_orgao_licenciado
		WHERE ia.cd_atividade =  :cdAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){

				while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

					//Configura exibição do menu
					switch ($reg->tp_status) {
						//Concluído
						case 'O':
						$dsStatus = '<span class="col-green">CONCLUÍDO</span>';
						break;

						//Em andamento
						case 'E':
						$dsStatus = '<span class="col-amber">EM ANDAMENTO</span>';
						break;

						//Reaberta
						case 'R':
						$dsStatus = '<span class="col-orange">REABERTO</span>';
						break;

						//Atrasado
						case 'A':
						$dsStatus = '<span class="col-pink">ATRASADO</span>';
						break;

						//Cancelado
						case 'C':
						$dsStatus = '<span class="col-red">CANCELADO</span>';
						break;

						//Em tramite
						case 'T':
						$dsStatus = '<span class="col-orange">EM TRÂMITE</span>';
						break;

						default:
						$dsStatus = '';
						break;
					}

					$directory 	= "..".$ds."repo".$ds."eco".$ds."protocoloAnexo".$ds.$reg->cd_it_atividade.$ds;
					$filecount 	= 0;
					$files 		= glob($directory . "*");

					if ($files){
						$filecount = count($files);
					}

					echo '<tr class="cursorPointer viewAndamento" data-cod="'.$reg->cd_it_atividade.'">';
					echo '<td>'.$reg->cd_it_atividade.'</td>';
					echo '<td>'.date("d/m/Y", strtotime($reg->dt_protocolo)).'</td>';
					echo '<td>'.date("d/m/Y", strtotime($reg->dt_prazo)).'</td>';
					echo '<td>'.$reg->ds_andamento.'</td>';
					echo '<td>'.$reg->nm_usuario.'</td>';
					// echo '<td>'.$reg->nm_cliente.'</td>';
					// echo '<td>'.$reg->nm_orgao_licenciado.'</td>';
					echo '<td class="text-center"><span class="badge bg-deep-purple">'.$filecount.'</span></td>';
					echo '<td class="text-center">'.$dsStatus.'</td>';
					echo '</tr>';

				}

			}else{
				return 0;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}

	}

	public function DadosItAtividade(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "SELECT * FROM eco_it_atividade WHERE cd_it_atividade = :cdItAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItAtividade", $this->cdItAtividade);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){

				$reg = $stmt->fetch(PDO::FETCH_OBJ);

				$this->cdAtividade 			= $reg->cd_atividade;
				$this->dtProtocolo 			= $reg->dt_protocolo;
				$this->dtPrazo 				= $reg->dt_prazo;
				$this->dsAndamento 			= $reg->ds_andamento;
				$this->cdResponsavel 		= $reg->cd_responsavel;
				$this->cdCliente 			= $reg->cd_cliente;
				$this->cdOrgaoLicenciador 	= $reg->cd_orgao_licenciador;
				$this->tpStatus 			= $reg->tp_status;

			}else{
				return 0;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}

	}

	public function AlterarItAtividade(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "UPDATE eco_it_atividade SET dt_protocolo = :dtProtocolo, dt_prazo = :dtPrazo, ds_andamento = :dsAndamento, cd_responsavel = :cdResponsavel, cd_cliente = :cdCliente, cd_orgao_licenciador = :cdOrgaoLicenciador WHERE cd_it_atividade = :cdItAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItAtividade", $this->cdItAtividade);
		$stmt->bindParam(":dtProtocolo", $this->dtProtocolo);
		$stmt->bindParam(":dtPrazo", $this->dtPrazo);
		$stmt->bindParam(":dsAndamento", $this->dsAndamento);
		$stmt->bindParam(":cdResponsavel", $this->cdResponsavel);
		$stmt->bindParam(":cdCliente", $this->cdCliente);
		$stmt->bindParam(":cdOrgaoLicenciador", $this->cdOrgaoLicenciador);
		$stmt->bindParam(":dsAndamento", $this->dsAndamento);
		$result = $stmt->execute();
		if ($result) {

			return true;

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public function AtualizarStatusItAtividade(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "UPDATE eco_it_atividade SET tp_status = :dsStatus WHERE cd_it_atividade = :cdItAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItAtividade", $this->cdItAtividade);
		$stmt->bindParam(":dsStatus", $this->dsStatus);
		$result = $stmt->execute();
		if ($result) {

			return true;

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public function MovItAtividade(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "INSERT INTO eco_mov_it_atividade (cd_atividade, cd_it_atividade, tp_status, cd_usuario_registro) VALUES (:cdAtividade, :cdItAtividade, :dsStatus, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":cdItAtividade", $this->cdItAtividade);
		$stmt->bindParam(":dsStatus", $this->dsStatus);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			$d = self::AtualizarStatusItAtividade();

			return true;

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public function addFaseAtividadeServico($cdFase){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "INSERT INTO `eco_atividade_fase` (`cd_atividade`, `cd_fase_atividade`, `cd_usuario_registro`) VALUES (:cdAtividade, :cdFase, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":cdFase", $cdFase);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			return $mysql->lastInsertId();

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public function removerFaseAtividade($cdAtividadeFase){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "DELETE FROM `eco_atividade_fase` WHERE cd_atividade_fase = :cdAtividadeFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
		$result = $stmt->execute();
		if ($result) {

			return true;

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}


	public function addFaseAtividade(){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "INSERT INTO `eco_atividade_fase` (`cd_atividade`, `cd_fase_atividade`, `cd_usuario_registro`) SELECT :cdAtividade, cd_fase_atividade, :cdUsuarioSessao FROM eco_tp_atividade_fase WHERE cd_tp_atividade = (SELECT cd_tp_atividade FROM eco_atividade WHERE cd_atividade = :cdAtividade)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			return true;

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public function listFasesAtividade(){
		$mysql = MysqlConexao::getInstance();

		$cdEmpresa = $_SESSION['cdEmpresa'];

		$sql = "SELECT * FROM eco_fase_atividade fa, eco_atividade_fase af WHERE fa.cd_fase_atividade = af.cd_fase_atividade AND cd_atividade = :cdAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){

				while($reg = $stmt->fetch(PDO::FETCH_OBJ)){

					$checked = ($reg->tp_status == 'C') ? 'checked' : null;
					$delLine = ($reg->tp_status == 'C') ? 'text-decoration: line-through;' : null;
					$dtPrazo = !is_null($reg->dt_prazo) ? date('d/m/Y', strtotime($reg->dt_prazo)) : null;

					$labelPrazo = (!is_null($dtPrazo)) ? '<div class="m-t-15 m-b-10">
					<u class="list-unstyled list-inline">
					<li class="text-muted"><small><i class="material-icons font-16">calendar_today</i> <span class="m-t--5">'.$dtPrazo.'</span></small></li>
					</u>
					</div>' : null;

					echo '
					<div class="card rounded" style="margin-bottom: 10px;">
					<div class="card-body">
					<div class="container-fluid">
					<a href="javascript:void(0)" class="pull-right m-t-10" onclick="removerFaseAtividade(this)" data-cod="'.$reg->cd_atividade_fase.'"><i class="material-icons col-red">delete</i></a>
					<input id="md_checkbox_'.$reg->cd_atividade_fase.'" data-cod="'.$reg->cd_atividade_fase.'" class="filled-in chk-col-green" type="checkbox" '.$checked.'>
					<label for="md_checkbox_'.$reg->cd_atividade_fase.'" class="pull-left m-t-15" style="margin-bottom: 0px;"></label>
					<div class="m-t-15">
					<a href="javascript:void(0)" onclick="viewComentarioFase(this)" data-cod="'.$reg->cd_atividade_fase.'">'.$reg->ds_fase_atividade.'</a>
					</div>
					'.$labelPrazo.'
					</div>
					</div>
					</div>
					';
				}
			}else{
				return 0;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));
		}
	}

	public static function addComentarioFase($cdAtividadeFase, $dsComentario){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "INSERT INTO `eco_atividade_fase_comentario` (`cd_atividade_fase`, `ds_comentario`, `cd_usuario_registro`) VALUES (:cdAtividadeFase, :dsComentario, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
		$stmt->bindParam(":dsComentario", $dsComentario);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			return intval($mysql->lastInsertId());

		}else{
			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function addAnexoComentarioFase($cdAtividadeFaseComentario, $dsAnexo, $fileData){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "UPDATE `eco_atividade_fase_comentario` SET ds_anexo = :dsAnexo, file_data = :fileData WHERE cd_atividade_fase_comentario = :cdAtividadeFaseComentario";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFaseComentario", $cdAtividadeFaseComentario);
		$stmt->bindParam(":dsAnexo", $dsAnexo);
		$stmt->bindParam(":fileData", $fileData);
		$result = $stmt->execute();
		if ($result) {

			return intval($stmt->rowCount());

		}else{
			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function listComentarioFase($cdAtividadeFase){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "SELECT c.cd_atividade_fase_comentario, u.nm_usuario, c.cd_usuario_registro, c.ds_comentario, c.ds_anexo, c.file_data, c.cd_atividade_fase, c.dh_registro FROM `eco_atividade_fase_comentario` c, `g_usuario` u WHERE c.cd_usuario_registro = u.cd_usuario AND c.cd_atividade_fase = :cdAtividadeFase ORDER BY c.dh_registro ASC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
		$result = $stmt->execute();
		if ($result) {

			echo '<div class="row">';

			//alterar label quando publicar o documento

			while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

				$fileData = json_decode($reg->file_data);
				$link = 'repo' . DIRECTORY_SEPARATOR . 'eco' . DIRECTORY_SEPARATOR . 'chat' . DIRECTORY_SEPARATOR . $reg->cd_atividade_fase . DIRECTORY_SEPARATOR . $reg->cd_atividade_fase_comentario . DIRECTORY_SEPARATOR . $reg->ds_anexo . '';

				if (!is_null($fileData)) {
					$dropbox = new cDropbox();
					$fileInfo = $dropbox->get($fileData->id);
					$link = $fileInfo->link;
				}

				$anexo = (!is_null($reg->ds_anexo)) ? '
				<div class="body p-a-10">
				<ul class="pull-right m-r--5 list-unstyled">
				<li class="dropdown">
				<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
				<i class="material-icons">more_vert</i>
				</a>
				<ul class="dropdown-menu pull-right">
				<li onclick="publicarAnexo(this)" data-comentario="' . $reg->cd_atividade_fase_comentario . '" data-arquivo="' . $reg->ds_anexo . '"><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block"><i class="material-icons col-deep-purple">cloud_upload</i> Publicar</a></li>
				</ul>
				</li>
				</ul>
				<a href="' . $link . '" target="_blank"><i class="material-icons pull-left m-t-3">attach_file</i> &nbsp;&nbsp;' . $reg->ds_anexo . '</a> &nbsp;&nbsp;&nbsp;<i>Não publicado</i>
				</div>
				' : null;

				if ($cdUsuarioSessao == $reg->cd_usuario_registro) {
					echo '
					<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-md-offset-2 m-b-0">
					<div class="card">
					<div class="body mdc-bg-deep-purple-100 p-a-10 col-black">
					<strong class="pull-right">'.date("d/m/Y H:i:s", strtotime($reg->dh_registro)).'</strong>
					<strong>Você</strong>
					<br>
					<br>
					'.$reg->ds_comentario.'
					</div>
					'.$anexo.'
					</div>
					</div>
					<div class="clearfix">

					</div>
					';
				} else {
					echo '
					<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 m-b-0">
					<div class="card">
					<div class="body p-a-10 col-black">
					<strong class="pull-right">'.date("d/m/Y H:i:s", strtotime($reg->dh_registro)).'</strong>
					<strong>'.$reg->nm_usuario.'</strong>
					<br>
					'.$reg->ds_comentario.'
					</div>
					'.$anexo.'
					</div>
					</div>
					<div class="clearfix">

					</div>
					';
				}

			}

			echo '</div>';

		}else{
			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function dadosFase($cdAtividadeFase){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "SELECT af.cd_atividade, fa.ds_fase_atividade, ta.ds_tp_atividade, a.cd_servico, af.cd_fase_atividade, af.tp_status, af.dt_prazo, af.cd_usuario_responsavel, af.cd_usuario_registro, af.dh_registro FROM `eco_atividade_fase` af, `eco_fase_atividade` fa, `eco_atividade` a, `eco_tp_atividade` ta WHERE af.cd_atividade_fase = :cdAtividadeFase AND fa.cd_fase_atividade = af.cd_fase_atividade AND a.cd_atividade = af.cd_atividade AND a.cd_tp_atividade = ta.cd_tp_atividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
		$result = $stmt->execute();
		if ($result) {

			return $reg = $stmt->fetch(PDO::FETCH_OBJ);

		}else{
			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function concluirAtividadeFase($cdAtividadeFase){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "UPDATE `eco_atividade_fase` SET tp_status = 'C', cd_usuario_conclusao = :cdUsuarioSessao, dh_conclusao = now() WHERE cd_atividade_fase = :cdAtividadeFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			return intval($stmt->rowCount());

		}else{
			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function reabrirAtividadeFase($cdAtividadeFase){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "UPDATE `eco_atividade_fase` SET tp_status = 'A', cd_usuario_conclusao = NULL, dh_conclusao = NULL WHERE cd_atividade_fase = :cdAtividadeFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
		$result = $stmt->execute();
		if ($result) {

			return intval($stmt->rowCount());

		}else{
			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function atualizarResponsavelAtividadeFase($cdAtividadeFase, $cdUsuarioResponsavel){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "UPDATE `eco_atividade_fase` SET cd_usuario_responsavel = :cdUsuarioResponsavel WHERE cd_atividade_fase = :cdAtividadeFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
		$stmt->bindParam(":cdUsuarioResponsavel", $cdUsuarioResponsavel);
		$result = $stmt->execute();
		if ($result) {

			return intval($stmt->rowCount());

		}else{
			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function publicarDocConsultoria($cdAtividadeFaseComentario, $dsAnexo)
	{
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "INSERT INTO `eco_doc_consultoria` (cd_atividade_fase_comentario, ds_anexo, cd_usuario_publicacao) SELECT :cdAtividadeFaseComentario, :dsAnexo, :cdUsuarioSessao FROM DUAL WHERE NOT EXISTS (SELECT * FROM eco_doc_consultoria WHERE cd_atividade_fase_comentario = :cdAtividadeFaseComentario AND ds_anexo = :dsAnexo)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFaseComentario", $cdAtividadeFaseComentario);
		$stmt->bindParam(":dsAnexo", $dsAnexo);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			return intval($stmt->rowCount());

		}else{
			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function removerPubDocConsultoria($cdDocConsultoria)
	{
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "DELETE FROM `eco_doc_consultoria` WHERE cd_doc_consultoria = :cdDocConsultoria";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdDocConsultoria", $cdDocConsultoria);
		$result = $stmt->execute();
		if ($result) {

			return intval($stmt->rowCount());

		}else{
			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function listarDocConsultoria($cdServico)
	{
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "
		SELECT 	dc.cd_doc_consultoria, a.cd_servico, a.cd_atividade, af.cd_atividade_fase, afc.cd_atividade_fase_comentario, afc.ds_anexo, dc.dh_publicado, u.nm_usuario
		FROM 	`eco_doc_consultoria` dc,
		`eco_atividade_fase_comentario` afc,
		`eco_atividade_fase` af,
		`eco_atividade` a,
		`g_usuario` u
		WHERE 	dc.cd_atividade_fase_comentario = afc.cd_atividade_fase_comentario
		AND		af.cd_atividade_fase 			= afc.cd_atividade_fase
		AND		af.cd_atividade					= a.cd_atividade
		AND		dc.cd_usuario_publicacao		= u.cd_usuario
		AND		a.cd_servico					= :cdServico
		";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $cdServico);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();

			if ($num > 0) {

				return $stmt->fetchAll(PDO::FETCH_OBJ);

			} else {
				return array();
			}

		}else{
			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function alterarOrdemAtividade($cdAtividade, $nrOrdem)
	{
		$mysql = MysqlConexao::getInstance();

		$sql = "UPDATE eco_atividade SET nr_ordem = :nrOrdem WHERE cd_atividade = :cdAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $cdAtividade);
		$stmt->bindParam(":nrOrdem", $nrOrdem);
		$result = $stmt->execute();
		if ($result) {

			$num = $stmt->rowCount();

			return intval($num);

		}else{
			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	// //Realiza o vínculo com o item de proposta
	// public function vincularItemProposta($cdItProposta){
	// 	$mysql = MysqlConexao::getInstance();

	// 	$cdUsuarioSessao = $_SESSION['cdUsuario'];
	// 	$cdEmpresa 		 = $_SESSION['cdEmpresa'];

	// 	$sql = "UPDATE `eco_atividade` SET cd_itproposta_licenca = :cdItProposta WHERE cd_atividade = :cdAtividade;";
	// 	$stmt = $mysql->prepare($sql);
	// 	$stmt->bindParam(":cdAtividade", $this->cdAtividade);
	// 	$stmt->bindParam(":cdItProposta", $cdItProposta);
	// 	$result = $stmt->execute();
	// 	if ($result) {
	// 		$num = $stmt->rowCount();

	// 		if($num > 0){
	// 			return true;
	// 		}else{
	// 			return false;
	// 		}

	// 	}else{
    //         //GERAR LOG
	// 		ob_start();
	// 		var_dump($stmt->errorInfo());
	// 		$dsError = ob_get_clean();
	// 		regLog($dsError, basename( __FILE__ ));

	// 		$erro = $stmt->errorInfo();

	// 		return $erro[2];
	// 	}
	// }

	public static function getAtividadeByAtividadeProposta($cdPropostaAtividade){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "SELECT * FROM `eco_atividade` WHERE cd_proposta_atividade = :cdPropostaAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdPropostaAtividade", $cdPropostaAtividade);
		$result = $stmt->execute();
		if ($result) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		}else{
			$erro = $stmt->errorInfo();
			return $erro[2];
		}
	}

	//Realiza o vínculo com o item de proposta
	public function vincularItemProposta($cdPropostaAtividade){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE `eco_atividade` SET cd_proposta_atividade = :cdPropostaAtividade WHERE cd_atividade = :cdAtividade;";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":cdPropostaAtividade", $cdPropostaAtividade);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();

			if($num > 0){
				return true;
			}else{
				return false;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public function suspenderAtividade($cdPropostaAtividade)
	{
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE `eco_atividade` SET tp_status = 'S' WHERE cd_proposta_atividade = :cdPro$cdPropostaAtividade;";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdPro$cdPropostaAtividade", $cdPropostaAtividade);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();

			if($num > 0){
				return true;
			}else{
				return false;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function addResponsavelAtividade($cdAtividadeFase, $cdUsuarioResponsavel)
	{
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "INSERT INTO `eco_atividade_fase_responsavel` (cd_atividade_fase, cd_usuario_responsavel, cd_usuario_registro) SELECT :cdAtividadeFase, :cdUsuarioResponsavel, :cdUsuarioSessao FROM DUAL WHERE NOT EXISTS (SELECT cd_atividade_fase FROM `eco_atividade_fase_responsavel` WHERE cd_usuario_responsavel = :cdUsuarioResponsavel AND cd_atividade_fase = :cdAtividadeFase)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
		$stmt->bindParam(":cdUsuarioResponsavel", $cdUsuarioResponsavel);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();

			if($num > 0){
				return true;
			}else{
				return false;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function removerResponsavelAtividade($cdAtividadeFase, $cdUsuarioResponsavel)
	{
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "DELETE FROM `eco_atividade_fase_responsavel` WHERE cd_usuario_responsavel = :cdUsuarioResponsavel AND cd_atividade_fase = :cdAtividadeFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
		$stmt->bindParam(":cdUsuarioResponsavel", $cdUsuarioResponsavel);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();

			if($num > 0){
				return true;
			}else{
				return false;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function getResponsaveisFase($cdAtividadeFase)
	{
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT cd_usuario_responsavel, (SELECT nm_usuario FROM g_usuario u WHERE u.cd_usuario = cd_usuario_responsavel) as nm_usuario FROM `eco_atividade_fase_responsavel` WHERE cd_atividade_fase = :cdAtividadeFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();

			if($num > 0){
				return $stmt->fetchAll(PDO::FETCH_OBJ);
			}else{
				return false;
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function alterarPrazoFase($cdAtividadeFase, $dtPrazo)
	{
		$mysql = MysqlConexao::getInstance();

		$sql = "UPDATE eco_atividade_fase SET dt_prazo = :dtPrazo WHERE cd_atividade_fase = :cdAtividadeFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
		$stmt->bindParam(":dtPrazo", $dtPrazo);
		$result = $stmt->execute();
		if ($result) {

			return true;

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function cadastrarProdutoAssessoria($cdItAtividade=null, $dsDocumento=null, $dtEmissao=null, $dtValidade=null, $dsAnexo=null)
	{
		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		
		$dropbox = new cDropbox();

		$servico = cServico::getServicoByItAtividade($cdItAtividade);
		$folder  = trim($servico->nm_cliente)."/".trim($servico->nm_empreendimento)."/Proposta - $servico->nr_protocolo.$servico->competencia";

		$dropBoxUpload = $dropbox->upload($dsAnexo, $folder);

		$mysql = MysqlConexao::getInstance();

		$sql = "INSERT INTO eco_doc_assessoria (cd_it_atividade, ds_documento, dt_emissao, dt_validade, ds_anexo, file_data, cd_usuario_registro) VALUES (:cdItAtividade, :dsDocumento, :dtEmissao, :dtValidade, :dsAnexo, :fileData, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItAtividade", $cdItAtividade);
		$stmt->bindParam(":dsDocumento", $dsDocumento);
		$stmt->bindParam(":dtEmissao", $dtEmissao);
		$stmt->bindParam(":dtValidade", $dtValidade);
		$stmt->bindParam(":dsAnexo", $dsAnexo['name']);
		$stmt->bindParam(":fileData", json_encode($dropBoxUpload));
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			return $mysql->lastInsertId();

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function getProdutosAssessoria($cdItAtividade=null, $cdServico=null)
	{
		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT cd_doc_assessoria, da.cd_it_atividade, a.cd_atividade, ds_documento, dt_emissao, dt_validade, ds_anexo, file_data, da.dh_registro, nm_usuario, da.file_data
		FROM eco_doc_assessoria da, g_usuario u, eco_it_atividade ia, eco_atividade a
		WHERE da.cd_usuario_registro = u.cd_usuario
		AND ia.cd_it_atividade = da.cd_it_atividade
		AND a.cd_atividade = ia.cd_atividade";
		$sql .= (!is_null($cdItAtividade)) ? " AND da.cd_it_atividade = :cdItAtividade" : null;
		$sql .= (!is_null($cdServico)) ? " AND a.cd_servico = :cdServico" : null;

		$stmt = $mysql->prepare($sql);
		(!is_null($cdItAtividade)) ? $stmt->bindParam(":cdItAtividade", $cdItAtividade) : null;
		(!is_null($cdServico)) ? $stmt->bindParam(":cdServico", $cdServico) : null;
		$result = $stmt->execute();
		if ($result) {

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		}else{
			var_dump($stmt->errorInfo());

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function updateProdutosAssessoria($cdDocAssessoria, $nmColuna, $valor)
	{
		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$mysql = MysqlConexao::getInstance();

		$sql = "UPDATE eco_doc_assessoria SET $nmColuna = :valor WHERE cd_doc_assessoria = :cdDocAssessoria";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdDocAssessoria", $cdDocAssessoria);
		$stmt->bindParam(":valor", $valor);
		$result = $stmt->execute();
		if ($result) {

			return $stmt->rowCount();

		}else{
			var_dump($stmt->errorInfo());

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function removerProdutoAssessoria($cdDocAssessoria)
	{
		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$mysql = MysqlConexao::getInstance();

		$sql = "DELETE FROM eco_doc_assessoria WHERE cd_doc_assessoria = :cdDocAssessoria";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdDocAssessoria", $cdDocAssessoria);
		$result = $stmt->execute();
		if ($result) {

			return $stmt->rowCount();

		}else{
			var_dump($stmt->errorInfo());

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function getAndamentos($tpStatus, $dtInicial = null, $dtFinal = null)
	{
		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$mysql = MysqlConexao::getInstance();

		$sqlDate = (!is_null($dtInicial) && !is_null($dtFinal)) ? ' AND dt_prazo BETWEEN :dtInicial AND :dtFinal ' : null;

		$sql = "SELECT ita.cd_it_atividade, c.nm_cliente, e.ds_empreendimento, ta.ds_tp_atividade, ita.dt_prazo, datediff(now(), ita.dt_prazo) as dias_atraso,
		(SELECT nm_usuario FROM g_usuario WHERE cd_usuario = a.cd_usuario) as nm_responsavel_atividade,
		(SELECT nm_cliente FROM g_cliente WHERE cd_cliente = ita.cd_cliente) as nm_cliente_responsavel,
		(SELECT nm_orgao_licenciado FROM g_orgao_licenciado WHERE cd_orgao_licenciado = ita.cd_orgao_licenciador) as nm_orgao_responsavel,
		ita.tp_status
		FROM eco_atividade a, eco_servico s, g_empreendimento e, g_cliente c, eco_tp_atividade ta, eco_it_atividade ita
		WHERE a.cd_atividade = ita.cd_atividade
		AND a.cd_tp_atividade = ta.cd_tp_atividade
		AND a.cd_servico = s.cd_servico
		AND s.cd_empreendimento = e.cd_empreendimento
		AND s.cd_cliente = c.cd_cliente
		AND s.cd_empresa = :cdEmpresa
		AND ita.tp_status IN ($tpStatus)
		$sqlDate
		ORDER BY dt_prazo ASC";
		// var_dump($sql);
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		(!is_null($dtInicial) && !is_null($dtFinal)) ? $stmt->bindParam(":dtInicial", $dtInicial) : null;
		(!is_null($dtInicial) && !is_null($dtFinal)) ? $stmt->bindParam(":dtFinal", $dtFinal) : null;
		$result = $stmt->execute();
		if ($result) {

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		}else{
			var_dump($stmt->errorInfo());

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function getAndamentosAtrasadas()
	{
		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT ita.cd_it_atividade, c.nm_cliente, e.ds_empreendimento, ta.ds_tp_atividade, ita.dt_prazo, datediff(now(), ita.dt_prazo) as dias_atraso,
		(SELECT nm_usuario FROM g_usuario WHERE cd_usuario = a.cd_usuario) as nm_responsavel_atividade,
		(SELECT nm_cliente FROM g_cliente WHERE cd_cliente = ita.cd_cliente) as nm_cliente_responsavel,
		(SELECT nm_orgao_licenciado FROM g_orgao_licenciado WHERE cd_orgao_licenciado = ita.cd_orgao_licenciador) as nm_orgao_responsavel,
		ita.tp_status
		FROM eco_atividade a, eco_servico s, g_empreendimento e, g_cliente c, eco_tp_atividade ta, eco_it_atividade ita
		WHERE ita.dt_prazo < now()
		AND a.cd_atividade = ita.cd_atividade
		AND a.cd_tp_atividade = ta.cd_tp_atividade
		AND a.cd_servico = s.cd_servico
		AND s.cd_empreendimento = e.cd_empreendimento
		AND s.cd_cliente = c.cd_cliente
		AND s.cd_empresa = :cdEmpresa
		AND ita.tp_status NOT IN ('C', 'O')
		ORDER BY dt_prazo ASC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		$result = $stmt->execute();
		if ($result) {

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		}else{
			var_dump($stmt->errorInfo());

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function getAtividadesAtrasadas()
	{
		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT af.cd_atividade_fase, c.nm_cliente, e.ds_empreendimento, ta.ds_tp_atividade, af.dt_prazo, datediff(now(), af.dt_prazo) as dias_atraso,
		(SELECT nm_usuario FROM g_usuario WHERE cd_usuario = a.cd_usuario) as nm_responsavel_atividade, u.nm_usuario as nm_responsavel_fase
		FROM eco_atividade a, eco_servico s, g_empreendimento e, g_cliente c, eco_tp_atividade ta, eco_atividade_fase af
		LEFT JOIN g_usuario u
		ON af.cd_usuario_responsavel = u.cd_usuario
		WHERE dt_prazo < now()
		AND a.cd_atividade = af.cd_atividade
		AND a.cd_tp_atividade = ta.cd_tp_atividade
		AND a.cd_servico = s.cd_servico
		AND s.cd_empreendimento = e.cd_empreendimento
		AND s.cd_cliente = c.cd_cliente
		AND s.cd_empresa = :cdEmpresa
		ORDER BY dt_prazo ASC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		$result = $stmt->execute();
		if ($result) {

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		}else{
			var_dump($stmt->errorInfo());

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function getAtividadesConcluidas()
	{
		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT af.cd_atividade_fase, c.nm_cliente, e.ds_empreendimento, ta.ds_tp_atividade, af.dt_prazo, datediff(now(), af.dt_prazo) as dias_atraso,
		(SELECT nm_usuario FROM g_usuario WHERE cd_usuario = a.cd_usuario) as nm_responsavel_atividade, u.nm_usuario as nm_responsavel_fase
		FROM eco_atividade a, eco_servico s, g_empreendimento e, g_cliente c, eco_tp_atividade ta, eco_atividade_fase af
		LEFT JOIN g_usuario u
		ON af.cd_usuario_responsavel = u.cd_usuario
		WHERE  a.cd_atividade = af.cd_atividade
		AND a.cd_tp_atividade = ta.cd_tp_atividade
		AND a.cd_servico = s.cd_servico
		AND s.cd_empreendimento = e.cd_empreendimento
		AND s.cd_cliente = c.cd_cliente
		AND s.cd_empresa = :cdEmpresa
		AND a.tp_status  = 'O'
		ORDER BY dt_prazo ASC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		$result = $stmt->execute();
		if ($result) {

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		}else{
			var_dump($stmt->errorInfo());

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function getAtividadesAbertas()
	{
		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT af.cd_atividade_fase, c.nm_cliente, e.ds_empreendimento, ta.ds_tp_atividade, af.dt_prazo, datediff(now(), af.dt_prazo) as dias_atraso,
		(SELECT nm_usuario FROM g_usuario WHERE cd_usuario = a.cd_usuario) as nm_responsavel_atividade, u.nm_usuario as nm_responsavel_fase
		FROM eco_atividade a, eco_servico s, g_empreendimento e, g_cliente c, eco_tp_atividade ta, eco_atividade_fase af
		LEFT JOIN g_usuario u
		ON af.cd_usuario_responsavel = u.cd_usuario
		WHERE  a.cd_atividade = af.cd_atividade
		AND a.cd_tp_atividade = ta.cd_tp_atividade
		AND a.cd_servico = s.cd_servico
		AND s.cd_empreendimento = e.cd_empreendimento
		AND s.cd_cliente = c.cd_cliente
		AND s.cd_empresa = :cdEmpresa
		AND a.tp_status  IN ('E', 'R')
		ORDER BY dt_prazo ASC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		$result = $stmt->execute();
		if ($result) {

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		}else{
			var_dump($stmt->errorInfo());

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public static function getAtividades($tpStatus, $dtInicial = null, $dtFinal = null)
	{
		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$mysql = MysqlConexao::getInstance();

		$sqlDate = (!is_null($dtInicial) && !is_null($dtFinal)) ? ' AND dt_prev_entrega BETWEEN :dtInicial AND :dtFinal ' : null;



		$sql = "SELECT af.cd_atividade_fase, c.nm_cliente, e.ds_empreendimento, ta.ds_tp_atividade, af.dt_prazo, datediff(now(), af.dt_prazo) as dias_atraso,
		(SELECT nm_usuario FROM g_usuario WHERE cd_usuario = a.cd_usuario) as nm_responsavel_atividade, u.nm_usuario as nm_responsavel_fase
		FROM eco_atividade a, eco_servico s, g_empreendimento e, g_cliente c, eco_tp_atividade ta, eco_atividade_fase af
		LEFT JOIN g_usuario u
		ON af.cd_usuario_responsavel = u.cd_usuario
		WHERE  a.cd_atividade = af.cd_atividade
		AND a.cd_tp_atividade = ta.cd_tp_atividade
		AND a.cd_servico = s.cd_servico
		AND s.cd_empreendimento = e.cd_empreendimento
		AND s.cd_cliente = c.cd_cliente
		AND s.cd_empresa = :cdEmpresa
		AND a.tp_status  IN ($tpStatus)
		$sqlDate
		ORDER BY dt_prazo ASC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		(!is_null($dtInicial) && !is_null($dtFinal)) ? $stmt->bindParam(":dtInicial", $dtInicial) : null;
		(!is_null($dtInicial) && !is_null($dtFinal)) ? $stmt->bindParam(":dtFinal", $dtFinal) : null;
		$result = $stmt->execute();
		if ($result) {

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		}else{
			var_dump($stmt->errorInfo());

			$erro = $stmt->errorInfo();

			return $erro[2];
		}
	}

	public function setHistoricoAlteracaoData($dtAnterior, $dtPrevEntrega, $dsJustificativa)
	{
		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$mysql = MysqlConexao::getInstance();

		$sql = "INSERT INTO eco_atividade_hist_prev (cd_atividade, dt_prev_entrega_anterior, dt_prev_entrega, ds_justificativa, cd_usuario_registro) VALUES (:cdAtividade, :dtPrevEntregaAnterior, :dtPrevEntrega, :dsJustificativa, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$stmt->bindParam(":dtPrevEntregaAnterior", $dtAnterior);
		$stmt->bindParam(":dtPrevEntrega", $dtPrevEntrega);
		$stmt->bindParam(":dsJustificativa", $dsJustificativa);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {

			return $mysql->lastInsertId();

		}else{
			$erro = $stmt->errorInfo();
			return $erro[2];
		}
	}

	public function getHistoricoAlteracaoData()
	{
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT *, (SELECT nm_usuario FROM g_usuario WHERE cd_usuario = eco_atividade_hist_prev.cd_usuario_registro) as nm_usuario FROM eco_atividade_hist_prev WHERE cd_atividade = :cdAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAtividade", $this->cdAtividade);
		$result = $stmt->execute();
		
		if ($result) {

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		} else {
			$erro = $stmt->errorInfo();
			return $erro[2];
		}
	}

	public function excluirItAtividade($cdAndamento)
	{
		$mysql = MysqlConexao::getInstance();

		$sql = "DELETE FROM eco_it_atividade WHERE cd_it_atividade = :cdAndamento";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdAndamento", $cdAndamento);
		$result = $stmt->execute();
		
		if ($result) {

			return $stmt->rowCount();

		} else {
			$erro = $stmt->errorInfo();
			return $erro[2];
		}
	}

	public static function addAnexoItAtividade($cdItAtividade, $anexo) {
		
		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		
		$dropbox = new cDropbox();

		$servico = cServico::getServicoByItAtividade($cdItAtividade);
		$folder  = trim($servico->nm_cliente)."/".trim($servico->nm_empreendimento)."/Proposta - $servico->nr_protocolo.$servico->competencia";

		$dropBoxUpload = $dropbox->upload($anexo, $folder);

		$mysql = MysqlConexao::getInstance();

		$sql = "INSERT INTO eco_it_atividade_doc (cd_it_atividade, ds_anexo, file_data, cd_usuario_registro) VALUES (:cdItAtividade, :anexo, :fileData, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItAtividade", $cdItAtividade);
		$stmt->bindParam(":anexo", $anexo['name']);
		$stmt->bindParam(":fileData", json_encode($dropBoxUpload));
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		
		if ($result) {

			return $stmt->rowCount();

		} else {
			$erro = $stmt->errorInfo();
			return $erro[2];
		}
	}

	public static function removeAnexoItAtividade($cdItAtividade, $fileId) {
		
		$dropbox = new cDropbox();
		$dropbox->delete($fileId);

		$mysql = MysqlConexao::getInstance();

		$sql = "DELETE FROM eco_it_atividade_doc WHERE cd_it_atividade_doc = :cdItAtividade";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItAtividade", $cdItAtividade);
		$result = $stmt->execute();
		
		if ($result) {

			return $stmt->rowCount();

		} else {
			$erro = $stmt->errorInfo();
			return $erro[2];
		}
	}
}

?>