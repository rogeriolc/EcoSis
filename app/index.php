<?php
session_start();

include 'conf/autoLoad.php';

cSeguranca::validaSessao();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>EcoSis | Calango</title>
    <!-- Favicon-->
    <link rel="apple-touch-icon" sizes="57x57" href="../lib/media/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../lib/media/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../lib/media/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../lib/media/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../lib/media/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../lib/media/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../lib/media/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../lib/media/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../lib/media/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../lib/media/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../lib/media/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../lib/media/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../lib/media/favicon/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../lib/media/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link ref="//fonts.googleapis.com/css?family=Poppins:300,regular,500,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Hammersmith+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Fontawesome -->
    <link rel="stylesheet" type="text/css" href="../lib/plugins/font-awesome-5/css/fontawesome-all.min.css" />

    <!-- Bootstrap Core Css -->
    <link href="../lib/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../lib/plugins/node-waves/waves.css" rel="stylesheet"/>

    <!-- Animation Css -->
    <link href="../lib/plugins/animate-css/animate.css" rel="stylesheet"/>

    <!-- Bootstrap Material Datetime Picker Css -->
    <!-- <link href="../lib/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" /> -->

    <!-- Morris Chart Css-->
    <link href="../lib/plugins/morrisjs/morris.css" rel="stylesheet"/>

    <!-- Dropzone Css -->
    <link href="../lib/plugins/dropzone/dropzone.css" rel="stylesheet">

    <!-- JQuery DataTable Css -->
    <link href="../lib/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

    <!-- SweetAlert -->
    <!-- <link href="../lib/plugins/sweetalert/sweetalert.css" rel="stylesheet"> -->

    <!-- Bootstrap Select Css -->
    <link href="../lib/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- Bootstrap Spinner Css -->
    <link href="../lib/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    <!-- Full Calendar -->
    <link href="../../lib/plugins/full-calendar/3.9.0/fullcalendar.min.css" rel="stylesheet">

    <!-- Custom Css -->
    <link href="../lib/css/style.css" rel="stylesheet">

    <!-- Material Colors -->
    <link href="../lib/css/material-colors/material-design-color-palette.min.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="../lib/css/themes/all-themes.css" rel="stylesheet"/>
</head>

