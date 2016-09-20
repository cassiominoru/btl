<?php
get_header(); ?>


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

<div class="l2">
	<div class="alinha">
		<header>
			<h2>Artistas</h2>
			<a href="<?php echo get_permalink(get_page_by_path('artistas' )); ?>" class="exibir-todos">Exibir Todos</a>
		</header>
		<a href="javascript:next_artista();" class="plus">+</a>
		<span class="plus-sombra"></span>
		<div id="artistas-carossel" class="artistas">
			<ul>
				<?php $loop = new WP_Query( array( 'post_type' => 'artista', 'posts_per_page' => 30 ) ); ?>
				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
					<?php $old = $post; $data_evento=null; ?>
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
								$cidade = get_field('_VenueCity', $event->ID);
								$estado = get_field('_VenueState', $event->ID);
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
				<?php endwhile; wp_reset_query();  ?>
			</ul>
		</div>
	</div>
</div>
<div class="l3">
	<div class="alinha">
	<header>
		<h3>Bares e casas noturnas</h3>
		<a href="<?php echo get_permalink(get_page_by_path('casas' )); ?>" class="exibir-todos">Exibir Todos</a>
	</header>
		<a href="javascript:next_bar();" class="plus">+</a>
		<span class="plus-sombra"></span>
		<div id="bares-carossel" class="bares">
			<ul>
				<?php $loop = new WP_Query( array( 'post_type' => 'tribe_venue', 'posts_per_page' => 30 ) ); ?>
				<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

				<li>
					<div class="bar-logo">
						<?php the_post_thumbnail(); ?>
					</div>
					<div class="bar-img">
						<?php echo wp_get_attachment_image(get_field('secondary_image'), 'events-thumbnail'); ?>
					</div>
					<div class="bar-shows">
						<a href="<?php echo get_permalink(get_page_by_path('local' )); ?>?slug=<?php echo basename(get_permalink()); ?>">
							<span>pr&oacute;ximos shows</span>
						</a>
						<a class="tooltip maps" title="<?php echo get_field('_VenueCity').' - '.get_field('_VenueState') ; ?>" href="#"></a>
					</div>
				</li>
				<?php endwhile; wp_reset_query();  ?>
			</ul>
		</div>
	</div>
</div>

<?php get_footer(); ?>
