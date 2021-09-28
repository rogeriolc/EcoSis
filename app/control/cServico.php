<?php
include_once('../conf/interfaceBase.php');

/**
* Control Servico
*/
class cServico extends mServico {

	function __construct($cdServico = null)
	{
		$this->cdServico = $cdServico;
	}

	//Realiza o cadastro
	public function Cadastrar(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "INSERT INTO eco_servico (`cd_cliente`, `cd_empreendimento`, `cd_orgao_licenciado`, `nr_processo`, `cd_empresa`, `cd_usuario_registro`) VALUES (:cdCliente, :cdEmpreendimento, :cdOrgaoLicenciado, UPPER(:nrProcesso), :cdEmpresa, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdCliente", $this->cdCliente);
		$stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
		$stmt->bindParam(":cdOrgaoLicenciado", $this->cdOrgaoLicenciado);
		$stmt->bindParam(":nrProcesso", $this->nrProcesso);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
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

	//Altera o cadastro
	public function Alterar(){

		$mysql = MysqlConexao::getInstance();

		$sql = "UPDATE eco_servico SET `cd_cliente` = :cdCliente, `cd_empreendimento` = :cdEmpreendimento, `cd_orgao_licenciado` = :cdOrgaoLicenciado, `nr_processo` = UPPER(:nrProcesso) WHERE cd_servico = :cdServico";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		$stmt->bindParam(":cdCliente", $this->cdCliente);
		$stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
		$stmt->bindParam(":cdOrgaoLicenciado", $this->cdOrgaoLicenciado);
		$stmt->bindParam(":nrProcesso", $this->nrProcesso);
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

	//Suspender serviço
	public function Suspender(){

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$mysql = MysqlConexao::getInstance();

		$sql = "UPDATE eco_servico SET `tp_status` = 'S', `dh_cancelamento` = NOW(), `cd_usuario_cancelamento` = :cdUsuarioSessao WHERE cd_servico = :cdServico; UPDATE eco_atividade SET tp_status = 'S', cd_usuario_suspensao = :cdUsuarioSessao AND dh_suspensao = now() WHERE cd_servico = :cdServico;";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
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

	//Reabrir o serviço suspenso
	public function Reabrir(){

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$mysql = MysqlConexao::getInstance();

		$sql = "UPDATE eco_servico SET `tp_status` = 'R', `dh_reabertura` = NOW(), `cd_usuario_reabertura` = :cdUsuarioSessao WHERE cd_servico = :cdServico AND tp_status = 'S'; UPDATE eco_atividade SET tp_status = 'R', cd_usuario_reabertura = :cdUsuarioSessao AND dh_reabertura = now() WHERE cd_servico = :cdServico;";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
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

	public function vincularProposta($cdPropostaCliente){

		$mysql = MysqlConexao::getInstance();

		$sql = "UPDATE eco_servico SET `cd_proposta_cliente` = :cdPropostaCliente WHERE cd_servico = :cdServico";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		$stmt->bindParam(":cdPropostaCliente", $cdPropostaCliente);
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

	//Cadastrar comentário
	public function Comentar($cdAtividade=null, $dsComentario, $snAtividade){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "INSERT INTO `eco_servico_comentario` (`cd_servico`, `cd_atividade`, `ds_comentario`,`sn_transforma_atividade`, `cd_usuario_registro`) VALUES (:cdServico, :cdAtividade, :dsComentario, :snAtividade, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		$stmt->bindParam(":cdAtividade", $cdAtividade);
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

		$sql = "SELECT * FROM `eco_servico_comentario` WHERE cd_servico = :cdServico ORDER BY cd_servico_comentario DESC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
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
						<img src="../../lib/media/img/user-male.png" alt="Usuário" class="media-object img-circle">
						</a>
						</div>
						<div class="media-body bg-white p-a-10">
						<h4 class="media-heading">Nome do usuário</h4>
						<p>
						'.$reg->ds_comentario.'
						</p>
						<p class="text-right pull-right" style="border-top: 1px solid #e9e9e9; padding-top: 3px; width: 15%;">
						'.date("d/m/Y H:i:s", strtotime($reg->dh_registro)).'
						</p>
						</div>
						</div>
						';
					}else{
						echo '
						<div class="media">
						<div class="media-body mdc-bg-green p-a-10 text-white" style="color: white">
						<h4 class="media-heading">Nome do usuário</h4>
						<p>
						'.$reg->ds_comentario.'
						</p>
						<p class="text-right pull-right" style="border-top: 1px solid #e9e9e9; padding-top: 3px; width: 15%;">
						'.date("d/m/Y H:i:s", strtotime($reg->dh_registro)).'
						</p>
						</div>
						<div class="media-right"> <a href="#">
						<img alt="64x64" class="media-object" data-src="holder.js/64x64" src="../../lib/media/img/user-female.png" data-holder-rendered="true" style="width: 64px; height: 64px;">
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

	//Lista os dados em formato de tabela
	public function ListarTable(){

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT s.cd_servico, s.nr_processo, s.tp_status, s.dt_prev_conclusao, e.nm_empreendimento, c.nm_cliente, (SELECT count(*) FROM eco_atividade a WHERE a.cd_servico = s.cd_servico) as qtdAtividade, (SELECT MAX(dt_prev_entrega) FROM eco_atividade WHERE cd_servico = s.cd_servico) as dt_prev_entrega, p.cd_proposta, p.nr_protocolo, p.competencia, p.nr_alteracao FROM eco_servico s, eco_proposta p, eco_proposta_cliente pc, g_empreendimento e, g_cliente c WHERE s.cd_cliente = c.cd_cliente AND s.cd_empreendimento = e.cd_empreendimento AND s.cd_proposta_cliente = pc.cd_proposta_cliente AND p.cd_proposta = pc.cd_proposta ORDER BY cd_servico DESC";
		$stmt = $mysql->prepare($sql);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

					$protocolo = ($reg->nr_alteracao == 0) ? $reg->nr_protocolo."/".$reg->competencia : $reg->nr_protocolo."/".$reg->competencia."/ALT-".$reg->nr_alteracao;

					$dtPrevConclusao = ($reg->dt_prev_entrega == '0000-00-00') ? null : date("d/m/Y", strtotime($reg->dt_prev_entrega));

					echo '
					<tr class="cursorPointer" onclick="viewFormServico(\''.base64_encode($reg->cd_servico).'\')">
					<td>'.$reg->cd_servico.'</td>
					<td>'.$protocolo.'</td>
					<td>'.$reg->nm_cliente.'</td>
					<td>'.$reg->nm_empreendimento.'</td>
					<td>'.$reg->nr_processo.'</td>
					<td>'.$reg->qtdAtividade.'</td>
					<td>'.$dtPrevConclusao.'</td>
					<td>'.$reg->tp_status.'</td>
					</tr>
					';

				}
			}else{

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
	//Lista os dados em formato de select > option
	public function ListarOption(){

	}
	//Construtor genérico
	public function Dados(){

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT s.cd_servico, s.nr_processo, s.tp_status, s.dt_prev_conclusao, s.cd_proposta_cliente, e.cd_empreendimento, e.nm_empreendimento, c.cd_cliente, c.nm_cliente, s.cd_orgao_licenciado FROM eco_servico s, g_empreendimento e, g_cliente c WHERE s.cd_cliente = c.cd_cliente AND s.cd_empreendimento = e.cd_empreendimento AND cd_servico = :cdServico";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$reg = $stmt->fetch(PDO::FETCH_OBJ);

				$this->nrProcesso 			= $reg->nr_processo;
				$this->tpStatus   			= $reg->tp_status;
				$this->dtPrevConclusao  	= $reg->dt_prev_conclusao;
				$this->cdOrgaoLicenciado  	= $reg->cd_orgao_licenciado;
				$this->cdCliente 	  		= $reg->cd_cliente;
				$this->nmCliente 	  		= $reg->nm_cliente;
				$this->cdEmpreendimento		= $reg->cd_empreendimento;
				$this->nmEmpreendimento		= $reg->nm_empreendimento;
				$this->cdPropostaCliente	= $reg->cd_proposta_cliente;

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

	//Solicita documentos do serviço
	public function solicitarDocumento($cdTpDocumento, $cdAtividade = null){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "INSERT INTO eco_doc_servico (cd_servico, cd_tp_documento, cd_atividade, cd_usuario_registro) VALUES (:cdServico, :cdTpDocumento, :cdAtividade, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		$stmt->bindParam(":cdTpDocumento", $cdTpDocumento);
		$stmt->bindParam(":cdAtividade", $cdAtividade);
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

	//Remove documentos do serviço
	public function removerSolDoc($cdDocServico){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "DELETE FROM eco_doc_servico WHERE cd_doc_servico = :cdDocServico";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdDocServico", $cdDocServico);
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

	//Dados documentos do serviço
	public function getSolDoc($cdDocServico){

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT * FROM eco_doc_servico WHERE cd_doc_servico = :cdDocServico";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdDocServico", $cdDocServico);
		$result = $stmt->execute();
		if ($result) {
			return $stmt->fetchAll(PDO::FETCH_OBJ);
		} else {
			$erro = $stmt->errorInfo();
			return $erro[2];
		}

	}


	public static function addAnexoSolicitacao($cdDocServico, $dsAnexo, $fileData)
	{
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "UPDATE eco_doc_servico SET ds_anexo = :dsAnexo, cd_usuario_anexo = :cdUsuarioSessao, file_data = :fileData, dh_anexo = now() WHERE cd_doc_servico = :cdDocServico";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdDocServico", $cdDocServico);
		$stmt->bindParam(":dsAnexo", $dsAnexo);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$stmt->bindParam(":fileData", $fileData);
		$result = $stmt->execute();
		if ($result) {

			return intval($stmt->rowCount());

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

	//Solicita documentos do serviço
	public function listarTableDocumentosSolicitados(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "SELECT ds.cd_doc_servico, ds.cd_tp_documento, td.ds_tp_documento, (SELECT ds_tp_atividade FROM eco_atividade a, eco_tp_atividade tpa WHERE a.cd_atividade = ds.cd_atividade AND a.cd_tp_atividade = tpa.cd_tp_atividade) as ds_tp_atividade, ds.ds_anexo, ds.dh_registro, u.nm_usuario, ds.recebido, ds.file_data FROM eco_doc_servico ds, eco_tp_documento td, g_usuario u WHERE ds.cd_tp_documento = td.cd_tp_documento AND ds.cd_usuario_registro = u.cd_usuario AND ds.cd_servico = :cdServico ORDER BY td.ds_tp_documento ASC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){

				while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

					$recebido = ($reg->recebido == 1) ? 'checked' : null;
					$file = json_decode($reg->file_data, true);
					$fileId = ($file['id']) ? $file['id'] : null;

					$fileLink = $fileId ? '<a href="javascript:void(0)" onclick="openFile(\''. base64_encode($fileId) .'\')">'.$reg->ds_anexo.'</a>' : '<a href="repo/eco/servDoc/'.$this->cdServico.'/'.$reg->cd_doc_servico.'/'.$reg->ds_anexo.'" target="_blank">'.$reg->ds_anexo.'</a>';

					echo '
					<tr>
						<td class="text-center">
							<input type="checkbox" name="docServico[]" data-cod="'.base64_encode($reg->cd_doc_servico).'" id="docServ_'.$reg->cd_doc_servico.'" class="filled-in chk-col-light-green" '.$recebido.' onchange="receberDoc(this)">
							<label for="docServ_'.$reg->cd_doc_servico.'"></label>
						</td>
						<td>'.$reg->ds_tp_documento.'</td>
						<td>'.$reg->ds_tp_atividade.'</td>
						<td>'.date('d/m/Y H:i:s', strtotime($reg->dh_registro)).'</td>
						<td>'.$reg->nm_usuario.'</td>
						<td>
							'.$fileLink.'
						</td>
						<td class="text-center">
							<ul class="list-unstyled">
								<li class="dropdown">
									<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
										<i class="material-icons mdc-text-grey-600">more_vert</i>
									</a>
									<ul class="dropdown-menu pull-left">
										<li role="presentation">
											<a href="#modalAddAnexoSolDoc" data-toggle="modal" class="waves-effect waves-block" onclick="modalAnexoSolDoc('.$reg->cd_doc_servico.')"><i class="material-icons col-indigo font-15">attach_file</i> Anexar</a>
										</li>
										<li role="presentation">
											<a class="waves-effect waves-block" data-serv="'.base64_encode($this->cdServico).'" onclick="removerSolDoc(\''.$reg->cd_doc_servico.'\')"><i class="material-icons col-red font-15">delete</i> Remover</a>
										</li>
										<li role="presentation">
											<a class="waves-effect waves-block"><i class="material-icons font-15">info</i> Detalhes</a>
										</li>
									</ul>
								</li>
							</ul>
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

	public static function getDocumentosServico($cdDocServico = null, $cdServico = null) {
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "SELECT ds.cd_doc_servico, ds.cd_servico, ds.cd_tp_documento, td.ds_tp_documento, ds.ds_anexo, ds.dh_registro, ds.recebido, ds.file_data FROM eco_doc_servico ds, eco_tp_documento td
		WHERE ds.cd_tp_documento = td.cd_tp_documento";
		$sql .= (!is_null($cdDocServico)) ? " AND ds.cd_doc_servico = :cdDocServico" : null;
		$sql .= (!is_null($cdServico)) ? " AND ds.cd_servico = :cdServico" : null;
		$stmt = $mysql->prepare($sql);
		(!is_null($cdDocServico)) ? $stmt->bindParam(":cdDocServico", $cdDocServico) : null;
		(!is_null($cdServico)) ? $stmt->bindParam(":cdServico", $cdServico) : null;
		$result = $stmt->execute();
		if ($result) {
			return $response = $stmt->fetchAll(PDO::FETCH_OBJ);
		}else{
			$erro = $stmt->errorInfo();
			return $erro[2];
		}
	}

	public static function receberDocumento($cdDocServico, $recebido) {
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		var_dump($recebido);

		if ($recebido) {
			$sql = "UPDATE eco_doc_servico SET recebido = :recebido, dh_recebido = NOW(), cd_usuario_recebimento = :cdUsuarioSessao WHERE cd_doc_servico = :cdDocServico";
			$stmt = $mysql->prepare($sql);
			$stmt->bindParam(":cdDocServico", $cdDocServico);
			$stmt->bindParam(":recebido", $recebido);
			$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		} else {
			$sql = "UPDATE eco_doc_servico SET recebido = :recebido, dh_recebido = null, cd_usuario_recebimento = null WHERE cd_doc_servico = :cdDocServico";
			$stmt = $mysql->prepare($sql);
			$stmt->bindParam(":cdDocServico", $cdDocServico);
			$stmt->bindParam(":recebido", $recebido);
		}

		$result = $stmt->execute();
		if ($result) {

			return $stmt->rowCount();

		} else {
			$error = $stmt->errorInfo();
			return $error[2];
		}
	}

	public function getAtividades($tpAtividade=null)
	{
		$mysql = MysqlConexao::getInstance();

		$sql = "
		SELECT
			a.cd_atividade,
			a.ds_atividade,
			a.tp_atividade,
			ta.cd_tp_atividade,
			ta.ds_tp_atividade,
			u.nm_usuario,
			date_format(a.dt_prev_entrega,'%d/%m/%Y') as dt_prev_entrega,
			date_format(a.dh_registro,'%d/%m/%Y') as dh_registro,
			CASE a.tp_status
			WHEN 'R' THEN '<span class=\"col-orange\"><i class=\"material-icons font-18\">refresh</i> REABERTO</span>'
			WHEN 'O' THEN '<span class=\"col-green\"><i class=\"material-icons font-18\">done</i> CONCLUÍDO</span>'
			WHEN 'E' THEN '<span class=\"col-amber\"><i class=\"material-icons font-18\">build</i> TRABALHANDO</span>'
			ELSE tp_status
			END AS tp_status,
			(SELECT count(*) FROM eco_it_atividade WHERE cd_atividade = a.cd_atividade) as total_it_atividade
		FROM 	`eco_tp_atividade` ta, `eco_atividade` a LEFT JOIN `g_usuario` u
		ON 		a.cd_usuario 		= u.cd_usuario
		WHERE 	a.cd_tp_atividade 	= ta.cd_tp_atividade
		AND 	a.cd_servico 		= :cdServico
		
		ORDER BY a.nr_ordem ASC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		// $stmt->bindParam(":tpAtividade", $tpAtividade);
		$result = $stmt->execute();
		if($result){

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		} else {
			$error = $stmt->errorInfo();
			return $error[2];
		}
	}

	public static function getAtividadesArray($cdServico, $tpAtividade=null)
	{
		$mysql = MysqlConexao::getInstance();

		$sql = "
		SELECT
			a.cd_atividade,
			a.ds_atividade,
			a.tp_atividade,
			a.cd_proposta_atividade,
			ta.cd_tp_atividade,
			ta.ds_tp_atividade,
			u.nm_usuario,
			date_format(a.dt_prev_entrega,'%d/%m/%Y') as dt_prev_entrega,
			date_format(a.dh_registro,'%d/%m/%Y') as dh_registro,
			CASE a.tp_status
			WHEN 'R' THEN '<span class=\"col-orange\"><i class=\"material-icons font-18\">refresh</i> REABERTO</span>'
			WHEN 'O' THEN '<span class=\"col-green\"><i class=\"material-icons font-18\">done</i> CONCLUÍDO</span>'
			WHEN 'E' THEN '<span class=\"col-amber\"><i class=\"material-icons font-18\">build</i> TRABALHANDO</span>'
			ELSE tp_status
			END AS tp_status,
			(SELECT count(*) FROM eco_it_atividade WHERE cd_atividade = a.cd_atividade) as total_it_atividade
		FROM 	`eco_tp_atividade` ta, `eco_atividade` a LEFT JOIN `g_usuario` u
		ON 		a.cd_usuario 		= u.cd_usuario
		WHERE 	a.cd_tp_atividade 	= ta.cd_tp_atividade
		AND 	a.cd_servico 		= :cdServico
		
		ORDER BY a.nr_ordem ASC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $cdServico);
		// $stmt->bindParam(":tpAtividade", $tpAtividade);
		$result = $stmt->execute();
		if($result){

			return $stmt->fetchAll(PDO::FETCH_ASSOC);

		} else {
			$error = $stmt->errorInfo();
			return $error[2];
		}
	}

	public function getAtividade($cdItProposta)
	{
		$mysql = MysqlConexao::getInstance();

		$sql = "
		SELECT
			a.cd_atividade,
			a.ds_atividade,
			a.tp_atividade,
			a.cd_itproposta_licenca,
			ta.cd_tp_atividade,
			ta.ds_tp_atividade,
			u.nm_usuario,
			date_format(a.dt_prev_entrega,'%d/%m/%Y') as dt_prev_entrega,
			date_format(a.dh_registro,'%d/%m/%Y') as dh_registro,
			CASE a.tp_status
			WHEN 'R' THEN '<span class=\"col-orange\"><i class=\"material-icons font-18\">refresh</i> REABERTO</span>'
			WHEN 'O' THEN '<span class=\"col-green\"><i class=\"material-icons font-18\">done</i> CONCLUÍDO</span>'
			WHEN 'E' THEN '<span class=\"col-amber\"><i class=\"material-icons font-18\">build</i> TRABALHANDO</span>'
			ELSE tp_status
			END AS tp_status,
			(SELECT count(*) FROM eco_it_atividade WHERE cd_atividade = a.cd_atividade) as total_it_atividade
		FROM 	`eco_tp_atividade` ta, `eco_atividade` a LEFT JOIN `g_usuario` u
		ON 		a.cd_usuario 		= u.cd_usuario
		WHERE 	a.cd_tp_atividade 	= ta.cd_tp_atividade
		AND 	a.cd_servico 		= :cdServico
		AND		a.cd_itproposta_licenca = :cdItProposta
		ORDER BY a.nr_ordem ASC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdServico", $this->cdServico);
		$stmt->bindParam(":cdItProposta", $cdItProposta);
		$result = $stmt->execute();
		if($result){

			return $stmt->fetchAll(PDO::FETCH_OBJ);

		} else {
			$error = $stmt->errorInfo();
			return $error[2];
		}
	}

    public static function getServicosByProposta($cdProposta) {
        $mysql = MysqlConexao::getInstance();

        $sql = "
        SELECT 	*
        FROM 	eco_servico s,
                eco_proposta p,
                eco_proposta_cliente pc
        WHERE 	p.cd_proposta 			= pc.cd_proposta
        AND 	pc.cd_proposta_cliente 	= s.cd_proposta_cliente
        AND 	p.cd_proposta 			= :cdProposta
        ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdProposta", $cdProposta);
        $result = $stmt->execute();
        if ($result) {

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function getServicoByAtividadeFase($cdAtividadeFase) {
        $mysql = MysqlConexao::getInstance();

        $sql = "
        SELECT 	s.cd_servico,
				p.cd_proposta,
				p.nr_alteracao,
				p.nr_protocolo,
				p.competencia,
				c.nm_cliente,
				e.nm_empreendimento
		FROM 	eco_servico s,
				eco_proposta p,
				eco_proposta_cliente pc,
				eco_atividade a,
				eco_atividade_fase af,
				g_cliente c,
				g_empreendimento e
		WHERE 	p.cd_proposta 			= pc.cd_proposta
		AND 	pc.cd_proposta_cliente 	= s.cd_proposta_cliente
		AND		a.cd_servico			= s.cd_servico
		AND     a.cd_atividade			= af.cd_atividade
		AND 	c.cd_cliente			= pc.cd_cliente
		AND 	e.cd_empreendimento		= pc.cd_empreendimento
		AND 	af.cd_atividade_fase	= :cdAtividadeFase
        ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdAtividadeFase", $cdAtividadeFase);
        $result = $stmt->execute();
        if ($result) {

            return $stmt->fetch(PDO::FETCH_OBJ);

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function getServicoByAtividade($cdAtividade) {
        $mysql = MysqlConexao::getInstance();

        $sql = "
        SELECT 	s.cd_servico,
				p.cd_proposta,
				p.nr_alteracao,
				p.nr_protocolo,
				p.competencia,
				c.nm_cliente,
				e.nm_empreendimento
		FROM 	eco_servico s,
				eco_proposta p,
				eco_proposta_cliente pc,
				eco_atividade a,
				g_cliente c,
				g_empreendimento e
		WHERE 	p.cd_proposta 			= pc.cd_proposta
		AND 	pc.cd_proposta_cliente 	= s.cd_proposta_cliente
		AND		a.cd_servico			= s.cd_servico
		AND 	c.cd_cliente			= pc.cd_cliente
		AND 	e.cd_empreendimento		= pc.cd_empreendimento
		AND 	a.cd_atividade			= :cdAtividade
        ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdAtividade", $cdAtividade);
        $result = $stmt->execute();
        if ($result) {

            return $stmt->fetch(PDO::FETCH_OBJ);

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }

    public static function getServicoByItAtividade($cdItAtividade) {
        $mysql = MysqlConexao::getInstance();

        $sql = "
        SELECT 	s.cd_servico,
				p.cd_proposta,
				p.nr_alteracao,
				p.nr_protocolo,
				p.competencia,
				c.nm_cliente,
				e.nm_empreendimento
		FROM 	eco_servico s,
				eco_proposta p,
				eco_proposta_cliente pc,
				eco_atividade a,
				eco_it_atividade ia,
				g_cliente c,
				g_empreendimento e
		WHERE 	p.cd_proposta 			= pc.cd_proposta
		AND 	pc.cd_proposta_cliente 	= s.cd_proposta_cliente
		AND		a.cd_servico			= s.cd_servico
		AND     a.cd_atividade			= ia.cd_atividade
		AND 	c.cd_cliente			= pc.cd_cliente
		AND 	e.cd_empreendimento		= pc.cd_empreendimento
		AND 	ia.cd_it_atividade		=  :cdItAtividade
        ";
        $stmt = $mysql->prepare($sql);
        $stmt->bindParam(":cdItAtividade", $cdItAtividade);
        $result = $stmt->execute();
        if ($result) {

            return $stmt->fetch(PDO::FETCH_OBJ);

        }else{
            $error = $stmt->errorInfo();
            return $error[2];
        }
    }
}

?>