<body class="theme-deep-purple">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-deep-purple">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Um momento...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <?php include_once 'view/vNavBar.php'; ?>
    <!-- #Top Bar -->

    <!-- Side Bar -->
    <?php include_once 'view/vSideBar.php'; ?>
    <!-- #Side Bar -->

    <div class="text-center" style="position: fixed; top: 0; height: 210px; padding-left: 280px; width: 100%; display: flex; align-items: center; text-align: center; align-content: center; background-image: linear-gradient(-225deg, #65379B 0%, #886AEA 53%, #6457C6 100%); z-index: -1"></div>
    <section class="content" id="divConteudo" style="height: 100vh">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h3 class="col-white">Assessoria</h3>
                </div>
                <?php
                $andamentosAtrasados  = cAtividade::getAndamentosAtrasadas();
                $andamentosConcluidos = cAtividade::getAndamentos("'O'");
                $andamentosParaHoje   = cAtividade::getAndamentos("'R','E'", date('Y-m-d'), date('Y-m-d'));
                $andamentosAbertos    = cAtividade::getAndamentos("'E'");
                ?>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="info-box-3">
                        <div class="icon">
                            <i class="material-icons col-green">done</i>
                        </div>
                        <div class="content">
                            <div class="text">ANDAMENTOS CONCLUÍDOS</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo count($andamentosConcluidos); ?>" data-speed="500" data-fresh-interval="1"><?php echo count($andamentosConcluidos); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="info-box-3">
                        <div class="icon">
                            <i class="material-icons col-amber">access_time</i>
                        </div>
                        <div class="content">
                            <div class="text">ANDAMENTOS EM ABERTO</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo count($andamentosAbertos); ?>" data-speed="500" data-fresh-interval="1"><?php echo count($andamentosAbertos); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="info-box-3">
                        <div class="icon">
                            <i class="material-icons col-indigo">calendar_today</i>
                        </div>
                        <div class="content">
                            <div class="text">PARA HOJE</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo count($andamentosParaHoje); ?>" data-speed="500" data-fresh-interval="1"><?php echo count($andamentosParaHoje); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="info-box-3">
                        <div class="icon">
                            <i class="material-icons col-red">error</i>
                        </div>
                        <div class="content">
                            <div class="text">ATRASADOS</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo count($andamentosAtrasados); ?>" data-speed="500" data-fresh-interval="1"><?php echo count($andamentosAtrasados); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!--
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <p class="title">Andamentos Atrasados</p>
                        </div>
                        <div class="body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Empreendimento</th>
                                        <th>Atividade</th>
                                        <th>Prazo</th>
                                        <th>Dias</th>
                                        <th>Resp.</th>
                                        <th>Cliente Resp.</th>
                                        <th>Orgao Resp.</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($andamentosAtrasados as $andamentoAtrasado) {
                                        $dtPrazo = date("d/m/Y", strtotime($andamentoAtrasado->dt_prazo));
                                        echo "
                                        <tr>
                                        <td>$andamentoAtrasado->nm_cliente</td>
                                        <td>$andamentoAtrasado->ds_empreendimento</td>
                                        <td>$andamentoAtrasado->ds_tp_atividade</td>
                                        <td>$dtPrazo</td>
                                        <td>$andamentoAtrasado->dias_atraso</td>
                                        <td>$andamentoAtrasado->nm_responsavel_atividade</td>
                                        <td>$andamentoAtrasado->nm_cliente_responsavel</td>
                                        <td>$andamentoAtrasado->nm_orgao_responsavel</td>
                                        <td>$andamentoAtrasado->tp_status</td>
                                        </tr>
                                        ";
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            -->

            <?php
            $atividadesAtrasadas    = cAtividade::getAtividadesAtrasadas();
            $atividadesConcluidas   = cAtividade::getAtividades("'C'");
            $atividadesAbertas      = cAtividade::getAtividades("'E','R'");
            $atividadesHoje         = cAtividade::getAtividades("'E','R'", date("Y-m-d"), date("Y-m-d"));
            ?>


            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h3 class="col-deep-purple">Consultoria</h3>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="info-box-3">
                        <div class="icon">
                            <i class="material-icons col-green">done</i>
                        </div>
                        <div class="content">
                            <div class="text">SERVIÇOS CONCLUÍDOS</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo count($atividadesConcluidas); ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($atividadesConcluidas); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="info-box-3">
                        <div class="icon">
                            <i class="material-icons col-amber">access_time</i>
                        </div>
                        <div class="content">
                            <div class="text">SERVIÇOS EM ABERTO</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo count($atividadesAbertas); ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($atividadesAbertas); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="info-box-3">
                        <div class="icon">
                            <i class="material-icons col-indigo">calendar_today</i>
                        </div>
                        <div class="content">
                            <div class="text">PARA HOJE</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo count($atividadesHoje); ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($atividadesHoje); ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <div class="info-box-3">
                        <div class="icon">
                            <i class="material-icons col-red">error</i>
                        </div>
                        <div class="content">
                            <div class="text">ATRASADOS</div>
                            <div class="number count-to" data-from="0" data-to="<?php echo count($atividadesAtrasadas) ?>" data-speed="1000" data-fresh-interval="20"><?php echo count($atividadesAtrasadas) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!--
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <p class="title">Serviços Atrasados</p>
                        </div>
                        <div class="body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Empreendimento</th>
                                        <th>Atividade</th>
                                        <th>Prazo</th>
                                        <th>Dias</th>
                                        <th>Resp. Atividade</th>
                                        <th>Resp. Fase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    foreach ($atividadesAtrasadas as $atividadeAtrasada) {
                                        $dtPrazo = date("d/m/Y", strtotime($atividadeAtrasada->dt_prazo));
                                        echo "
                                        <tr>
                                        <td>$atividadeAtrasada->nm_cliente</td>
                                        <td>$atividadeAtrasada->ds_empreendimento</td>
                                        <td>$atividadeAtrasada->ds_tp_atividade</td>
                                        <td>$dtPrazo</td>
                                        <td>$atividadeAtrasada->dias_atraso</td>
                                        <td>$atividadeAtrasada->nm_responsavel_atividade</td>
                                        <td>$atividadeAtrasada->nm_responsavel_fase</td>
                                        </tr>
                                        ";
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            -->

        </div>
        
      <!--   <div class="container-fluid">
            <div class="card">
                <div id='calendar' class="card-body">

                </div>
            </div>
        </div> -->

    </section>

    <section class="content">
        <div class="container-fluid" id="divResult" style=""></div>
    </section>

    <!-- Jquery Core Js -->
    <script src="../lib/plugins/jquery/jquery.min.js"></script>

    <!-- Jquery Ui -->
    <script src="../lib/plugins/jquery/jquery-ui.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../lib/plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="../lib/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="../lib/plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../lib/plugins/node-waves/waves.js"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="../lib/plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script href="../lib/plugins/raphael/raphael.min.js"></script>
    <script href="../lib/plugins/morrisjs/morris.js"></script>

    <!-- ChartJs -->
    <script src="../lib/plugins/chartjs/Chart.bundle.js"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="../lib/plugins/flot-charts/jquery.flot.js"></script>
    <script src="../lib/plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="../lib/plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="../lib/plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="../lib/plugins/flot-charts/jquery.flot.time.js"></script>

    <!-- Dropzone Plugin Js -->
    <script src="../lib/plugins/dropzone/dropzone.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="../lib/plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src="../lib/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="../lib/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.20/sorting/natural.js"></script>

    <!-- SweetAlert -->
    <script src="../lib/plugins/sweetalert/sweetalert2.min.js"></script>

    <!-- JqueryValidator -->
    <script src="../lib/plugins/jquery-validation/jquery.validate.js"></script>

    <!-- InputMask -->
    <script src="../lib/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <!-- MaskMoney -->
    <script src="../lib/plugins/mask-money/jquery.maskMoney.min.js"></script>

    <!-- Moment Plugin Js -->
    <script src="../lib/plugins/momentjs/moment.js"></script>

    <!-- Autosize Plugin Js -->
    <script src="../lib/plugins/autosize/autosize.js"></script>

    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <!-- <script src="../lib/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script> -->
    <script src="../lib/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>

    <!-- jQuery MD5 -->
    <script src="../lib/plugins/jquery-md5/jquery.md5.js"></script>

    <!-- ListJs -->
    <script src="../lib/plugins/list-js/list.min.js"></script>

    <!-- Jquery Spinner Plugin Js -->
    <script src="../lib/plugins/jquery-spinner/js/jquery.spinner.js"></script>

    <!-- AnimatedModaJs -->
    <script type="text/javascript" src="../lib/plugins/animated-modal-js/animatedModal.min.js"></script>

    <!-- Fullcalendar -->
    <script src="../lib/plugins/full-calendar/3.9.0/fullcalendar.min.js"></script>
    <script src="../lib/plugins/full-calendar/3.9.0/locale/pt-br.js"></script>

    <!-- TinyMCE -->
    <script src="../lib/plugins/tinymce/tinymce.js"></script>

    <!-- Custom Js -->
    <script src="../lib/js/admin.js"></script>
    <!--<script src="../lib/js/pages/index.js"></script>-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.js"></script>

    <!-- Demo Js -->
    <script src="../lib/js/demo.js"></script>
    <!-- Custom Scripts  -->
    <script src="../lib/js/script.js"></script>
    <script type="text/javascript">
        function pag(p){
            $(".page-loader-wrapper").fadeIn("fast");

            let open = new Promise((resolve, reject)=>{

                $("#divConteudo").load(`view/${p}${window.location.search}`);
                $("div.overlay").trigger('click');

                resolve();
            });

            open.then((result) => {
                if (p.substr(-4) === '.php') {
                    localStorage.setItem('page', p.substr(0, p.length-4));
                } else {
                    localStorage.setItem('page', p);
                }
                // $(".page-loader-wrapper").fadeOut("fast");

            })
            .catch(error=>{
                // $(".page-loader-wrapper").fadeOut("fast");
                console.log(error);
            });

        }

        $(function(){

            $(window).scroll(function(){
                var navbar = $("nav.navbar");
                var aTop = navbar.height();
                if($(this).scrollTop()>=aTop){
                    navbar.css("background-image","linear-gradient(-225deg, #65379B 0%, #886AEA 53%, #6457C6 100%)");

                }else{
                    navbar.css("background","none");

                }
            });

            $('#calendar').fullCalendar({
                themeSystem: 'bootstrap3',
                header: {
                  left: 'prev,next today',
                  center: 'title',
                  right: 'month,agendaWeek,agendaDay,listMonth'
              },
              weekNumbers: true,
                eventLimit: true, // allow "more" link when too many events
                events: 'api/events.php'
            });

            initCounters();
            getPage();
        });

        $(document).bind("ajaxSend", function(){
            $(".page-loader-wrapper").fadeOut("fast");
        }).bind("ajaxComplete", function(){
            $(".page-loader-wrapper").fadeOut("fast");
        });

        setInterval(function(){
            var online = navigator.onLine; // true ou false, (há, não há conexão à internet)

            if(!online) {
                $("#msgInternet").css("display","block");
            }else{
                $("#msgInternet").css("display","none");
            }
        }, 5000);

        //Widgets count plugin
        function initCounters() {
            $('.count-to').countTo();
        }

        function getPage() {
            const page = localStorage.getItem('page');

            if (page) {
                $("#divConteudo").html('<p class="col-white">Carregando página...</p>');
                pag(`${page}.php`);
            }

        }
    </script>
</body>

</html>