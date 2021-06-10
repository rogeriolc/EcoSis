let proposta = {
    cd_proposta: '',
    card_selecionado: {
        key: '',
        cd_cliente: ''
    },
    clientes: [],
    dt_prev_entrega: '',
    ds_observacao: '',
    vl_proposta: '',
    fechar: false,
    aprovacao_cliente: false,
    sn_aprovado: '',
    able_to_close: false,
};

function addCliente() {

    proposta.clientes.push({
        cd_proposta_cliente: "",
        cd_cliente: 0,
        cd_empreendimento: 0,
        itens: {
            assessoria: [],
            consultoria: [],
        },
    });

    window.localStorage.setItem("proposta", JSON.stringify(proposta));

    renderListaClientes();
}

function removeCliente(key) {
    proposta.clientes.splice(key, 1);
    window.localStorage.setItem("proposta", JSON.stringify(proposta));
    renderListaClientes();
    renderItensProposta();
}

function removeItemProposta(key, cat) {
    let ckey = proposta.card_selecionado.key;
    let assessoria = proposta.clientes[ckey].itens.assessoria;
    let consultoria = proposta.clientes[ckey].itens.consultoria;

    if (cat == 1) {
        assessoria.splice(key, 1);
    } else if (cat == 2) {
        consultoria.splice(key, 1);
    }

    renderItensProposta();
}

function setClienteProposta(el) {
    let value = $(el).val();
    let key = $(el).closest(".card").data("key");

    proposta.clientes[key].cd_cliente = value;

    window.localStorage.setItem("proposta", JSON.stringify(proposta));
}

function setClienteVinculoProposta(el) {
    let value = $(el).val();
    let key = proposta.card_selecionado.key;

    proposta.clientes[key].cd_cliente_vinculo = value;

    window.localStorage.setItem("proposta", JSON.stringify(proposta));
}

function setEmpreendimentoProposta(el) {
    let value = $(el).val();
    let key = $(el).closest(".card").data("key");

    proposta.clientes[key].cd_empreendimento = value;

    window.localStorage.setItem("proposta", JSON.stringify(proposta));
}

function selectCliente(el) {
    let key = $(el).data("key");
    proposta.card_selecionado.key = key;
    proposta.card_selecionado.cd_cliente = proposta.clientes[key].cd_cliente;

    $("#cadListClientes div.card").removeClass("mdc-bg-deep-purple-100");

    $(el).addClass("mdc-bg-deep-purple-100");

    window.localStorage.setItem("proposta", JSON.stringify(proposta));

    renderOptionTiposAtividade();
    renderOptionClientes();
    renderItensProposta();
    renderAprovacaoCliente();
}

function addAtividadeProposta(a) {
    let val = $(a).val();
    let option = $(a).find("option:selected").data("cat");
    let label = $(a).find("option:selected").data("description");

    //1 - Assessoria
    //2 - Consultoria

    let key = proposta.card_selecionado.key;

    if (option == 1) {
        proposta.clientes[key].itens.assessoria.push({
            cd_proposta_atividade: "",
            cd_tp_atividade: val,
            ds_tp_atividade: label,
            tp_atividade: "A",
            valor: 0,
            desconto: 0,
            dt_prev_entrega: ''
        });
    } else if (option == 2) {
        proposta.clientes[key].itens.consultoria.push({
            cd_proposta_atividade: "",
            cd_tp_atividade: val,
            ds_tp_atividade: label,
            tp_atividade: "C",
            valor: 0,
            desconto: 0,
            dt_prev_entrega: ''
        });
    }

    window.localStorage.setItem("proposta", JSON.stringify(proposta));

    renderItensProposta();

    $(a).find("option:selected").removeAttr("selected");

    //eu te amo
}

function setDtPrevista(el) {
    let valor = $(el).val();

    proposta.dt_prev_entrega = valor;

    window.localStorage.setItem("proposta", JSON.stringify(proposta));
}

function setObservacao(el) {
    let valor = $(el).val();

    proposta.ds_observacao = valor;

    window.localStorage.setItem("proposta", JSON.stringify(proposta));
}

function setValorItem(el) {
    let valor = $(el).val();
    let ckey = $(el).data('ckey');
    let key = $(el).data('key');
    let cat = $(el).data('cat');

    valor = valor.substr(2);
    valor = valor.replace(".", "");
    valor = valor.replace(",", ".");

    if (cat == 1) {
        proposta.clientes[ckey].itens.assessoria[key].valor = valor;
    } else if (cat == 2) {
        proposta.clientes[ckey].itens.consultoria[key].valor = valor;
    }

    window.localStorage.setItem("proposta", JSON.stringify(proposta));

    renderItensProposta();
}

