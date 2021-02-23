<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdAtividade 	= $_POST['cdAtividade'];

$atv 		 	= new cAtividade($cdAtividade);

$historico      = $atv->getHistoricoAlteracaoData();

echo "<table class='table table-striped table-hover'>";
echo "<thead>";
echo "<tr>";
echo "<th>Data Anterior</th>";
echo "<th>Data Atual</th>";
echo "<th>Justificativa</th>";
echo "<th>Usuário</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

if (count($historico) > 0) {
    foreach ($historico as $key => $value) {
        ?>
        <tr>
            <td><?php echo date("d/m/Y", strtotime($value->dt_prev_entrega_anterior)) ?></td>
            <td><?php echo date("d/m/Y", strtotime($value->dt_prev_entrega)) ?></td>
            <td><?php echo $value->ds_justificativa ?></td>
            <td><?php echo $value->nm_usuario ?></td>
        </tr>
        <?php
    }
} else {
    ?>
        <tr>
            <td colspan="4" class="text-center">Nenhum registro encontrado</td>
        </tr>
        <?php
}

echo "</tbody>";
echo "</table>";
?>