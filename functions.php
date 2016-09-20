<?php

function showbook_theme_scripts() {
	// Add css.
	wp_enqueue_style( 'basic-sh', get_template_directory_uri() . '/css/basic.css', array(), '1.0' );
    wp_enqueue_style( 'custom-sh', get_template_directory_uri() . '/css/custom.css', array(), '1.0' );
	wp_enqueue_style( 'agenda-showbook', get_template_directory_uri() . '/css/agenda.css', array(), '1.0' );
	wp_enqueue_style( 'tooltip-showbook', get_template_directory_uri() . '/css/tooltipster.bundle.css', array(), '1.0' );
	wp_enqueue_style( 'tooltip-borderless-showbook', get_template_directory_uri() . '/css/tooltipster-sideTip-borderless.min.css', array(), '1.0' );
    wp_enqueue_style('showbook-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/base/jquery-ui.css', false, '1.0', false);

	if (! is_page('home') ){
		wp_enqueue_style( 'pages-sh', get_template_directory_uri() . '/css/pages.css', array(), '1.0' );
	}
	wp_enqueue_style( 'tricks-sh', get_template_directory_uri() . '/css/tricks.css', array(), '1.0' );

	// Theme stylesheet.
	wp_enqueue_style( 'showbook-style', get_stylesheet_uri() );

	wp_enqueue_script('jcycle', get_template_directory_uri() . '/js/jcycle.js', array('jquery'), '1.0');
	wp_enqueue_script('scrollbox', get_template_directory_uri() . '/js/jquery.scrollbox.js', array('jquery'), '1.0');
	wp_enqueue_script('tooltip-showbook-script', get_template_directory_uri() . '/js/tooltipster.bundle.js', array('jquery'), '1.0');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('showbook-maps-script', 'http://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyAUA2WnCbkxbaqd-mD-ejsmGbBZUfTYn-c', array('jquery'), '1.0');
    wp_enqueue_script('showbook-locationpicker-script', get_template_directory_uri() . '/js/locationpicker.jquery.min.js', array('showbook-maps-script'), '1.0');
	wp_enqueue_script('main-script', get_template_directory_uri() . '/js/main.js', array('jquery', 'jquery-ui-datepicker', 'showbook-locationpicker-script'), '1.0');
}
add_action( 'wp_enqueue_scripts', 'showbook_theme_scripts' );

function showbook_add_thumbnail_support(){
	add_post_type_support('tribe_venue', 'thumbnail');
}

add_action( 'init', 'showbook_add_thumbnail_support' );

add_theme_support( 'post-thumbnails', array ('tribe_events', 'tribe_venue', 'artista') );
set_post_thumbnail_size( 210, 190, true );
add_image_size( 'events-thumbnail', 219, 210, true );


function modify_tribe_venues(  $post_type, $args ) {
    if ( $post_type == 'tribe_venue' ) {

        global $wp_post_types;
        $args->public = true;
		$args->publicly_queryable = true;
		$args->show_ui = true;
		$args->exclude_from_search = false;
		$args->show_in_nav_menus = true;

        $wp_post_types[ $post_type ] = $args;
    }
}
add_action( 'registered_post_type', 'modify_tribe_venues', 10, 2);

function showbook_add_query_vars_filter( $vars ){
  $vars[] = "slug";
  return $vars;
}
add_filter( 'query_vars', 'showbook_add_query_vars_filter' );

// Esconde o box de SEO de usuários que não são administradores
if (!current_user_can('administrator')){
    function hide_post_page_options() {
        global $post;
        $hide_post_options = "<style type=\"text/css\">#wpseo_meta, #postexcerpt, #postcustom, #commentstatusdiv { display: none; }</style>";
        echo $hide_post_options;
    }
    add_action( 'admin_head', 'hide_post_page_options');
}

//Muda o label dos campos de miniatura dos cadastros
add_action('do_meta_boxes', 'change_image_box');
function change_image_box()
{
    remove_meta_box( 'postimagediv', 'artista', 'side' );
    add_meta_box('postimagediv', __('Imagem destacada (210x210)'), 'post_thumbnail_meta_box', 'artista', 'side', 'low');
    remove_meta_box( 'postimagediv', 'tribe_events', 'side' );
    add_meta_box('postimagediv', __('Imagem destacada (180x180)'), 'post_thumbnail_meta_box', 'tribe_events', 'side', 'low');
    remove_meta_box( 'postimagediv', 'tribe_venue', 'side' );
    add_meta_box('postimagediv', __('Imagem destacada (210x165)'), 'post_thumbnail_meta_box', 'tribe_venue', 'side', 'low');
}