function setDescontoItem(el) {
    let valor = $(el).val();
    let ckey = $(el).data('ckey');
    let key = $(el).data('key');
    let cat = $(el).data('cat');

    valor = valor.substr(2);
    valor = valor.replace(".", "");
    valor = valor.replace(",", ".");

    if (cat == 1) {
        proposta.clientes[ckey].itens.assessoria[key].desconto = valor;
    } else if (cat == 2) {
        proposta.clientes[ckey].itens.consultoria[key].desconto = valor;
    }

    window.localStorage.setItem("proposta", JSON.stringify(proposta));

    renderItensProposta();
}

$("#checkFecharProposta").change(function () {
    let checked = $(this).is(':checked');

    proposta.fechar = checked;

    window.localStorage.setItem("proposta", JSON.stringify(proposta));
});

$("#checkEnviarCliente").change(function () {
    let checked = $(this).is(':checked');

    proposta.aprovacao_cliente = checked;

    window.localStorage.setItem("proposta", JSON.stringify(proposta));
});

$("#checkEnviarCliente").change(function () {
    let checked = $(this).is(':checked');

    proposta.able_to_close = checked;

    window.localStorage.setItem("proposta", JSON.stringify(proposta));
});

function setDataItem(el) {
    let valor = $(el).val();
    let ckey = $(el).data('ckey');
    let key = $(el).data('key');
    let cat = $(el).data('cat');

    if (cat == 1) {
        proposta.clientes[ckey].itens.assessoria[key].dt_prev_entrega = valor;
    } else if (cat == 2) {
        proposta.clientes[ckey].itens.consultoria[key].dt_prev_entrega = valor;
    }

    window.localStorage.setItem("proposta", JSON.stringify(proposta));

    renderItensProposta();
}

function calcularTotalProposta() {
    let total = parseFloat(0);

    if (proposta.clientes.length > 0) {
        for (key in proposta.clientes) {
            for (ikey in proposta.clientes[key].itens) {

                if (proposta.clientes[key].itens[ikey].length > 0) {
                    for (akey in proposta.clientes[key].itens[ikey]) {
                        if (!isNaN(proposta.clientes[key].itens[ikey][akey].valor)) {
                            total += parseFloat(proposta.clientes[key].itens[ikey][akey].valor) - parseFloat(proposta.clientes[key].itens[ikey][akey].desconto);
                        }
                    }
                }

            }
        }
    }

    // $("#totalGeralProposta").html(total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
    // $("#totalGeralProposta").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    total = total.toLocaleString("pt-BR", { style: "currency", currency: "BRL" })
    total = total.substr(3)
    $("#totalGeralProposta").html(total);

}

function cancelarProposta() {
    proposta = {
        cd_proposta: '',
        card_selecionado: {
            key: '',
            cd_cliente: ''
        },
        clientes: [],
        dt_prev_entrega: '',
        ds_observacao: '',
        vl_proposta: '',
    };

    window.localStorage.setItem("proposta", JSON.stringify(proposta));

    renderListaClientes();
    renderItensProposta();

    $("#modalNewProposta button.close").trigger("click");
}

function limparObjetoProposta() {
    proposta = {
        cd_proposta: '',
        card_selecionado: {
            key: '',
            cd_cliente: ''
        },
        clientes: [],
        dt_prev_entrega: '',
        ds_observacao: '',
        vl_proposta: '',
        fechar: false
    };

    window.localStorage.setItem("proposta", JSON.stringify(proposta));
}

/* **************************************************** */
/*              Funcões de Renderização                 */
/* **************************************************** */

