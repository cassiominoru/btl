<?php get_header(); ?>

<?php $slug_local = get_query_var( 'slug', '' );
    $args=array(
      'post_type' => 'tribe_venue',
      'post_status' => 'publish',
      'showposts' => -1,
	  'name' => $slug_local,
    );
    $my_query = null;
    $my_query = new WP_Query($args);
    if( $my_query->have_posts() ) {
      while ($my_query->have_posts()) : $my_query->the_post(); ?>

	<div class="l1">
		<div class="banner-secundario">
			<ul>
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
			</ul>
		</div>
	</div>

	<div class="l3" style="background-color:white; padding-bottom:80px; padding-top:40px;height:auto;">
		<div class="alinha">
			<header class="local">
				<img src="<?php echo get_template_directory_uri(). '/img/btn-casas-drink.png' ?>" alt="">
				<h2><?php the_title(); ?></h2>
				<h4><?php echo get_field('_VenueCity').', '.get_field('_VenueState'); ?></h4>
			</header>
			<div class="casas">
				<div class="local-conteudo">
					<?php the_content(); ?>
					<div class="artista-botoes" style="float:left;">
						<a target="_blank" href="<?php echo get_field('url_facebook'); ?>" class="btn01" style="width:8em;">Oficial</a>
					</div>
					<div class="local-endereco">
						<label><?php echo get_field('_VenueAddress').' / '.get_field('_VenuePhone'); ?></label>
						<label><?php echo get_field('_VenueCity').','.get_field('_VenueState'); ?></label></br>
						<label><?php echo get_field('resumo_local'); ?></label>
					</div>
				</div>
				<div class="local-mapa">
					<div>
					<?php
					$location = get_field('local_evento');
					if( ! empty($location) ):
					?>
					<div id="map" style="width: 100%; height: 250px;"></div>
					<script src='http://maps.googleapis.com/maps/api/js?sensor=false' type='text/javascript'></script>

					<script type="text/javascript">
					  //<![CDATA[
						function load() {
						var lat = <?php echo $location['lat']; ?>;
						var lng = <?php echo $location['lng']; ?>;
					// coordinates to latLng
						var latlng = new google.maps.LatLng(lat, lng);
					// map Options
						var myOptions = {
						zoom: 14,
						center: latlng,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					   };
					//draw a map
						var map = new google.maps.Map(document.getElementById("map"), myOptions);
						var marker = new google.maps.Marker({
						position: map.getCenter(),
						map: map
					   });
					}
					// call the function
					   load();
					//]]>
					</script>
					<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="l3 agenda">
	   <div class="alinha">
    		<header>
    			<h4>Agenda</h4>
    		</header>
    		<div class="exibe-agenda">
                <a  href="javascript:next_agenda();" class="plus">+</a>
    			<div id="agenda-carossel" class="exibe-agenda-hidden">
                    <?php
                        $m= date("m");
                        $de= date("d");
                        $y= date("Y");
                        $latest_day = '';
                        $latest_month = '';
                        $count_month = -1;
                        $count_day = -1;
                        $contador = 0;
                        $encontrou_evento = false;
                        //foreach( $events as $event ){
                        while(true){
                            $data_evento = date('Y-m-d',mktime(0,0,0,$m,($de+$contador),$y));
                            $events = get_posts(array(
                                'post_type' => 'tribe_events',
                                'meta_query' => array(
                                    array(
                                        'key' => '_EventVenueID', // name of custom field
                                        'value' => get_the_ID(),
                                        'compare' => '='
                                    ),
                                    array(
                                        'key' => '_EventStartDate',
                                        'value' => array($data_evento, $data_evento),
                                        'compare' => 'BETWEEN',
                                        'type' => 'DATE'
                                    )
                                ),
                                'meta_key' => '_EventStartDate',
                                'orderby' => 'meta_value',
                                'order' => 'asc',
                                'nopaging' => true,
                            ));
                            //Lógica - Continua o while até achar uma data com evento
                            //Para casos em que não tem nenhum evento ele verifica até 30 dias
                            //Após encontrar o primeiro evento, finaliza o while após 7 dias
                            if (count($events)>0)
                                $encontrou_evento = true;
                            $contador++;
                            if(!$encontrou_evento && $contador < 30){
                                continue;
                            }
                            if($contador>=30){
                                $encontrou_evento=true;
                                $contador = 0;
                                continue;
                            }
                            if($encontrou_evento && $contador>=9){
                                break;
                            }
                            $current_month = date_i18n('F', strtotime($data_evento ));
                            $current_day = date_i18n('d', strtotime($data_evento ));
                            if($latest_day != $current_day){
                                $count_day++;
                            }
                            if($latest_month != $current_month){
                                $count_month++;
                            }
                            $classe_mes = $count_month%2==0?'mes-a':'mes-b';
                            $classe_dia = $count_day%2==0?'dia-a':'dia-b';
                        ?>

                        <?php if($latest_day != $current_day && $latest_day != '') { ?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($latest_month != $current_month && $latest_month != '') { ?>
                            </div>
                        <?php } ?>

                        <?php if($latest_month != $current_month){ ?>
        				    <div class="<?php echo $classe_mes; ?>">
        				        <div class="nome-mes">
                                    <p><?php echo $current_month; ?></p>
                                </div>
                        <?php }
                            if($latest_day != $current_day){
                        ?>
            					<!-- -->
            					<div class="<?php echo $classe_dia; ?>">
            						<div class="data-mes">
            							<div class="data-dia"><p><?php echo date_i18n('d', strtotime($data_evento )); ?></p></div>
            							<div class="data-semana"><p><?php echo date_i18n('l', strtotime($data_evento )); ?></p></div>
            						</div>
            						<div class="agenda-detalhe">
            							<ul>
                        <?php } ?>

                            <?php foreach($events as $event):
                                $evento_data = get_field('_EventStartDate', $event->ID);
                                ?>
                            <li>
                                <div class="agrupa-detalhe">
                                    <span><?php echo get_the_post_thumbnail($event->ID); ?></span>
                                    <div class="nome-banda"><p><?php echo get_the_title($event->ID); ?></p></div>
                                    <div class="hora-show"><p><?php echo date_i18n('H\h', strtotime($evento_data)); ?></p></div>
                                    <?php $custo = get_field('_EventCost', $event->ID);
                                        if($custo != ''){
                                    ?>
                                        <div class="valor-show"><p>R$ <?php echo ($custo=='0'?'free':$custo) ?></p></div>
                                    <?php } ?>
                                </div>
                                <a href="<?php echo get_the_permalink($event->ID); ?>">+</a>
                            </li>

                        <?php
                            endforeach;
                            $latest_day = $current_day;
                            $latest_month = $current_month;
                            } ?>

                        </ul>
                        </div>
                        </div>
                        </div>
    				</div>
    			 </div>
		      </div>
	       </div>
        </div>


<?php endwhile;
} ?>

<?php get_footer(); ?>