// Modifica a prioridade do campo Yoast SEO
function showbook_change_wpseo_metabox_prio( $priority ) {
    $priority = 'low';
    return $priority;
}
add_filter('wpseo_metabox_prio', 'showbook_change_wpseo_metabox_prio');

// Valida os dados passados para o formulário de contato
function showbook_senha_confirmation_validation_filter( $result, $tag ) {
    $tag = new WPCF7_Shortcode($tag);
    if ( 'text-senha' == $tag->name ) {
        $senha = isset( $_POST['text-senha'] ) ? trim( $_POST['text-senha'] ) : '';
        $confirmacao = isset( $_POST['text-confirmacao'] ) ? trim( $_POST['text-confirmacao'] ) : '';

        if ( $senha != $confirmacao ) {
            $result->invalidate( $tag, "Verifique a senha e sua confirmação!" );
        }
    }
    return $result;
}
add_filter( 'wpcf7_validate_text', 'showbook_senha_confirmation_validation_filter', 20, 2 );
add_filter( 'wpcf7_validate_text*', 'showbook_senha_confirmation_validation_filter', 20, 2 );

//Adiciona campo do tipo senha ao Contact Form 7
function cfp($atts, $content = null) {
    extract(shortcode_atts(array( "id" => "", "title" => "", "pwd" => "" ), $atts));
    if(empty($id) || empty($title)) return "";
    $cf7 = do_shortcode('[contact-form-7 id="' . $id . '" title="' . $title . '"]');
    $pwd = explode(',', $pwd);
    foreach($pwd as $p) {
        $p = trim($p);
        $cf7 = preg_replace('/<input type="text" name="' . $p . '"/usi', '<input type="password" name="' . $p . '"', $cf7);
    }
    return $cf7;
}
add_shortcode('cfp', 'cfp');


//Salva dados do contrate artistas
function registra_interesse_artista(){
    global $wpdb;
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $data = $_POST['data'];
    $telefone = $_POST['telefone'];
    $latitude = $_POST['us6-lat'];
    $longitude = $_POST['us6-lon'];
    $artista = $_POST['artista'];

    $values = array(
        'post_title'=> $nome.' - Interessado no artista: '.$artista,
        'post_status'=>'pending',
        'post_author'=> 1,
        'post_type'=>'contato_artista',
        'post_content'=> 'Interesse registrado para o artista: ' . $artista .
         '<br />Nome: ' . $nome .
         '<br />E-mail para contato: ' . $email .
         '<br />Data do evento: ' . $data .
         '<br />Telefone para contato: ' . $telefone,
    );

    $post_id = wp_insert_post($values, true);
    if($post_id > 0){
        $value = array(
            'address'=>'Endereço Selecionado',
            'lat'=>$latitude,
            'lng'=>$longitude,
        );
        update_field('local_do_evento', $value, $post_id );
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail('daniel@showbook.com.br,contato@showbook.com.br', $values['post_title'], $values['post_content'], $headers);
        echo "Obrigado por seu interesse! Entraremos em contato!";
    }
    else {
        echo "Interesse registrado.";
    }
    die();
}
add_action('wp_ajax_RegistraInteresse', 'registra_interesse_artista');
add_action('wp_ajax_nopriv_RegistraInteresse', 'registra_interesse_artista');


function add_custom_taxonomies_artistas_bares() {
  // Add new "Locations" taxonomy to Posts
  register_taxonomy('regiao', 'tribe_venue', array(
    // Hierarchical taxonomy (like categories)
    'hierarchical' => true,
    // This array of options controls the labels displayed in the WordPress Admin UI
    'labels' => array(
      'name' => _x( 'Região', 'taxonomy general name' ),
      'singular_name' => _x( 'Região', 'taxonomy singular name' ),
      'search_items' =>  __( 'Pesquisar Regiões' ),
      'all_items' => __( 'Todas as Regiões' ),
      'parent_item' => __( 'Região Pai' ),
      'parent_item_colon' => __( 'Região Pai:' ),
      'edit_item' => __( 'Editar Região' ),
      'update_item' => __( 'Atualizar Região' ),
      'add_new_item' => __( 'Adicionar nova Região' ),
      'new_item_name' => __( 'Novo nome de região' ),
      'menu_name' => __( 'Regiões' ),
    ),
    // Control the slugs used for this taxonomy
    'rewrite' => array(
      'slug' => 'regiao', // This controls the base slug that will display before each term
      'with_front' => false, // Don't display the category base before "/locations/"
      'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
    ),
  ));
}
add_action( 'init', 'add_custom_taxonomies_artistas_bares', 0 );