function renderListaClientes() {
    $("#cadListClientes").html('');

    proposta = JSON.parse(window.localStorage.getItem("proposta"));

    for (key in proposta.clientes) {

        $("#cadListClientes").append(`
        <div class="card" id="item_`+ key + `" data-key="` + key + `" ondblclick="selectCliente(this)">
            <input type="hidden" name="cdPropostaCliente[]" value="` + proposta.clientes[key].cd_proposta_cliente + `" />
            <div class="body">
                <div class="row">
                    <div class="col-md-2">
                        <i class="material-icons">person</i>
                    </div>
                    <div class="col-md-10">
                        <div class="form-group m-b-0">
                            <select name="cdCliente[]" class="form-control clienteCadProposta" onchange="setClienteProposta(this); renderOptionEmpreendimentos(this);" data-live-search="true">
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <i class="material-icons">domain</i>
                    </div>
                    <div class="col-md-10">
                        <div class="form-group m-b-0">
                            <select name="cdEmpreendimento[]" class="form-control" onchange="setEmpreendimentoProposta(this)" data-live-search="true">
                                <option></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <small><a href="javascript:void(0)" class="col-red" onclick="removeCliente(` + key + `)">Remover</a></small>
                </div>
            </div>
        </div>
        `);
    }

    renderOptionClientes();

    $.AdminBSB.input.activate();
    $.AdminBSB.select.activate();

}

function renderOptionClientes() {
    let input = $("select.clienteCadProposta");
    let input2 = $("select.clienteVinculoProposta");

    input2.find("option:selected").prop("selected", false);

    let key = proposta.card_selecionado.key;

    $.ajax({
        type: 'GET',
        url: 'api/getClientes.php',
        success: function (response) {
            for (var i = 0; i < input.length; i++) {
                for (rkey in response) {

                    let selected = function (clientes) {
                        for (ckey in clientes) {
                            if ((clientes[ckey]['cd_cliente'] == response[rkey].cd_cliente) && ckey == i) {
                                return "selected";
                            }
                        }

                        return;
                    }

                    let selected2 = function (clientes) {
                        for (ckey in clientes) {
                            if ((clientes[ckey].cd_cliente_vinculo == response[rkey].cd_cliente) && ckey == i) {
                                return "selected";
                            }
                        }

                        return;
                    }

                    input.eq(i).append(`<option value="` + response[rkey].cd_cliente + `" ` + selected(proposta.clientes) + `>` + response[rkey].nm_cliente + `</option>`);
                    input2.eq(i).append(`<option value="` + response[rkey].cd_cliente + `" ` + selected2(proposta.clientes) + `>` + response[rkey].nm_cliente + `</option>`);
                }

                renderOptionEmpreendimentos(input[i]);
            }
        },
        complete: function (data) {
            setTimeout(() => {
                $.AdminBSB.select.activate();
                $.AdminBSB.select.refresh();
            }, 1000);
        }
    });
}

function renderOptionEmpreendimentos(el) {

    let key = $(el).closest(".card").data("key");

    const cliente = $(".card[data-key='" + key + "']").find("select[name='cdCliente[]']");
    const input = $(".card[data-key='" + key + "']").find("select[name='cdEmpreendimento[]']");

    axios.get('api/getEmpreendimentosByCliente.php', {
        params: {
            cdCliente: cliente.val()
        }
    })
        .then(response => {
            input.html(`<option value="">&nbsp;</option>`);
            for (var i = 0; i < input.length; i++) {
                for (rkey in response.data) {
                    let selected = function (clientes) {
                        for (ckey in clientes) {
                            if ((clientes[ckey]['cd_empreendimento'] == response.data[rkey].cd_empreendimento) && ckey == key) {
                                return "selected";
                            }
                        }
                    }

                    input.eq(i).append(`<option value="` + response.data[rkey].cd_empreendimento + `" ` + selected(proposta.clientes) + `>` + response.data[rkey].nm_empreendimento + `</option>`);
                }
            }

            // setTimeout(() => {
            $.AdminBSB.select.activate();
            $.AdminBSB.select.refresh();
            // }, 1000);
        })
        .catch(error => {
            console.log(error)
        });
}

function renderOptionTiposAtividade() {
    const input = $("select[name=cdTpAtividade]");

    axios.get('api/getTiposAtividade.php')
        .then(response => {
            input.empty();

            input.html(`<option value=""></option>`);

            for (key in response.data) {
                input.append(`<option value="` + response.data[key].cd_tp_atividade + `" data-cat="` + response.data[key].cd_cat_tp_atividade + `"  data-description="` + response.data[key].ds_tp_atividade + `">` + response.data[key].ds_tp_atividade + `</option>`);
            }

            // setTimeout(() => {
            $.AdminBSB.select.activate();
            $.AdminBSB.select.refresh();
            // }, 1000);
        })
        .catch(error => {
            console.log(error)
        });
}

