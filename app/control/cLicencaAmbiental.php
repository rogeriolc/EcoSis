<?php

class cLicencaAmbiental extends mLicencaAmbiental
{

	public function __construct($cdLicencaAmbiental=null, $cdCliente=null, $cdEmpreendimento=null, $nrProcesso=null, $cdOrgaoLicenciado=null){

		$this->cdLicencaAmbiental = $cdLicencaAmbiental;
		$this->cdCliente 		  = $cdCliente;
		$this->cdEmpreendimento   = $cdEmpreendimento;
		$this->nrProcesso 		  = $nrProcesso;
		$this->cdOrgaoLicenciado  = $cdOrgaoLicenciado;

	}

	public function Cadastro(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "INSERT INTO eco_licenca_ambiental (cd_orgao_licenciado, cd_cliente, cd_empreendimento, nr_processo, cd_empresa, cd_usuario_registro) VALUES (:cdOrgaoLicenciado, :cdCliente, :cdEmpreendimento, :nrProcesso, :cdEmpresa, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdOrgaoLicenciado", $this->cdOrgaoLicenciado);
		$stmt->bindParam(":cdCliente", $this->cdCliente);
		$stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
		$stmt->bindParam(":nrProcesso", $this->nrProcesso);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				return $mysql->lastInsertId();
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

	public function Alterar(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE eco_licenca_ambiental SET cd_orgao_licenciado = :cdOrgaoLicenciado, cd_cliente = :cdCliente, cd_empreendimento = :cdEmpreendimento, nr_processo = :nrProcesso WHERE cd_licenca_ambiental = :cdLicencaAmbiental";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdLicencaAmbiental", $this->cdLicencaAmbiental);
		$stmt->bindParam(":cdOrgaoLicenciado", $this->cdOrgaoLicenciado);
		$stmt->bindParam(":cdCliente", $this->cdCliente);
		$stmt->bindParam(":cdEmpreendimento", $this->cdEmpreendimento);
		$stmt->bindParam(":nrProcesso", $this->nrProcesso);
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

	public function Concluir(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE eco_licenca_ambiental SET tp_status = 'O', dh_conclusao = now(), cd_usuario_conclusao = :cdUsuarioSessao WHERE cd_licenca_ambiental = :cdLicencaAmbiental";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdLicencaAmbiental", $this->cdLicencaAmbiental);
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

	public function Reabrir($dsMotivoReabertura){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE eco_licenca_ambiental SET tp_status = 'R', dh_reabertura = now(), ds_reabertura = :dsMotivoReabertura, cd_usuario_reabertura = :cdUsuarioSessao WHERE cd_licenca_ambiental = :cdLicencaAmbiental";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdLicencaAmbiental", $this->cdLicencaAmbiental);
		$stmt->bindParam(":dsMotivoReabertura", $dsMotivoReabertura);
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

