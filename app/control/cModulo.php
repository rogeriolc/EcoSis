<?php

/**
* Módulo
*/
class cModulo extends mModulo
{


	public static function moduloViewMenu($cd_usuario, $busca=""){
		/* PRIMEIRO*/
		$mysql = MysqlConexao::getInstance();

		echo '
		<ul class="list">
			<li class="header">Menu</li>
			<li class="active">
				<a href="../app/">
					<i class="material-icons">home</i>
					<span>Home</span>
				</a>
			</li>
			';

			$sql = "SELECT m.cd_modulo, m.nm_modulo, m.ds_icone FROM g_modulo m, g_modulo_usuario mu WHERE m.sn_ativo = 'S' AND m.cd_modulo = mu.cd_modulo AND mu.cd_usuario = :cd_usuario ORDER BY m.nr_ordem ASC";
			$stmt = $mysql->prepare($sql);
			$stmt->bindParam(":cd_usuario", $cd_usuario);
			$result = $stmt->execute();
			if($result){
				while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
					$cd_modulo = $reg->cd_modulo;
					echo '
					<li>
						<a href="javascript:void(0);" class="menu-toggle">
							'.$reg->ds_icone.'
							<span>'.$reg->nm_modulo.'</span>
						</a>
						<ul class="ml-menu">
							';
							$sql2 = "SELECT p.cd_pagina, p.nm_pagina, p.ds_icone, IF((SELECT count(cd_pagina) FROM g_pagina WHERE cd_pagina_principal = p.cd_pagina) > 0, 'S','N') as sn_tree, p.ds_caminho FROM g_pagina p, g_pagina_usuario pu WHERE pu.cd_pagina = p.cd_pagina AND pu.cd_usuario = :cd_usuario AND p.sn_ativo = 'S' AND sn_principal = 'S' AND p.cd_modulo = :cd_modulo ORDER BY p.nr_ordem ASC";
							$stmt2 = $mysql->prepare($sql2);
							$stmt2->bindParam(":cd_usuario", $cd_usuario);
							$stmt2->bindParam(":cd_modulo", $cd_modulo);
							$result2 = $stmt2->execute();
							if($result2){
								while($reg2 = $stmt2->fetch(PDO::FETCH_OBJ)){
									$cd_pagina 	= $reg2->cd_pagina;
									$sn_tree 	= $reg2->sn_tree;


									if($sn_tree == 'S'){
										echo '
										<li>
											<a href="javascript:void(0);" class="menu-toggle">
												<span>'.$reg2->nm_pagina.'</span>
											</a>
											';
										}else{
											echo '
											<li onclick="pag(\''.$reg2->ds_caminho.'\')">
												<a href="javascript:void(0);">
													<span>'.$reg2->nm_pagina.'</span>
												</a>
												';
											}
											if($sn_tree == 'S'){
												echo '
												<ul class="ml-menu">
													';
													$sql3 = "SELECT p.cd_pagina, p.nm_pagina, p.ds_icone, p.ds_caminho FROM g_pagina p, g_pagina_usuario pu WHERE pu.cd_pagina = p.cd_pagina AND pu.cd_usuario = :cd_usuario AND p.sn_ativo = 'S' AND p.cd_pagina_principal = :cd_pagina ORDER BY p.nr_ordem ASC";
													$stmt3 = $mysql->prepare($sql3);
													$stmt3->bindParam(":cd_usuario", $cd_usuario);
													$stmt3->bindParam(":cd_pagina", $cd_pagina);
													$result3 = $stmt3->execute();
													if($result3){
														while($reg3 = $stmt3->fetch(PDO::FETCH_OBJ)){
															echo '
															<li onclick="pag(\''.$reg3->ds_caminho.'\')">
																<a href="javascript:void(0);">'.$reg3->ds_icone.' <span>'.$reg3->nm_pagina.'</span></a>
															</li>
															';
														}
													}
													echo '
												</ul>
												';
											}
											echo '
										</li>
										';
									}
								}
								echo '
							</ul>
							';
						}
						echo '
					</li>
					';
				}else{
					var_dump($stmt->errorInfo());
				}

				echo '
			</ul>
			';
		}
	}

	?>