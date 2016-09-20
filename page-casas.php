<?php get_header(); ?>

<div class="l1">
	<div class="banner-principal">
		<ul>
			<?php $loop = new WP_Query( array( 'post_type' => 'casas', 'posts_per_page' => 4, 'orderby'=> 'rand' ) ); ?>
			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
			<li>
				<div class="foto-banda">
					<?php echo wp_get_attachment_image(get_field('banner_principal'), 'full'); ?>
				</div>
				<div class="tarja-laranja"></div>
				<div class="alinha">
					<div class="logo-banda">
						<?php the_post_thumbnail(); ?>
					</div>
				</div>
			</li>
			<?php endwhile; wp_reset_query();  ?>
		</ul>
	</div>
</div>

<?php
    if(isset($_GET['regiao'])){
        $regiao = $_GET['regiao'];
    }
	if(isset($_GET['ordem'])){
		$ordem = $_GET['ordem'];
	}
	if(isset($_GET['pesquisa'])){
        $pesquisa = $_GET['pesquisa'];
    }
?>

<div class="l3" style="background-color:white; padding-bottom:20px; height:auto;">
	<div class="alinha">
		<header class="regiao" style="margin-top:40px;">
			<img style="margin:4px 0.938em;" src="<?php echo get_template_directory_uri(). '/img/btn-casas-drink.png' ?>" alt="">
			<h2>Bares &amp; casas noturnas</h2>
		</header>
		<div class="filtro">
			<div style="float:left;width:20%;text-align:right;padding-right:20px;">
				<label>Selecione por</label>
			</div>
			<div style="float:left;width:75%;">
				<form method="get" action="<?php echo get_permalink(get_page_by_path('casas' )); ?>">
					<span class="orange">Região:</span>
					<?php $alltags = get_terms('regiao');
					if ($alltags){
					  foreach( $alltags as $tag ) { ?>
						<span>
							<?php if(isset($regiao) && in_array($tag->slug, $regiao)) { ?>
								<input name="regiao[]" type="checkbox" checked value="<?php echo $tag->slug ?>" />
							<?php } else { ?>
								<input name="regiao[]" type="checkbox" value="<?php echo $tag->slug ?>" />
							<?php } ?>
							<?php echo $tag->name; ?>
						</span>
					<?php } } ?>
					</br>
					<span class="orange">Buscar:</span>
					<?php if(isset($pesquisa)) { ?>
						<input name="pesquisa" type="text" value="<?php echo $pesquisa ?>" />
					<?php } else { ?>
						<input name="pesquisa" type="text" />
					<?php } ?>
					<button type="submit">Buscar</button>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
$args_term = array();
if(isset($regiao)){
	$args_term = array(
		'slug' => $regiao,
		'order' => isset($ordem) ? $ordem:'ASC',
	);
}
$alltags = get_terms('regiao', $args_term);
$found = false;
if ($alltags){
  foreach( $alltags as $tag ) {
    $args=array(
      'post_type' => 'tribe_venue',
      'post_status' => 'publish',
      'showposts' => -1,
	  'order' => isset($ordem) ? $ordem:'ASC',
	  'tax_query' => array(
	        array(
	            'taxonomy' => 'regiao', //or tag or custom taxonomy
	            'field' => 'id',
	            'terms' => array($tag->term_id)
	        )
	   )
    );
	if(isset($pesquisa)){
		$args['s'] = $pesquisa;
	}
	$my_query = null;
    $my_query = new WP_Query($args);
    if( $my_query->have_posts() ) {
	?>

<div class="l3" style="background-color:white; padding-bottom:80px; height:auto;">
	<div class="alinha">
		<header class="regiao">
			<img src="<?php echo get_template_directory_uri(). '/img/regiao-img.png' ?>" alt="">
			<h2><?php echo $tag->name ?></h2>
		</header>
		<?php
			$contador = 0;
		    while ($my_query->have_posts()) : $my_query->the_post(); $found=true; ?>
		<?php if($contador == 0) { ?>
		<div class="bares">
			<ul> <?php } ?>
				<li>
					<div class="bar-logo">
						<?php the_post_thumbnail(); ?>
					</div>
					<div class="bar-img">
						<?php echo wp_get_attachment_image(get_field('banner_miniatura'), 'events-thumbnail'); ?>
					</div>
					<div class="bar-shows">
						<a href="<?php echo get_permalink(get_page_by_path('local' )); ?>?slug=<?php echo basename(get_permalink()); ?>">
							<span>pr&oacute;ximos shows</span>
						</a>
						<a class="tooltip maps" title="<?php echo get_field('_VenueCity').' - '.get_field('_VenueState') ; ?>" href="#"></a>
					</div>
					<label><?php echo $contador; ?></label>
				</li>
			<?php if($contador == 3) {  $contador = -1; ?>
			</ul>
		</div>
		<?php }
			$contador++;
			endwhile;
 			if($contador != 0) {?>
			</ul>
		</div>
		<?php } ?>
	</div>
</div>
       <?php
    }
  }
}
wp_reset_query();  // Restore global post data stomped by the_post().
?>

<?php if(!$found){ ?>
	<div class="l3" style="background-color:white; padding-bottom:80px; height:200px;">
		<div class="alinha">
			<label>Nenhum registro encontrado</label>
		</div>
	</div>
<?php } ?>

<?php get_footer(); ?>
