<?php get_header(); ?>


<div class="l1">
	<div class="banner-principal">
		<ul>
			<?php $loop = new WP_Query( array( 'post_type' => 'artista', 'posts_per_page' => 4, 'orderby'=> 'rand' ) ); ?>
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
			<img style="margin:4px 0.938em;" src="<?php echo get_template_directory_uri(). '/img/btn-artistas.png' ?>" alt="">
			<h2>Artistas</h2>
		</header>
		<div class="filtro">
			<div style="float:left;width:20%;text-align:right;padding-right:20px;">
				<label>Selecione por</label>
			</div>
			<div style="float:left;width:75%;">
				<form method="get" action="<?php echo get_permalink(get_page_by_path('artistas' )); ?>">
					<span class="orange">Região:</span>
					<?php $alltags = get_terms('category');
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
$alltags = get_terms('category', $args_term);
if ($alltags){
  $found = false;
  foreach( $alltags as $tag ) {
    $args=array(
      'cat' => $tag->term_id,
      'post_type' => 'artista',
      'post_status' => 'publish',
      'showposts' => -1,
	  'order' => isset($ordem) ? $ordem:'ASC',
    );
	if(isset($pesquisa)){
		$args['s'] = $pesquisa;
	}
	$my_query = null;
    $my_query = new WP_Query($args);
    if( $my_query->have_posts() ) {
	?>

<div class="l3" style="background-color:white; padding-bottom:80px;">
	<div class="alinha">
		<header class="artistas">
			<img src="<?php echo get_template_directory_uri(). '/img/regiao-img.png' ?>" alt="">
			<h2><?php echo $tag->name ?></h2>
		</header>
		<div class="artistas">
			<ul>
			<?php
			      while ($my_query->have_posts()) : $my_query->the_post(); ?>
					<?php $old = $post; $data_evento=null; $found=true; ?>
					<li>
						<div class="artista-img">
							<?php the_post_thumbnail(); ?>
							<?php
							$events = get_posts(array(
								'post_type' => 'tribe_events',
								'meta_query' => array(
									array(
										'key' => 'artistas', // name of custom field
										'value' => '"' . get_the_ID() . '"', // matches exaclty "123", not just 123. This prevents a match for "1234"
										'compare' => 'LIKE'
									)
								)
							));
							foreach( $events as $event ):
								$data_evento = get_field('_EventStartDate', $event->ID);
								$id_evento = $event->ID;
							endforeach; ?>
							<?php
								$post = $old;  // reset the post from the main loop
                                $id = $old->ID; ?>
						</div>
						<div class="artista-logo">
							<?php echo wp_get_attachment_image(get_field('banner_miniatura'), 'events-thumbnail'); ?>
						</div>
						<div class="artista-data">
							<?php if(!is_null($data_evento)) { ?>
								<a href="<?php echo get_the_permalink($id_evento); ?>">
									<span> <?php echo date_i18n('d.F /H\H', strtotime($data_evento )) ?> </span>
								</a>
							<?php } else { ?>
								<span>Sem show</span>
							<?php } ?>
							<?php if(isset($id_evento)) { ?>
								<a class="tooltip map" href="#" title="<?php echo tribe_get_city($id_evento).' - '.tribe_get_stateprovince($id_evento); ?>">Local</a>
							<?php } else { ?>
								<a class="map" href="#">Local</a>
							<?php } ?>
						</div>
						<a href="<?php the_permalink(); ?>" class="artista-contrate">Contrate</a>
					</li>
					<?php
			       endwhile;
			     } ?>
			</ul>
		</div>
	</div>
</div>
<?php
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