// Registrando Cadastro de artistas
add_action( 'init', 'cptui_register_my_cpts_artista' );
function cptui_register_my_cpts_artista() {
	$labels = array(
		"name" => __( 'Artistas', 'showbook' ),
		"singular_name" => __( 'Artista', 'showbook' ),
		);

	$args = array(
		"label" => __( 'Artistas', 'showbook' ),
		"labels" => $labels,
		"description" => "Registra artistas que vão comparecer ao evento",
		"public" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "artista", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-money",
		"supports" => array( "title", "editor", "thumbnail", "custom-fields", "comments" ),
		"taxonomies" => array( "category" ),
	);
	register_post_type( "artista", $args );

// End of cptui_register_my_cpts_artista()
}

// Registrando contatos que o artista recebeu via site
add_action( 'init', 'cptui_register_my_cpts_contato_artista' );
function cptui_register_my_cpts_contato_artista() {
	$labels = array(
		"name" => __( 'Contatos de Artista', 'showbook' ),
		"singular_name" => __( 'Contato Artista', 'showbook' ),
		);

	$args = array(
		"label" => __( 'Contatos de Artista', 'showbook' ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "contato_artista", "with_front" => true ),
		"query_var" => true,

		"supports" => array( "title", "editor" ),
	);
	register_post_type( "contato_artista", $args );

// End of cptui_register_my_cpts_contato_artista()
}



