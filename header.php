<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php bloginfo( 'name' ); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="img/favicon.ico" rel="shortcut icon" />
    <meta name="description" content="" />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="rating" content="General" />
    <meta name="author" content="Trustcode" />
    <link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,500|Ubuntu Condensed' rel='stylesheet' type='text/css'>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body>
    <header>
        <div class="menu-topo menu-interno">
            <div class="alinha">
                <a href="<?php echo get_permalink(get_page_by_path('home')); ?>">
            		<div class="logo-topo">
                        Show Book
            		</div>
                </a>
        		<nav>
        			<ul>
        				<li><a href="<?php echo get_permalink(get_page_by_path('artistas' )); ?>" class="btn01">Artistas</a></li>
        				<li><a href="<?php echo get_permalink(get_page_by_path('casas' )) ?>" class="btn02">Bares e Casas Noturnas</a></li>
        				<li><a href="#" id="btn-faca-parte-menu" class="btn03">Fa&ccedil;a Parte</a></li>
        				<li><a href="<?php echo get_permalink(get_page_by_path('artistas')); ?>" class="btn04">Contrate</a></li>
        				<li><a href="https://www.facebook.com/showbookagencia/" target="_blank" class="btn05">Facebook</a></li>
        			</ul>
        		</nav>
            </div>
    	</div>
    </header>

    <div id="div-parceria-1" class="modal-parceria">
        <div id="fecha-parceria-1" class="fecha-parceria">
            Fechar
        </div>
        <img src="<?php echo get_template_directory_uri() . '/img/parceria-1.jpg' ?>" />
    </div>
    <div id="div-parceria-2" class="modal-parceria">
        <div id="fecha-parceria-2" class="fecha-parceria">
            Fechar
        </div>
        <img src="<?php echo get_template_directory_uri() . '/img/parceria-2.jpg' ?>" />
    </div>
    <div id="div-parceria-3" class="modal-parceria">
        <div id="fecha-parceria-3" class="fecha-parceria">
            Fechar
        </div>
        <img src="<?php echo get_template_directory_uri() . '/img/parceria-3.jpg' ?>" />
    </div>

    <div class="faca-parte">
        <header>
            <h3>Fa&ccedil;a Parte</h3>
            <div class="fecha-janela">
                Fechar
            </div>
        </header>
        <div class="content-faca-parte">
            <p>Você Artista, chega de pagar valores absurdos sem nenhum retorno!</p>
            <p>
A ShowBook foi criada para promover e divulgar artistas de todo o país. Dispomos de uma equipe multifocal, de profissionais competentes que
contribuirão com a comunicação de seu dia-a-dia, nas suas redes sociais, gerando conteúdo e na venda de shows.
Aqui na ShowBook você é dono do seu talento,  nós estamos aqui apenas para levar este seu dom para o mundo!
Venha fazer parte desse time também. Preencha o formulário ao lado e escolha a melhor parceria que você pode formar conosco!
            </p>
            <p>ShowBook, fazendo do seu show um espetáculo!</p>
            <div class="logo-faca-parte"></div>
        </div>
        <div class="form-faca-parte">
        	<?php $formulario = get_option('showbook_theme_faca_parte_form_shortcode');
                  echo do_shortcode($formulario); ?>
        </div>
    </div>

    <div class="fundo-escuro"></div>
    <main style="min-height:40%;">
