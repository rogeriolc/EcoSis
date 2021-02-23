<?php
include '../conf/autoLoad.php';

$nmUsuario = $_POST['nmUsuario'];

cSeguranca::validaSessao();

$usuario = new Usuario(null,null,null,null,null,null);
$empresa   = new Empresa(null, null,null);

$usuario->setUsername($nmUsuario);
$cdUsuario = $usuario->returnCdUsuario();
?>
<select class="form-control" name="cdEmpresa" data-live-search="true">
	<?php 
	$empresa->listSelectLogin($cdUsuario);
	?>
</select>
<script type="text/javascript">
	$.AdminBSB.select.activate();
</script>