if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_campos-do-evento',
		'title' => 'Campos do Evento',
		'fields' => array (
			array (
				'key' => 'field_576815ef2d5ed',
				'label' => 'Artistas',
				'name' => 'artistas',
				'type' => 'relationship',
				'return_format' => 'object',
				'post_type' => array (
					0 => 'artista',
				),
				'taxonomy' => array (
					0 => 'all',
				),
				'filters' => array (
					0 => 'search',
				),
				'result_elements' => array (
					0 => 'post_type',
					1 => 'post_title',
				),
				'max' => '',
			),
			array (
				'key' => 'field_5765acc6011df',
				'label' => 'Banner Principal (1900x700px)',
				'name' => 'banner_principal',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_5765ae3e5dc32',
				'label' => 'Banner Secundario (960x300px)',
				'name' => 'banner_secundario',
				'type' => 'image',
				'save_format' => 'url',
				'preview_size' => 'medium',
				'library' => 'all',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'tribe_events',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_campos-local',
		'title' => 'Campos Local',
		'fields' => array (
            array (
				'key' => 'field_5761da943a37e',
				'label' => 'Banner Principal (1900x700px)',
				'name' => 'banner_principal',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_5761bcb03eas24',
				'label' => 'Banner Miniatura (210x210px)',
				'name' => 'banner_miniatura',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_ds87sfjdk23',
				'label' => 'Resumo Local',
				'name' => 'resumo_local',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => 'Aberto da ..... / Faixa de preço: ...',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5761ef2ac777c',
				'label' => 'Url Facebook',
				'name' => 'url_facebook',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => 'Cole aqui o link da página do facebook',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5761e11822ccf',
				'label' => 'Local Evento',
				'name' => 'local_evento',
				'type' => 'google_map',
				'center_lat' => '',
				'center_lng' => '',
				'zoom' => '',
				'height' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'tribe_venue',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_novas-imagens-artista',
		'title' => 'Novas imagens artista',
		'fields' => array (
			array (
				'key' => 'field_572b2d07053eb',
				'label' => 'Banner Principal (1900x700px)',
				'name' => 'banner_principal',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_5761bcb03e6fb',
				'label' => 'Banner Miniatura (185x185px)',
				'name' => 'banner_miniatura',
				'type' => 'image',
				'save_format' => 'id',
				'preview_size' => 'medium',
				'library' => 'all',
			),
			array (
				'key' => 'field_57322973b15df',
				'label' => 'Rider Tecnico',
				'name' => 'rider_tecnico',
				'type' => 'file',
				'save_format' => 'id',
				'library' => 'all',
			),
			array (
				'key' => 'field_5732299f63bed',
				'label' => 'Release Tecnico',
				'name' => 'release_tecnico',
				'type' => 'file',
				'save_format' => 'url',
				'library' => 'all',
			),
			array (
				'key' => 'field_575974a84aaad',
				'label' => 'Facebook Album Id',
				'name' => 'facebook_album_id',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => 'Copie e cole aqui o Id do album que você quer mostrar (album público)',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5761b076b2c4c',
				'label' => 'ID Playlist Youtube',
				'name' => 'youtube_playlist_id',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => 'Copie e cole aqui o ID da playlist que você quer mostrar (playlist pública)',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5761bbd693bc9',
				'label' => 'Url Facebook',
				'name' => 'url_facebook',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'artista',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));

    //Registra campo local do evento para os interessados no artista
    register_field_group(array (
        'id' => 'acf_local_evento_contato_artista',
        'title' => 'asfdsAF',
        'fields' => array (
            array (
                'key' => 'field_579f5ca642034',
                'label' => 'Local do evento',
                'name' => 'local_do_evento',
                'type' => 'google_map',
                'center_lat' => '',
                'center_lng' => '',
                'zoom' => '',
                'height' => '',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'contato_artista',
                    'order_no' => 0,
                    'group_no' => 0,
                ),
            ),
        ),
        'options' => array (
            'position' => 'normal',
            'layout' => 'no_box',
            'hide_on_screen' => array (
            ),
        ),
        'menu_order' => 0,
    ));

}


function showbook_filter_control_artista( $control ) {
	return is_page('artistas');
}

function showbook_filter_control_casas( $control ) {
	return is_page('casas');
}

function mytheme_customize_register( $wp_customize )
{
    $wp_customize->add_section('showbook_theme_banner_header', array(
        'title'    => __('Banner Inicial', 'showbook_theme'),
        'description' => '',
        'priority' => 120,
    ));

    $wp_customize->add_section('showbook_theme_footer_contact', array(
        'title'    => __('Formulário Contato', 'showbook_theme'),
        'description' => '',
        'priority' => 120,
    ));

    //  =============================
    //  = Image Upload              =
    //  =============================
    $wp_customize->add_setting('showbook_theme_image_banner_artistas', array(
        'capability'        => 'edit_theme_options',
        'type'           => 'option',

    ));

    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'image_banner_artistas', array(
        'label'    => __('Banner principal Artistas', 'showbook'),
        'section'  => 'showbook_theme_banner_header',
        'settings' => 'showbook_theme_image_banner_artistas',
		'active_callback' => 'showbook_filter_control_artista',
    )));

    //  =============================
    //  = Image Upload              =
    //  =============================
    $wp_customize->add_setting('showbook_theme_image_banner_casas', array(
        'capability'        => 'edit_theme_options',
        'type'           => 'option',

    ));

    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'image_banner_casas_noturnas', array(
        'label'    => __('Banner principal Casas Noturnas', 'showbook'),
        'section'  => 'showbook_theme_banner_header',
        'settings' => 'showbook_theme_image_banner_casas',
		'active_callback' => 'showbook_filter_control_casas',
    )));

    //  =============================
    //  = Shortcode Formulário de contato              =
    //  =============================
    $wp_customize->add_setting('showbook_theme_contact_form_shortcode', array(
        'capability'        => 'edit_theme_options',
        'type'           => 'option',

    ));

    $wp_customize->add_control('formulario_contato_footer', array(
        'label'    => __('Formulário de contato Rodapé', 'showbook'),
        'section'  => 'showbook_theme_footer_contact',
        'settings' => 'showbook_theme_contact_form_shortcode',
		'type' => 'text',
    ));


	//  =============================
	//  = Shortcode Formulário Faça Parte
	//  =============================
	$wp_customize->add_setting('showbook_theme_faca_parte_form_shortcode', array(
		'capability'        => 'edit_theme_options',
		'type'           => 'option',

	));

	$wp_customize->add_control('formulario_faca_parte', array(
		'label'    => __('Formulário Faça Parte', 'showbook'),
		'section'  => 'showbook_theme_footer_contact',
		'settings' => 'showbook_theme_faca_parte_form_shortcode',
		'type' => 'text',
	));

}
add_action( 'customize_register', 'mytheme_customize_register' );

 ?>