function renderItensProposta() {
    $("#cadListItensAssessoria, #cadListItensConsultoria").empty();

    let key = proposta.card_selecionado.key;

    let assessoriaTotal = 0;
    let consultoriaTotal = 0;
    let totalProposta = 0;

    if (proposta.clientes.length == 0) {
        $("#assessoriaTotal").html("R$ " + assessoriaTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        $("#consultoriaTotal").html("R$ " + consultoriaTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        $("#totalProposta").html(`<h1 class="text-green"><small class="text-muted">R$</small><br><span style="font-size: 40px">` + totalProposta.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + `</span></h1>`);

        calcularTotalProposta();
        return false;
    }

    let assessoria = proposta.clientes[key].itens.assessoria;
    let consultoria = proposta.clientes[key].itens.consultoria;

    assessoria.reverse();
    consultoria.reverse();

    assessoria.total = 0;
    assessoria.totalDesconto = 0;
    consultoria.total = 0;
    consultoria.totalDesconto = 0;

    if (assessoria.length > 0) {

        for (akey in assessoria) {

            if (assessoria[akey].cd_tp_atividade) {
                let date = null;

                if (assessoria[akey].dt_prev_entrega.search('-') > 0) {
                    date = moment(assessoria[akey].dt_prev_entrega, 'YYYY-MM-DD').format('DD/MM/YYYY');
                } else {
                    date = assessoria[akey].dt_prev_entrega;
                }

                let value = parseFloat(assessoria[akey].valor);
                value = value.toLocaleString('pt-br', { minimumFractionDigits: 2 });

                let desconto = parseFloat(assessoria[akey].desconto);
                desconto = desconto.toLocaleString('pt-br', { minimumFractionDigits: 2 });

                $("#cadListItensAssessoria").append(`
                <div class="card" id="assessoriaCard_` + key + `">
                    <div class="body">
                        <input type="hidden" name="cdTpAtividade[]" class="form-control" value="` + assessoria[akey].cd_tp_atividade + `">
                        <input type="hidden" name="cdPropostaAtividade[]" class="form-control" value="` + assessoria[akey].cd_proposta_atividade + `">
                        <p class="col-deep-purple"><strong>` + assessoria[akey].ds_tp_atividade + `</strong></p>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="vlItem[]" class="form-control inputMoney" onblur="setValorItem(this)" data-ckey="`+ key + `" data-key="` + akey + `" data-cat="1" value="` + value + `" autocomplete="off">
                                        <label class="form-label">Valor</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="descItem[]" class="form-control inputMoney" onblur="setDescontoItem(this)" data-ckey="`+ key + `" data-key="` + akey + `" data-cat="1" value="` + desconto + `" autocomplete="off">
                                        <label class="form-label">Desconto</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <div class="form-line">
                                            <input type="text" name="dtPrevEntrega[]" class="form-control datepicker" onblur="setDataItem(this)" data-ckey="`+ key + `" data-key="` + akey + `" data-cat="1" value="` + date + `" autocomplete="off" />
                                            <label class="form-label">Previsão Entrega</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <small><a href="javascript:void(0)" class="col-red" onclick="removeItemProposta(` + akey + `, 1)">Remover</a></small>
                        </div>
                    </div>
                </div>
                `);

                assessoria.total += (parseFloat(assessoria[akey].valor) - parseFloat(assessoria[akey].desconto));
                assessoria.totalDesconto += parseFloat(assessoria[akey].desconto);
            }

        }

        assessoriaTotal = assessoria.total;

    }

    // $("#assessoriaTotal").html("R$ " + assessoriaTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

    if (consultoria.length > 0) {

        for (akey in consultoria) {

            if (consultoria[akey].cd_tp_atividade) {
                let date = null;

                if (consultoria[akey].dt_prev_entrega.search('-') > 0) {
                    date = moment(consultoria[akey].dt_prev_entrega, 'YYYY-MM-DD').format('DD/MM/YYYY');
                } else {
                    date = consultoria[akey].dt_prev_entrega;
                }

                let value = parseFloat(consultoria[akey].valor);
                value = value.toLocaleString('pt-br', { minimumFractionDigits: 2 });

                let desconto = parseFloat(consultoria[akey].desconto);
                desconto = desconto.toLocaleString('pt-br', { minimumFractionDigits: 2 });

                $("#cadListItensConsultoria").append(`
                <div class="card" id="consultoriaCard_` + key + `">
                    <div class="body">
                        <input type="hidden" name="cdTpAtividade[]" class="form-control" value="` + consultoria[akey].cd_tp_atividade + `">
                        <input type="hidden" name="cdPropostaAtividade[]" class="form-control" value="` + consultoria[akey].cd_proposta_atividade + `">
                        <p class="col-deep-purple"><strong>` + consultoria[akey].ds_tp_atividade + `</strong></p>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="vlItem[]" class="form-control inputMoney" onblur="setValorItem(this)" data-ckey="`+ key + `" data-key="` + akey + `" data-cat="2" value="` + value + `">
                                        <label class="form-label">Valor</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" name="descItem[]" class="form-control inputMoney" onblur="setDescontoItem(this)" data-ckey="`+ key + `" data-key="` + akey + `" data-cat="2" value="` + desconto + `">
                                        <label class="form-label">Desconto</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <div class="form-line">
                                            <input type="text" name="dtPrevEntrega[]" class="form-control datepicker" onblur="setDataItem(this)" data-ckey="`+ key + `" data-key="` + akey + `" data-cat="2" value="` + date + `" />
                                            <label class="form-label">Previsão Entrega</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <small><a href="javascript:void(0)" class="col-red" onclick="removeItemProposta(` + akey + `, 2)">Remover</a></small>
                        </div>
                    </div>
                </div>
                `);

                consultoria.total += (parseFloat(consultoria[akey].valor) - parseFloat(consultoria[akey].desconto));
                consultoria.totalDesconto += parseFloat(consultoria[akey].desconto);
            }

        }

        consultoriaTotal = consultoria.total;

    }

    // $("#consultoriaTotal").html("R$ " + consultoriaTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

    assessoriaTotal = assessoriaTotal.toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
    consultoriaTotal = consultoriaTotal.toLocaleString("pt-BR", { style: "currency", currency: "BRL" });

    $("#assessoriaTotal").html(assessoriaTotal);
    $("#consultoriaTotal").html(consultoriaTotal);

    totalProposta = parseFloat(assessoria.total + consultoria.total);

    totalProposta = totalProposta.toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
    totalProposta = totalProposta.substr(3);

    // $("#totalProposta").html(`<h1 class="text-green"><small class="text-muted">R$</small><br><span style="font-size: 40px">` + totalProposta.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,') + `</span></h1>`);
    $("#totalProposta").html(`<h1 class="text-green"><small class="text-muted">R$</small><br><span style="font-size: 40px">` + totalProposta + `</span></h1>`);

    proposta.clientes[key].vl_proposta = totalProposta;

    window.localStorage.setItem("proposta", JSON.stringify(proposta));

    calcularTotalProposta();

    $(".inputMoney").maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: false });

    $('.datepicker').datetimepicker({
        format: 'DD/MM/YYYY',
        widgetPositioning: {
            horizontal: 'right',
            vertical: 'bottom'
        }
        // widgetParent: $("#cadListItensAssessoria")
    });

    $.AdminBSB.select.refresh();
    $.AdminBSB.input.activate();
}