	public function Cancelar($dsCancelamento){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "UPDATE eco_licenca_ambiental SET tp_status = 'C', ds_motivo_cancelamento = :dsCancelamento, dh_cancelamento = now(), cd_usuario_cancelamento = :cdUsuarioSessao WHERE cd_licenca_ambiental = :cdLicencaAmbiental";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdLicencaAmbiental", $this->cdLicencaAmbiental);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$stmt->bindParam(":dsCancelamento", $dsCancelamento);
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

	public function listTable(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "SELECT  l.cd_licenca_ambiental,
		c.nm_cliente,
		e.nm_empreendimento,
		l.nr_processo,
		l.dt_prev_conclusao,
		l.tp_status
		FROM 	eco_licenca_ambiental l,
		g_cliente c,
		g_empreendimento e
		WHERE 	l.cd_cliente 			= c.cd_cliente
		AND   	l.cd_empreendimento 	= e.cd_empreendimento
		AND 	l.cd_empresa 			= :cdEmpresa";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdEmpresa", $cdEmpresa);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

					$this->cdLicencaAmbiental = $reg->cd_licenca_ambiental;

					$qtdObj = self::returnQtdObjetoLicenca();

					echo '
					<tr class="cursorPointer" onclick="viewFormLicencaAmbiental(\''.base64_encode($reg->cd_licenca_ambiental).'\')">
					<td>'.$reg->cd_licenca_ambiental.'</td>
					<td><a href="javascript:void(0)">'.$reg->nm_cliente.'</a></td>
					<td>'.$reg->nm_empreendimento.'</td>
					<td>'.$reg->nr_processo.'</td>
					<td class="text-center"><span class="badge bg-teal">'.$qtdObj.'</span></td>
					<td class="text-center">'.$reg->dt_prev_conclusao.'</td>
					<td class="text-center">'.$reg->tp_status.'</td>
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

			echo '<tr><td>'.$erro[2].'</td></tr>';
		}
	}

	public function Dados(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];
		$cdEmpresa 		 = $_SESSION['cdEmpresa'];

		$sql = "SELECT  l.cd_licenca_ambiental,
		c.nm_cliente,
		c.cd_cliente,
		e.nm_empreendimento,
		e.cd_empreendimento,
		l.nr_processo,
		l.dt_prev_conclusao,
		l.cd_orgao_licenciado,
		l.tp_status
		FROM 	eco_licenca_ambiental l,
		g_cliente c,
		g_empreendimento e
		WHERE 	l.cd_cliente 			= c.cd_cliente
		AND   	l.cd_empreendimento 	= e.cd_empreendimento
		AND 	l.cd_licenca_ambiental	= :cdLicencaAmbiental";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdLicencaAmbiental", $this->cdLicencaAmbiental);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$reg = $stmt->fetch(PDO::FETCH_OBJ);

				$this->cdCliente 		 = $reg->cd_cliente;
				$this->cdEmpreendimento  = $reg->cd_empreendimento;
				$this->nrProcesso 		 = $reg->nr_processo;
				$this->cdOrgaoLicenciado = $reg->cd_orgao_licenciado;
				$this->dtPrevEntrega 	 = $reg->dt_prev_conclusao;
				$this->tpStatus 		 = $reg->tp_status;

			}else{

			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			echo '<tr><td>'.$erro[2].'</td></tr>';
		}
	}

	public function cadastroNaoFinalizado(){

		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "SELECT cd_licenca_ambiental FROM eco_licenca_ambiental WHERE cd_usuario_registro = :cdUsuarioSessao AND tp_status = 'N'";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$reg = $stmt->fetch(PDO::FETCH_OBJ);
				return $reg->cd_licenca_ambiental;
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

	public function addObjetoLicenca($cdObjetoLicenca){
		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$mysql = MysqlConexao::getInstance();

		$sql = "INSERT INTO eco_itlicenca_ambiental (cd_licenca_ambiental, cd_objeto_licenca, cd_usuario_registro) VALUES (:cdLicencaAmbiental, :cdObjetoLicenca, :cdUsuarioSessao);";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdLicencaAmbiental", $this->cdLicencaAmbiental);
		$stmt->bindParam(":cdObjetoLicenca", $cdObjetoLicenca);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				return $mysql->lastInsertId();
			}else{
				return null;
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

	public function removerObjetoLicenca(){
		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$mysql = MysqlConexao::getInstance();

		$sql = "DELETE FROM eco_itlicenca_ambiental WHERE cd_itlicenca_ambiental = :cdItLicencaAmbiental; DELETE FROM eco_itlicenca_fase WHERE cd_itlicenca_ambiental = :cdItLicencaAmbiental";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaAmbiental", $this->cdItLicencaAmbiental);
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

	public function listObjetoLicenca($cdCatObjetoLicenca=null,  $cdItLicencaFase=null){
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT il.cd_itlicenca_ambiental, il.cd_objeto_licenca, obj.ds_objeto_licenca FROM eco_itlicenca_ambiental il, eco_objeto_licenca obj WHERE il.cd_objeto_licenca = obj.cd_objeto_licenca AND il.cd_licenca_ambiental = :cdLicencaAmbiental";
		$sql .= (!is_null($cdCatObjetoLicenca)) ? " AND obj.cd_cat_objeto_licenca = :cdCatObjetoLicenca" : null;
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdLicencaAmbiental", $this->cdLicencaAmbiental);
		(!is_null($cdCatObjetoLicenca)) ? $stmt->bindParam(":cdCatObjetoLicenca", $cdCatObjetoLicenca) : null;
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				for($i=0;$i<$num;$i++){

					$reg = $stmt->fetch(PDO::FETCH_OBJ);

					$cdObjetoLicenca = $reg->cd_objeto_licenca;

					$inCollapse = (!is_null($cdItLicencaFase)) ? "in" : null;

					self::setCdItLicencaAmbiental($reg->cd_itlicenca_ambiental);

					$qtdTotalFases = self::returnQtdFaseObjetoLicenca();
					$qtdFasesConcl = self::returnQtdFaseObjetoLicenca("S");
					$qtdFasesCance = self::returnQtdFaseObjetoLicenca(null, "S");

					$objLic = new cObjetoLicenca($cdObjetoLicenca);
					$objLic->Dados();

					$snPrecisaProtocolo  = $objLic->getSnPedirProtocolo();

					$msgPrecisaProtocolo = ($snPrecisaProtocolo == "S") ? '<div><a href="#modalProtocolo" class="col-amber"><i class="fas fa-exclamation-triangle col-amber"></i>&nbsp; Necessita de Protocolo!</a></div>' : null;

					echo '
					<div class="card col-md-12 no-padding" id="card'.md5($reg->cd_itlicenca_ambiental).'">
					<div class="body no-padding">
					<div class="panel b-a-0 m-b-0 b-b-1-w" style="border-bottom: 1px solid white;" id="panel'.md5($reg->cd_itlicenca_ambiental).'">
					<div class="panel-heading bg-teal b-r-0 b-a-0 b-b-1-w">
					<ul class="m-t-10 pull-right" style="list-style: none;">
					<li class="dropdown">
					<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					<i class="material-icons">more_vert</i>
					</a>
					<ul class="dropdown-menu pull-right">
					<li><a data-toggle="modal" href="#modalFormAddFaseObjetoLicenca" onclick="selecionarObjeto(\''.$i.'\',\''.$reg->cd_itlicenca_ambiental.'\')" class="waves-effect waves-block"><i class="material-icons col-green" style="color: #4CAF50 !important;">add</i> Adicionar Fase</a></li>
					<li><a data-toggle="modal" href="#modalNovaLicenca" onclick="removerObjeto(\''.$i.'\',\''.base64_encode($reg->cd_itlicenca_ambiental).'\')" class="waves-effect waves-block"><i class="material-icons col-red" style="color: #F44336 !important;">delete</i> Remover</a></li>
					</ul>
					</li>
					</ul>
					<h4>
					<a class="col-white" href="#tabObj'.$reg->cd_itlicenca_ambiental.$i.'" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="tabObj'.$reg->cd_itlicenca_ambiental.$i.'">
					<i class="fas fa-clone"></i> &nbsp;'.$reg->ds_objeto_licenca.'
					</a>
					<br/>
					</h4>
					</div>
					<div class="panel-body bg-teal" style="padding: 10px 5px 5px 10px; border-top: 2px solid #c9c9c9;">
					<ul class="list-inline list-unstyled">
					<li>Fases: ( '.$qtdTotalFases.' ) Total; ( '.$qtdFasesConcl.' ) Concluídas; ( '.$qtdFasesCance.' ) Canceladas;</li>
					<li>Integrantes: <i class="fas fa-users"></i></li>
					<li>Data Prev Entrega: <i class="fas fa-calendar"></i></li>
					<li>'.$msgPrecisaProtocolo.'</li>
					</ul>
					</div>
					<div class="panel-body no-padding">
					<div class="collapse '.$inCollapse.'" id="tabObj'.$reg->cd_itlicenca_ambiental.$i.'">
					';
					self::listFaseObjetoLicenca($reg->cd_itlicenca_ambiental, $cdItLicencaFase);
					echo '
					</div>
					</div>
					</div>
					</div>
					</div>
					';
				}
			}else{
				echo 'Nenhum objeto inserido';
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			echo $erro[2];
		}
	}

	public function returnQtdObjetoLicenca($cdCatObjetoLicenca=null){
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT count(il.cd_itlicenca_ambiental) as qtd FROM eco_itlicenca_ambiental il, eco_objeto_licenca obj WHERE il.cd_objeto_licenca = obj.cd_objeto_licenca AND il.cd_licenca_ambiental = :cdLicencaAmbiental";
		$sql .= (!is_null($cdCatObjetoLicenca)) ? " AND obj.cd_cat_objeto_licenca = :cdCatObjetoLicenca" : null;
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdLicencaAmbiental", $this->cdLicencaAmbiental);
		(!is_null($cdCatObjetoLicenca)) ? $stmt->bindParam(":cdCatObjetoLicenca", $cdCatObjetoLicenca) : null;
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$reg = $stmt->fetch(PDO::FETCH_OBJ);
				return $reg->qtd;

			}else{
				echo 'Nenhum objeto inserido';
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			echo $erro[2];
		}
	}

	public function returnQtdFaseObjetoLicenca($snConcluida=null, $snCancelada=null){
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT count(*) as totalFase FROM eco_itlicenca_fase WHERE cd_itlicenca_ambiental = :cdItLicencaAmbiental";
		if(!is_null($snConcluida)){
			$sql .= " AND tp_status = 'O' ";
		}else if(!is_null($snCancelada)){
			$sql .= " AND tp_status = 'C' ";
		}
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaAmbiental", $this->cdItLicencaAmbiental);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$reg = $stmt->fetch(PDO::FETCH_OBJ);
				return $reg->totalFase;

			}else{
				echo 'Nenhum objeto inserido';
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			echo $erro[2];
		}
	}

	public function returnCdLicencaAmbiental($cdItLicenca){
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT cd_licenca_ambiental FROM eco_itlicenca_ambiental WHERE cd_itlicenca_ambiental = :cdItLicenca";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicenca", $cdItLicenca);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$reg = $stmt->fetch(PDO::FETCH_OBJ);
				return $reg->cd_licenca_ambiental;

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

	public function returnCdItLicenca($cdItLicencaFase){
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT cd_itlicenca_ambiental FROM eco_itlicenca_fase WHERE cd_itlicenca_fase = :cdItLicencaFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaFase", $cdItLicencaFase);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$reg = $stmt->fetch(PDO::FETCH_OBJ);
				return $reg->cd_itlicenca_ambiental;

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

	public function returnCdFase($cdItLicencaFase){
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT cd_fase_objeto FROM eco_itlicenca_fase WHERE cd_itlicenca_fase = :cdItLicencaFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaFase", $cdItLicencaFase);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$reg = $stmt->fetch(PDO::FETCH_OBJ);
				return $reg->cd_fase_objeto;

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

	/*==========================================================*/
	/*							FASES 							*/
	/*==========================================================*/

	//adiciona a fase ao cadastro do objeto
	public function addFaseObjeto($cdItLicenca, $cdObjetoLicenca){
		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$mysql = MysqlConexao::getInstance();

		$sql = "INSERT INTO eco_itlicenca_fase (cd_itlicenca_ambiental, cd_licenca_ambiental, cd_fase_objeto, cd_usuario_registro) SELECT :cdItLicenca, :cdLicencaAmbiental, cd_fase_objeto, :cdUsuarioSessao FROM eco_objeto_licenca_fase WHERE cd_objeto_licenca = :cdObjetoLicenca";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicenca", $cdItLicenca);
		$stmt->bindParam(":cdLicencaAmbiental", $this->cdLicencaAmbiental);
		$stmt->bindParam(":cdObjetoLicenca", $cdObjetoLicenca);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				return $mysql->lastInsertId();
			}else{
				return null;
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

	//Adiciona a fase ao objeto inserido na licenca
	public function addFaseObjetoLicenca($cdItLicenca, $cdFase){
		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$cdLicencaAmbiental = self::returnCdLicencaAmbiental($cdItLicenca);

		$mysql = MysqlConexao::getInstance();

		$sql = "INSERT INTO eco_itlicenca_fase (cd_itlicenca_ambiental, cd_licenca_ambiental, cd_fase_objeto, cd_usuario_registro) VALUES (:cdItLicenca, :cdLicencaAmbiental, :cdFase, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicenca", $cdItLicenca);
		$stmt->bindParam(":cdLicencaAmbiental", $cdLicencaAmbiental);
		$stmt->bindParam(":cdFase", $cdFase);
		$stmt->bindParam(":cdUsuarioSessao", $cdUsuarioSessao);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				return $mysql->lastInsertId();
			}else{
				return null;
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

	public function listFaseObjetoLicenca($cdItLicenca, $cdItLicencaFase=null){
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT ilf.cd_itlicenca_fase, il.cd_itlicenca_ambiental, il.cd_objeto_licenca, fo.ds_fase_objeto , fo.cd_fase_objeto, ilf.tp_status
		FROM eco_itlicenca_ambiental il, eco_itlicenca_fase ilf, eco_fase_objeto fo
		WHERE il.cd_itlicenca_ambiental = ilf.cd_itlicenca_ambiental
		AND ilf.cd_fase_objeto 			= fo.cd_fase_objeto
		AND il.cd_itlicenca_ambiental	= :cdItLicenca";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicenca", $cdItLicenca);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				for($i=0;$i<$num;$i++){

					$reg = $stmt->fetch(PDO::FETCH_OBJ);

					$this->cdItLicencaFase = $reg->cd_itlicenca_fase;
					$inCollapse = ($this->cdItLicencaFase == $cdItLicencaFase) ? "in" : null;

					self::dadosFaseObjeto();

					$disabled = "";
					$opMenuSalvar 	= "";
					$opMenuRemover 	= "";
					$opMenuConcluir = "";
					$opMenuCancelar = "";
					$opMenuReabrir 	= "";

					switch ($this->tpStatus) {
						case 'E':
						$dsStatus = '<span class="col-amber">Em andamento</span>';
						$bgStatus = '<div class="pull-right p-a-10 m-t--10 bg-amber text-center" style="width:150px;">Em andamento</div>';
						$dtConclusao 	= $this->dtConclusao;
						$dtCancelamento = $this->dtCancelamento;
						$opMenuSalvar 	= '<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="salvarFase(\'#tabFase'.$i.'It'.$this->cdItLicencaFase.'\')"><i class="material-icons" style="color: #3F51B5 !important;">save</i> Salvar</a></li>';
						$opMenuRemover 	= '<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="removerFase(\'#tabFase'.$i.'It'.$this->cdItLicencaFase.'\')"><i class="material-icons" style="color: #F44336 !important;">delete</i> Remover</a></li>';
						$opMenuConcluir = '
						<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="concluirFase(\'#tabFase'.$i.'It'.$this->cdItLicencaFase.'\')"><i class="material-icons" style="color: #4CAF50 !important;">done</i> Concluir</a></li>';
						$opMenuCancelar = '<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="cancelarFase(\'#tabFase'.$i.'It'.$this->cdItLicencaFase.'\')"><i class="material-icons" style="color: #F44336 !important;">block</i> Cancelar</a></li>';
						break;
						case 'R':
						$dsStatus = '<span class="col-indigo">Reaberta</span>';
						$bgStatus = '<div class="pull-right p-a-10 m-t--10 bg-indigo text-center" style="width:150px;">Reaberta</div>';
						$dtConclusao 	= $this->dtConclusao;
						$dtCancelamento = $this->dtCancelamento;
						$opMenuSalvar 	= '<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="salvarFase(\'#tabFase'.$i.'It'.$this->cdItLicencaFase.'\')"><i class="material-icons" style="color: #3F51B5 !important;">save</i> Salvar</a></li>';
						$opMenuRemover 	= '<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="removerFase(\'#tabFase'.$i.'It'.$this->cdItLicencaFase.'\')"><i class="material-icons" style="color: #F44336 !important;">delete</i> Remover</a></li>';
						$opMenuConcluir = '
						<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="concluirFase(\'#tabFase'.$i.'It'.$this->cdItLicencaFase.'\')"><i class="material-icons" style="color: #4CAF50 !important;">done</i> Concluir</a></li>';
						$opMenuCancelar = '<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="cancelarFase(\'#tabFase'.$i.'It'.$this->cdItLicencaFase.'\')"><i class="material-icons" style="color: #F44336 !important;">block</i> Cancelar</a></li>';
						break;

						case 'O':
						$dsStatus = '<span class="col-green">Concluido</span>';
						$bgStatus = '<div class="pull-right p-a-10 m-t--10 bg-green text-center" style="width:150px;">Concluido</div>';
						$dtConclusao 	= $this->dtConclusao;
						$dtCancelamento = $this->dtCancelamento;
						$disabled = "disabled";
						$opMenuReabrir = '<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="reabrirFase(\'#tabFase'.$i.'It'.$this->cdItLicencaFase.'\')"><i class="material-icons" style="color: #FFC107 !important;">replay</i> Reabrir</a></li>';
						break;

						case 'C':
						$dsStatus = '<span class="col-red">Cancelado</span>';
						$bgStatus = '<div class="pull-right p-a-10 m-t--10 bg-red text-center" style="width:150px;">Cancelado</div>';
						$dtConclusao 	= $this->dtConclusao;
						$dtCancelamento = $this->dtCancelamento;
						$disabled = "disabled";
						$opMenuReabrir = '<li><a data-toggle="modal" href="javascript:void(0)" class="waves-effect waves-block" onclick="reabrirFase(\'#tabFase'.$i.'It'.$this->cdItLicencaFase.'\')"><i class="material-icons" style="color: #FFC107 !important;">replay</i> Reabrir</a></li>';
						break;

						default:
						$dsStatus 		= '';
						$dtConclusao 	= '';
						$dtCancelamento = '';
						$opMenuReabrir  = '';
						break;
					}

					$resp = self::returnResponsaveisFase($this->cdItLicencaFase);

					echo '
					<div class="panel b-a-0 m-b-0 b-b-1-w" id="panelFase'.md5($reg->cd_itlicenca_fase).'">
					<div class="panel-heading bg-deep-purple col-white b-r-0">
					<ul class="m-r--5 pull-right" style="list-style: none; margin-bottom: 0;">
					<li class="dropdown">
					<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					<i class="material-icons">more_vert</i>
					</a>
					<ul class="dropdown-menu pull-right">
					'.$opMenuSalvar.'
					'.$opMenuRemover.'
					'.$opMenuConcluir.'
					'.$opMenuCancelar.'
					'.$opMenuReabrir.'
					</ul>
					</li>
					</ul>
					<div class="pull-right">'.$bgStatus.'</div>
					<a class="col-white" role="button" data-toggle="collapse" href="#tabFase'.$i.'It'.$this->cdItLicencaFase.'" aria-expanded="false" aria-controls="tabFase'.$i.'It'.$this->cdItLicencaFase.'">
					<i class="fas fa-chevron-right"></i> &nbsp;'.$reg->ds_fase_objeto.'
					</a>
					</div>
					<div class="panel-body no-padding">
					<div class="collapse '.$inCollapse.'" id="tabFase'.$i.'It'.$this->cdItLicencaFase.'">
					<input type="hidden" name="cdItLicencaAmbiental" value="'.base64_encode($cdItLicenca).'" />
					<input type="hidden" name="cdItLicencaFase" value="'.base64_encode($reg->cd_itlicenca_fase).'" />
					<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs tab-nav-right" role="tablist" style="margin-left: 3px; border-bottom: none; border-right: 2px solid #eee;">
					<li role="presentation" class="active">
					<a href="#optFase'.$i.'InfoIt'.$this->cdItLicencaFase.'" data-toggle="tab">
					<i class="material-icons">info</i>
					</a>
					</li>
					<li role="presentation">
					<a href="#optFase'.$i.'ResponsavelIt'.$this->cdItLicencaFase.'" data-toggle="tab">
					<i class="material-icons">face</i>
					</a>
					</li>
					<li role="presentation">
					<a href="#optFase'.$i.'MensagensIt'.$this->cdItLicencaFase.'" data-toggle="tab">
					<i class="material-icons">comment</i>
					</a>
					</li>
					</ul>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-11 col-lg-11">
					<!-- Tab panes -->
					<div class="tab-content p-a-10">
					<div role="tabpanel" class="tab-pane fade in active" id="optFase'.$i.'InfoIt'.$this->cdItLicencaFase.'">
					<br/>
					<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
					<div class="form-group">
					<div class="form-line">
					<label>Descrição</label>
					<textarea class="form-control no-resize auto-growth" name="dsFase" rows="3" '.$disabled.' placeholder="Digite o que será feito na fase...">'.$this->dsFase.'</textarea>
					</div>
					</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
					<div class="form-group">
					<div class="form-line">
					<label>Data Prev. Entrega</label>
					<input class="form-control datepicker" name="dtPrevEntrega" type="text" value="'.$this->dtPrevEntrega.'"  '.$disabled.' placeholder="dd/mm/yyyy" />
					</div>
					</div>
					</div>
					</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="optFase'.$i.'ResponsavelIt'.$this->cdItLicencaFase.'"  '.$disabled.'>
					<br/>
					<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="form-group">
					<label>Responsáveis</label>
					<select name="cdResponsavel" class="form-control show-tick" data-live-search="true" multiple  '.$disabled.'>
					<option></option>
					';
					cCliente::staticListOption($resp);
					echo '
					</select>
					</div>
					</div>
					</div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="optFase'.$i.'MensagensIt'.$this->cdItLicencaFase.'">
					<form class="formComentarioFase" method="POST">
					<input type="hidden" name="cdItLicencaFase" value="'.base64_encode($this->cdItLicencaFase).'">
					<br/>
					<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

					<div style="background: #f9f9f9;" id="listComentariosFase'.$this->cdItLicencaFase.'" class="listComentariosFase container-fluid p-a-10">

					';
					self::listComentarioFaseObjeto($reg->cd_itlicenca_fase);
					echo '

					</div>

					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<hr/>
					<div class="form-group">
					<div class="form-line">
					<textarea class="form-control no-resize auto-growth" name="dsComentario" id="dsComentario'.$this->cdItLicencaFase.'" rows="3" placeholder="Digite aqui o seu comentario sobre a fase: '.$reg->ds_fase_objeto.'"></textarea>
					</div>
					</div>
					<div class="form-group text-right">
					<button type="button" onclick="addComentarioFase(this)" data-cod="'.$this->cdItLicencaFase.'" class="btn-sm btn bg-green waves-effect">Comentar</button>
					</div>
					</div>
					</div>
					</form>
					</div>
					</div>
					</div>
					</div>
					<ul class="list-inline" style="border-top: 2px solid #eee; margin-left: 3px;">
					<li>
					<br/>
					<p><span class="text-muted">Status:</span> '.$dsStatus.'</p>
					</li>
					<li>
					<br/>
					<p class="text-muted">Data de conclusão: '.$dtConclusao.'</p>
					</li>
					<li>
					<br/>
					<p class="text-muted">Data de cancelamento: '.$dtCancelamento.'</p>
					</li>
					</ul>
					</div>
					</div>
					</div>
					';
				}
			}else{
				echo 'Nenhuma fase inserida';
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			echo $erro[2];
		}
	}

	public function alterarStatusFase($status){
		$mysql = MysqlConexao::getInstance();

		$sql = "UPDATE eco_itlicenca_fase ilf SET tp_status = :status";

		$cdFase = self::returnCdFase($this->cdItLicencaFase);

		switch ($status) {
			case 'C':

			$sql .= ", dh_cancelamento = now() ";
			$dsMovimentacao = "FASE CANCELADA";

			break;

			case 'O':

			$sql .= ", dh_conclusao = now() ";
			$dsMovimentacao = "FASE CONCLUÍDA";

			break;

			case 'R':

			$sql .= ", dh_conclusao = null, dh_cancelamento = null ";
			$dsMovimentacao = "FASE REABERTA";

			break;

			default:
				# code...
			break;
		}

		$sql .= " WHERE ilf.cd_itlicenca_fase = :cdItLicencaFase";

		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaFase", $this->cdItLicencaFase);
		$stmt->bindParam(":status", $status);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();

			if($num > 0){

				($cdFase > 0) ? self::movFaseLicenca($cdFase, $dsMovimentacao) : null;

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

	public function salvarFaseObjeto(){
		$mysql = MysqlConexao::getInstance();

		$sql = "UPDATE eco_itlicenca_fase ilf SET ds_fase = :dsFase, dt_prev_entrega = :dtPrevEntrega WHERE ilf.cd_itlicenca_fase = :cdItLicencaFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaFase", $this->cdItLicencaFase);
		$stmt->bindParam(":dsFase", $this->dsFase);
		$stmt->bindParam(":dtPrevEntrega", $this->dtPrevEntrega);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				return true;
			}else{
				return true;
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

	public function removerFaseObjeto(){
		$mysql = MysqlConexao::getInstance();

		$sql = "DELETE FROM eco_itlicenca_fase WHERE cd_itlicenca_fase = :cdItLicencaFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaFase", $this->cdItLicencaFase);
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

	public function movFaseLicenca($cdFase, $dsMovimentacao){
		$mysql = MysqlConexao::getInstance();

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$sql = "INSERT INTO eco_mov_itlicenca_fase (cd_itlicenca_fase, cd_fase_objeto, ds_movimentacao, cd_usuario_registro) VALUES (:cdItLicencaFase, :cdFase, :dsMovimentacao, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaFase", $this->cdItLicencaFase);
		$stmt->bindParam(":cdFase", $cdFase);
		$stmt->bindParam(":dsMovimentacao", $dsMovimentacao);
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

	public function addResponsavelFaseObjeto(){

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$mysql = MysqlConexao::getInstance();

		//se retonar um array
		if(gettype($this->cdResponsavel) == 'array'){

			//cria a variavel de sql
			$sql = "";
			//cria array para excluir os usuarios responsaveis que existem na tabela mas não foram selecionados no formulario
			$arrayDel = array();

			//percorre o array dos usuários selecionados no formulario
			foreach ($this->cdResponsavel as $cdUsuario) {
				//desecripta o código do usuário
				$decodeCdUsuario = base64_decode($cdUsuario);

				//monta o sql para inserir os responsáveis. Verifica se o usuário ja está cadastrado para a fase do item para que não cadastre novamente
				$sql .= "INSERT INTO eco_itlicenca_fase_responsavel (cd_itlicenca_fase, cd_usuario_responsavel, cd_usuario_registro) SELECT $this->cdItLicencaFase, $decodeCdUsuario, $cdUsuarioSessao FROM DUAL WHERE NOT EXISTS (SELECT cd_itlicenca_fase_responsavel FROM eco_itlicenca_fase_responsavel WHERE cd_itlicenca_fase = $this->cdItLicencaFase AND cd_usuario_responsavel = $decodeCdUsuario); ";

				//monta o array de usuário decriptado
				$arrayDel[] = $decodeCdUsuario;
			}

			//monta a estrutura de um IN para o sql
			$inDel = implode(",", array_filter($arrayDel));

			//cria a variavel sql para deletar os usuário não selecionados no formulário
			$sqlDel = "DELETE FROM eco_itlicenca_fase_responsavel WHERE cd_itlicenca_fase = $this->cdItLicencaFase AND cd_usuario_responsavel NOT IN ($inDel); ";

			//concatena as duas variaveis sql, para deletar e inserir os responsáveis
			$sql = $sqlDel.$sql;

			$stmt = $mysql->prepare($sql);
			$result = $stmt->execute();
			if ($result) {
				$num = $stmt->rowCount();
				if($num > 0){
					return true;
				}else{
					return true;
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
		// se nao, se retornar vazio, exclui todos os responsáveis da fase
		else if(empty($this->cdResponsavel)){
			$sql = "DELETE FROM eco_itlicenca_fase_responsavel WHERE cd_itlicenca_fase = $this->cdItLicencaFase; ";
			$stmt = $mysql->prepare($sql);
			$result = $stmt->execute();
			if ($result) {
				$num = $stmt->rowCount();
				if($num > 0){
					return true;
				}else{
					return true;
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
		//se nao não tem o que fazer...
		else{
			echo 'parametro incorreto enviado ao adicionar responsável.';
		}
	}

	public function addComentarioFaseObjeto(){

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$mysql = MysqlConexao::getInstance();

		$sql = "INSERT INTO eco_itlicenca_fase_comentario (cd_itlicenca_fase, ds_comentario, cd_usuario_registro) VALUES (:cdItLicencaFase, :dsComentario, :cdUsuarioSessao)";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaFase", $this->cdItLicencaFase);
		$stmt->bindParam(":dsComentario", $this->dsComentario);
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

	public function listComentarioFaseObjeto($cdItLicencaFase){

		$cdUsuarioSessao = $_SESSION['cdUsuario'];

		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT c.ds_comentario, date_format(c.dh_registro, '%d/%m/%Y %H:%i') as dh_registro, u.nm_usuario, u.cd_usuario FROM eco_itlicenca_fase_comentario c, g_usuario u WHERE u.cd_usuario = c.cd_usuario_registro AND cd_itlicenca_fase = $cdItLicencaFase ORDER BY c.cd_itlicenca_fase_comentario ASC";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaFase", $cdItLicencaFase);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				while ($reg = $stmt->fetch(PDO::FETCH_OBJ)) {

					if($cdUsuarioSessao == $reg->cd_usuario){

						echo '
						<div class="row">
						<div class="well well-sm bg-green col-md-6 col-xs-6 col-md-offset-6 col-xs-offset-6">
						<b class="pull-right"><i class="far fa-calendar-alt"></i>&nbsp; '.$reg->dh_registro.'</b>
						<b>'.$reg->nm_usuario.'</b>
						<p>
						'.$reg->ds_comentario.'
						</p>
						</div>
						</div>
						<br/>
						';

					}else{

						echo '
						<div class="row">
						<div class="well well-sm col-md-6 col-xs-6">
						<b class="pull-right"><i class="far fa-calendar-alt"></i>&nbsp; '.$reg->dh_registro.'</b>
						<b>'.$reg->nm_usuario.'</b>
						<p>
						'.$reg->ds_comentario.'
						</p>
						</div>
						</div>
						<br/>
						';
					}
				}
			}else{
				echo 'Nenhum comentário até o momento... ';
			}

		}else{
            //GERAR LOG
			ob_start();
			var_dump($stmt->errorInfo());
			$dsError = ob_get_clean();
			regLog($dsError, basename( __FILE__ ));

			$erro = $stmt->errorInfo();

			echo $erro[2];
		}
	}

	public function dadosFaseObjeto(){
		$mysql = MysqlConexao::getInstance();

		$sql = "SELECT ds_fase, date_format(dt_prev_entrega,'%d/%m/%Y') as dt_prev_entrega, tp_status, date_format(dh_conclusao,'%d/%m/%Y %H:%i:%s') dh_conclusao, date_format(dh_cancelamento,'%d/%m/%Y %H:%i:%s') dh_cancelamento FROM eco_itlicenca_fase ilf WHERE ilf.cd_itlicenca_fase = :cdItLicencaFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaFase", $this->cdItLicencaFase);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){
				$reg = $stmt->fetch(PDO::FETCH_OBJ);

				$this->dsFase 		  = $reg->ds_fase;
				$this->dtPrevEntrega  = ($reg->dt_prev_entrega == '00/00/0000') ? null : $reg->dt_prev_entrega;
				$this->tpStatus 	  = $reg->tp_status;
				$this->dtConclusao 	  = ($reg->dh_conclusao == '00/00/0000') ? null : $reg->dh_conclusao;
				$this->dtCancelamento = ($reg->dh_cancelamento == '00/00/0000') ? null : $reg->dh_cancelamento;

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

	public static function returnResponsaveisFase($cdItLicencaFase){
		$mysql = MysqlConexao::getInstance();

		$arrayResp = array();

		$sql = "SELECT cd_usuario_responsavel FROM eco_itlicenca_fase_responsavel WHERE cd_itlicenca_fase = :cdItLicencaFase";
		$stmt = $mysql->prepare($sql);
		$stmt->bindParam(":cdItLicencaFase", $cdItLicencaFase);
		$result = $stmt->execute();
		if ($result) {
			$num = $stmt->rowCount();
			if($num > 0){

				while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
					$arrayResp[] = intval($reg->cd_usuario_responsavel);
				}

				return $arrayResp;

			}else{
				return $arrayResp;
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

}
?>