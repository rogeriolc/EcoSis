<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdEmpreendimento = $_POST['cdEmpreendimento'];

$nomes = cEmpreendimento::getHistoryName($cdEmpreendimento);

if (count($nomes) > 0) {

	foreach ($nomes as $key => $nome) {
		?>
		<tr>
			<td><?php echo $nome->nm_empreendimento_antigo; ?></td>
			<td><?php echo $nome->nm_empreendimento_novo; ?></td>
			<td><?php echo date('d/m/Y H:i:s', strtotime($nome->dh_registro)); ?></td>
			<td><?php echo $nome->nm_usuario_registro; ?></td>
		</tr>
		<?php
	}

} else {
	?>
	<tr>
		<td colspan="4" class="text-center">Nenhuma modificação de nome foi realizada</td>
	</tr>
	<?php
}

?>