function renderPropostaData() {
    let proposta = JSON.parse(window.localStorage.getItem('proposta'));
    const ableToClose = proposta.able_to_close;

    //CONVERTER DATA PARA DD/MM/YYYY
    let date = moment(proposta.dt_prev_entrega, 'YYYY-MM-DD').format('DD/MM/YYYY')

    $("#formNewProposta input[name=cdProposta]").val(proposta.cd_proposta);
    $("#formNewProposta input[name=dtPrevConclusaoLicenca]").val(date);
    $("#formNewProposta textarea[name=dsObservacao]").val(proposta.ds_observacao);

    if (proposta.fechar) {
        $("#checkFecharProposta").prop("checked", true);
    } else {
        $("#checkFecharProposta").prop("checked", false);
    }

    if (proposta.aprovacao_cliente) {
        $("#checkEnviarCliente").prop("checked", true);
    } else {
        $("#checkEnviarCliente").prop("checked", false);
    }

    // $("#checkFecharSemAprovacao").prop("disabled", !ableToClose)
}

function renderAprovacaoCliente() {
    const ckey = proposta.card_selecionado.key;
    const cliente = proposta.clientes[ckey];
    const element = $("#propostaClienteAprovacao");

    if (cliente.sn_aprovado === 'S') {
        element.html(`
        <div style="margin-bottom: 20px">
            <p class="mdc-text-green">Aprovada pelo cliente</p>
            <p>Em:</p>
        </div>
        `);
    }
}