<?php
/**
* Pagina
*/
class cPagina extends mPagina{

    public function listTableCheck(){
    	$mysql = MysqlConexao::getInstance();

    	$sql = "SELECT p.cd_pagina, p.nm_pagina, m.nm_modulo, p.ds_pagina FROM g_pagina p, g_modulo m WHERE p.sn_ativo = 'S' AND m.cd_modulo = p.cd_modulo AND ds_caminho IS NOT NULL ORDER BY p.nr_ordem ASC";
    	$stmt = $mysql->prepare($sql);
    	$stmt->bindParam(":cd_usuario", $cd_usuario);
    	$result = $stmt->execute();
    	if($result){
    		while($reg = $stmt->fetch(PDO::FETCH_OBJ)){
    			echo '
    			<tr>
    				<td><label for="editCheckPagina'.md5($reg->cd_pagina).'">'.$reg->nm_pagina.'</label></td>
    				<td>'.$reg->ds_pagina.'</td>
                    <td>'.$reg->nm_modulo.'</td>
    				<td>
    					<input type="checkbox" id="editCheckPagina'.md5($reg->cd_pagina).'" name="cdPagina[]" value="'.base64_encode($reg->cd_pagina).'" class="filled-in">
    					<label for="editCheckPagina'.md5($reg->cd_pagina).'"></label>
    				</td>
    			</tr>
    			';
    		}
    	}
    }
}
?>