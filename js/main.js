jQuery(function($){
    $.datepicker.regional['pt-BR'] = {
            closeText: 'Fechar',
            prevText: '&#x3c;Anterior',
            nextText: 'Pr&oacute;ximo&#x3e;',
            currentText: 'Hoje',
            monthNames: ['Janeiro','Fevereiro','Mar&ccedil;o','Abril','Maio','Junho',
            'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
            'Jul','Ago','Set','Out','Nov','Dez'],
            dayNames: ['Domingo','Segunda-feira','Ter&ccedil;a-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sabado'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
            dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sab'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''};
    $.datepicker.setDefaults($.datepicker.regional['pt-BR']);
});

jQuery(function($) {
    $(document).ready(function() {
        $(function() {
            $('.banner-principal ul')
                .before('<div id="pag">')
                .cycle({
                    fx: 'fade',
                    pager: '#pag',
                    speed: 700,
                    timeout: 8000
                });
        })
        $('#contrate-data').datepicker($.datepicker.regional[ "pt-BR" ]);
        $('#contrate-mapa').click(function(){
            $('#modal-mapa').show();
            $('#modal-mapa-salvar').click(function(){
                $('#modal-mapa').hide();
            });
            $('#us6').locationpicker({
                location: { latitude: -23.55035596176441, longitude: -46.63515797226563},
                zoom: 10,
                inputBinding: {
                    latitudeInput: $('#us6-lat'),
                    longitudeInput: $('#us6-lon'),
                },
                enableAutocomplete: true
            });
            $('#modal-mapa').on('shown.bs.modal', function () {
                $('#us6').locationpicker('autosize');
            });
        });
    });
    $('#artistas-carossel').scrollbox({
        direction: 'h',
        distance: 235,
    });
    $('#bares-carossel').scrollbox({
        direction: 'h',
        distance: 235,
    });
    $('#agenda-carossel').scrollbox({
        direction: 'h',
        distance: 480,
    });

    /*  Abre e fecha faça parte  */
    $(".fecha-janela").click(function() {
        $(".faca-parte").removeClass('mostra-janela');
        $(".banda-detalhe").removeClass('mostra-janela');
        $(".fundo-escuro").removeClass('mostra-fundo');
        setTimeout(function() {
            $(
                ".faca-parte,.banda-detalhe,.fundo-escuro"
            ).hide('fast');
        }, 100);
    })

    $("#btn-faca-parte-menu").click(function() {
        $(".faca-parte").addClass('mostra-janela');
        $(".fundo-escuro").addClass('mostra-fundo');
        setTimeout(function() {
            $(".faca-parte,.fundo-escuro").show('fast');
        }, 500);
    })

    $(".btn-contrate-banda").click(function() {
        $(".quero-show").toggle("fast", function() {});
    });
    $('.tooltip').tooltipster({
        theme: 'tooltipster-borderless'
    });
    $('.exibe-agenda').mouseenter(function() {
        parar = true;
    })
    $('.exibe-agenda').mouseleave(function() {
        parar = false;
    })
    $('#a-parceria-1 span.wpcf7-list-item-label').click(function(){
        $('#div-parceria-1').toggle();
    });
    $('#a-parceria-2 span.wpcf7-list-item-label').click(function(){
        $('#div-parceria-2').toggle();
    });
    $('#a-parceria-3 span.wpcf7-list-item-label').click(function(){
        $('#div-parceria-3').toggle();
    });
    $('#fecha-parceria-1').click(function(){
        $('#div-parceria-1').toggle();
    });
    $('#fecha-parceria-2').click(function(){
        $('#div-parceria-2').toggle();
    });
    $('#fecha-parceria-3').click(function(){
        $('#div-parceria-3').toggle();
    });

    $('#formulario-contrate').submit(function(){
        $('#contrate-submit').prop('disabled', true);
        var contrateArtista = jQuery(this).serialize();
        jQuery.ajax({
            type:"POST",
            url: "/wp-admin/admin-ajax.php",
            data: contrateArtista,
            success:function(data){
                $('#contrate-nome').val('');
                $('#contrate-email').val('');
                $('#contrate-data').val('');
                $('#contrate-telefone').val('');
                alert(data);
                $('#contrate-submit').prop('disabled', false);
            },
            error: function(erro){
                alert(erro);
                $('#contrate-submit').prop('disabled', false);
            }
        });
        return false;
    })
});

function next_artista() {
    jQuery('#artistas-carossel').trigger('forward');
}

function next_bar() {
    jQuery('#bares-carossel').trigger('forward');
}

var indo_esquerda = false;
var margin_agenda = 0;
var parar = false;
function next_agenda(loop) {
    var dias = jQuery('#agenda-carossel .dia-a').size();
    dias += jQuery('#agenda-carossel .dia-b').size();
    var tamanhoTotal = dias * 240;
    if((Math.abs(margin_agenda) + 720)>=tamanhoTotal || margin_agenda == 0)
        indo_esquerda = !indo_esquerda;
    if(indo_esquerda)
        margin_agenda -= 240;
    else
        margin_agenda += 240;
    jQuery('#agenda-carossel').animate({'margin-left': margin_agenda.toString() + 'px'});
}
