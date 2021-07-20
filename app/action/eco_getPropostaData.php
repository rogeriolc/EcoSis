<?php
session_start();

include '../conf/autoLoad.php';

cSeguranca::validaSessao();

// header('Content-Type: application/json');

// var_dump($_POST);

$cdProposta	= ($_POST["cdProposta"]);

$dadosProposta  = cProposta::getData($cdProposta);
$clientes       = cProposta::getClientesByProposta($cdProposta);

$proposta   = new stdClass();

$proposta->card_selecionado = new stdClass();
$proposta->card_selecionado->key = '';
$proposta->card_selecionado->cd_cliente = '';

$proposta->cd_proposta = $dadosProposta[0]->cd_proposta;
$proposta->clientes = array();
$proposta->dt_prev_entrega = $dadosProposta[0]->dt_prev_conclusao;
$proposta->ds_observacao = $dadosProposta[0]->ds_observacao;
$proposta->vl_proposta = '';
$proposta->fechar = false;
$proposta->able_to_close = ($dadosProposta[0]->total_aprovado === $dadosProposta[0]->total_clientes);
$proposta->nr_protocolo = $dadosProposta[0]->nr_protocolo;
$proposta->nr_alteracao = $dadosProposta[0]->nr_alteracao;

switch ($dadosProposta[0]->tp_status) {
    case 'F':
        $proposta->fechar = true;
    break;
    
    default:
    break;
}

if (count($clientes) > 0) {

    foreach ($clientes as $key => $cliente) {
        $itens = null;
        
        $proposta->clientes[$key] = $cliente;
        $itens = cProposta::getItensProposta($cdProposta, $cliente->cd_proposta_cliente);

        @$proposta->clientes[$key]->itens->assessoria = array();
        @$proposta->clientes[$key]->itens->consultoria = array();

        if (count($itens) > 0) {
            foreach ($itens as $ikey => $item) {

                // var_dump($item['tp_atividade']);

                if ($item['tp_atividade'] == 'A') {
                    $proposta->clientes[$key]->itens->assessoria[] = $item;
                } else if ($item['tp_atividade'] == 'C') {
                    $proposta->clientes[$key]->itens->consultoria[] = $item;
                }

            }
        }
    }

}

echo json_encode($proposta);

// var_dump($proposta);

?>