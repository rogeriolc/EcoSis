<?php
session_start();

include '../conf/autoLoad.php';
include '../conf/showErros.php';

cSeguranca::validaSessao();

$cdPropostaLicenca = isset($_GET['cdPropostaLicenca']) ? $_GET['cdPropostaLicenca'] : null;

$html = '';
$html .= '
<!DOCTYPE html>
<html>
<head>
	<title>Contrato</title>
</head>
<body>
	<table>
	<tr>
		<td width="70%">
		</td>
		<td align="justify">
			Proposta de Serviço Nº 001/2019
			Referente a serviços de consultoria e assessoria ambiental no município do Paulista/PE.
		</td>
	</tr>
	</table>

<p>
A(o) Sr(a)
<br>
<strong>FULANO DE TAL</strong>
<br>
Diretor
<br>
Boeckmann Comércio e Serviços
</p>


<p align="justify">
A Calango Meio Ambiente e Tecnologia Ltda. vem pela presente proposta encaminhar para apreciação de V. Sª. detalhamento dos serviços a serem contratados, com estimativa de preços, previsão das atividades e produtos, conforme descrito no tópico que segue.
</p>

<p>
<strong>1.	SERVIÇOS PROPOSTOS E PRODUTOS FINAIS A SEREM ENTREGUES</strong>
</p>

<p align="justify">
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
</p>

<p align="justify">
	Destaca-se que as obrigações da CONTRATADA junto a CONTRATANTE tem seu fim após a entrega dos produtos/conclusão dos serviços acima pontuados. A presente proposta não contempla o acompanhamento da análise dos estudos junto ao órgão licenciador, tampouco cumprimento de condicionantes das licenças e autorizações.
</p>

<p>
<strong>2.	VALORES E FORMA DE PAGAMENTO</strong>
</p>


<p>
<strong>3.	PRAZO DE EXECUÇÃO</strong>
</p>


<p align="justify">
Importante salientar que dos prazos supramencionados iniciam-se após a entrega de toda documentação necessária para elaboração dos produtos.
</p>

<p>
<strong>4.	DISPOSIÇÕES GERAIS</strong>
</p>
<p align="justify">

	Não estão incluídos na presente proposta a elaboração de outros estudos ambientais que porventura venham a ser exigidos pelos órgãos competentes.
	Todas as despesas eventuais necessárias para a realização dos serviços não estão contempladas na presente proposta e deverão ser pagas pela CONTRATANTE (taxas, custas, cópias além das pontuadas no item 1). Também é de responsabilidade da CONTRATANTE e incorrerão em adicional de valor, alterações dos produtos em virtude de, por exemplo, alteração de projeto.
</p>

<p>
<strong>5.	VALIDADE DA PROPOSTA</strong>
</p>

	A presente proposta tem validade de 30 (trinta) dias.

<p align="right">
Recife, '.date("d").' de '.date("F").' de '.date("Y").'
</p>

<br>
<br>

<p align="center">
<strong>MAGDA SIMONE LEITE PEREIRA CRUZ</strong>
<br>
Sócio – administradora
</p>


';

$html .= '
</body>
</html>
';

require_once("../../lib/plugins/dompdf/dompdf_config.inc.php");

/* Cria a instância */
$dompdf = new DOMPDF();

/* Carrega seu HTML */
$dompdf->load_html($html);

/* Renderiza */
$dompdf->render();

/* Exibe  */
$dompdf->stream(
	"Contrato.pdf", /* Nome do arquivo de saída */
	array(
		"Attachment" => false /* Para download, altere para true */
	)
);