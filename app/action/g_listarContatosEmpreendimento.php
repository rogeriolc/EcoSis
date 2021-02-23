<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

$cdEmpreendimento = $_POST['cdEmpreendimento'];

$contatos = cEmpreendimento::getContatos($cdEmpreendimento);

if (count($contatos) > 0) {

	foreach ($contatos as $key => $contato) {
		?>
		<tr>
			<td>
				<input type="hidden" name="cdEmpreendimentoContato[]" value="<?php echo $contato->cd_empreendimento_contato; ?>">
				<div class="form-group">
					<div class="form-line">
						<input type="text" class="form-control" name="nmContato[]" placeholder="Ex: José" value="<?php echo $contato->nm_contato; ?>" />
					</div>
				</div>
			</td>
			<td>
				<div class="form-group">
					<div class="form-line">
						<input type="text" class="form-control" name="nmDepartamento[]" placeholder="Ex: Recursos Humanos" value="<?php echo $contato->nm_departamento; ?>" />
					</div>
				</div>
			</td>
			<td>
				<div class="form-group">
					<div class="form-line">
						<input type="text" class="form-control" name="nmCargo[]" placeholder="Ex: Analista" value="<?php echo $contato->nm_cargo; ?>" />
					</div>
				</div>
			</td>
			<td>
				<div class="form-group">
					<div class="form-line">
						<input type="text" class="form-control" name="nrTelefone[]" placeholder="Ex: (81) 99999-9999" value="<?php echo $contato->nr_telefone; ?>" />
					</div>
				</div>
			</td>
			<td class="text-center text-middle">
				<a href="javascript:void(0)" onclick="removerContato(this)">
					<i class="material-icons col-red">delete</i>&nbsp;
				</a>
			</td>
		</tr>
		<?php
	}

} else {
	?>
	<tr>
		<td colspan="5" class="text-center">Nenhum contato inserido</td>
	</tr>
	<?php
}

?>