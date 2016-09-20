<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<?php
    $artista_id = get_the_ID();
	$album_id = get_field('facebook_album_id');
	$playlist_id = get_field('youtube_playlist_id');
	if(isset($album_id) && trim($album_id)!=='') {
 ?>

<?php
	$url = 'https://graph.facebook.com/v2.6/oauth/access_token?client_id=1630530173930954&client_secret=90f2d7807ef949002bac93107842991b&grant_type=client_credentials';
	$json = file_get_contents($url);
	$obj = json_decode($json);
	$token = $obj->access_token;
 ?>

<script>
  window.fbAsyncInit = function() {
	FB.init({
	  appId      : '1630530173930954',
	  xfbml      : true,
	  version    : 'v2.6'
	});

	FB.api(
	    "/<?php echo $album_id; ?>/photos",
		"get",
		{'access_token': '<?php echo $token ?>', 'limit': 9},
	    function (response) {
	      if (response && !response.error) {
			var contador = 0;
	        for(var x in response.data){
				var photo = response.data[x];
				FB.api(
				    "/" + photo.id,
					'get',
					{'access_token': '<?php echo $token ?>', 'fields': 'link,picture,images'},
				    function (response) {
				      if (response && !response.error) {
						var li = document.createElement('li');
						li.innerHTML = '<a target="_blank" href="' + response.link + '"><img src="' + response.picture + '" alt=""></a>';
						document.getElementById('videos-fotos').appendChild(li);
						if(contador == 0){
							for(var i in  response.images){
								var imagem = response.images[i];
								if(imagem.height > 300){
									var li = document.createElement('a');
									li.href = response.link;
									li.target = '_blank';
									li.innerHTML = '<img src="' + imagem.source + '" alt="">'
									document.getElementById('video-foto-principal').appendChild(li);
									break;
								}
							}
						}
						contador++;
				      }
				    }
				);
			}
	      }
	    }
	);
  };

  (function(d, s, id){
	 var js, fjs = d.getElementsByTagName(s)[0];
	 if (d.getElementById(id)) {return;}
	 js = d.createElement(s); js.id = id;
	 js.src = "//connect.facebook.net/en_US/sdk.js";
	 fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

<?php }
if(isset($playlist_id) && trim($playlist_id)!=='') {
?>
    <script>
        function onGoogleLoad() {
            gapi.client.setApiKey('AIzaSyAUA2WnCbkxbaqd-mD-ejsmGbBZUfTYn-c');
            gapi.client.load('youtube', 'v3', function() {

                var request = gapi.client.youtube.playlistItems.list({
                    part: 'snippet',
                    playlistId: '<?php echo $playlist_id ?>',
                    maxResults: 8
                });

                request.execute(function(response) {
                    for (var i = 0; i < response.items.length; i++) {
                        var video = response.items[i];

                        var li = document.createElement('li');
                        var youtubeLink = 'https://www.youtube.com/watch?v=' + video.snippet.resourceId.videoId;
                        var html = '<div><a href="' + youtubeLink +  '" target="_blank" style="position: absolute; display: block; z-index: 5; background: url(&quot;/wp-content/themes/wp-themes/img/play-youtube.png&quot;);background-size:100%;background-position:center;background-repeat:no-repeat;"></a>';
                        html += '<a target="_blank" href="' + youtubeLink + '"><img src="' + video.snippet.thumbnails.default.url + '" alt=""></a></div>';
                        li.innerHTML = html;
                        document.getElementById('videos-fotos').appendChild(li);

                        if(i==0){
                            var a1 = document.createElement('a');
                            a1.href = youtubeLink;
                            a1.target = '_blank';
                            a1.style = "position: absolute; display: block;background: url('/wp-content/themes/wp-themes/img/play-youtube.png'); background-size:100%;z-index:5;";
                            document.getElementById('video-foto-principal').appendChild(a1);

                            var li = document.createElement('a');
                            li.href = youtubeLink;
                            li.target = '_blank';
                            li.innerHTML = '<img src="' + video.snippet.thumbnails.high.url + '" alt="">'
                            document.getElementById('video-foto-principal').appendChild(li);
                        }
                    }
                });
            });
        }
    </script>
    <script src="https://apis.google.com/js/client.js?onload=onGoogleLoad"></script>
<?php } ?>

    <div class="l1">
        <div class="artista-banner">
            <ul>
                <li>
                    <?php echo wp_get_attachment_image(get_field('banner_principal'), 'full'); ?>
                </li>
            </ul>
        </div>
    </div>

    <div id="modal-mapa" style="display:none; position:absolute; top:30%; left:30%; width:600px;height:450px; z-index:300;background-color:white; padding:20px;box-shadow: 4px 4px 40px 6px rgba(0,0,0,0.75);">
        <label style="font-size:22px;">Arraste o marcador para o local do evento</label>
        <div id="us6" style="width: 600px; height: 400px;position: relative; overflow: hidden; transform: translateZ(0px);"></div>
        <button id="modal-mapa-salvar" style="margin-top:10px;float:right;">Salvar</button>
    </div>

    <div class="l2">
        <div class="alinha">
            <header>
                <h3><?php the_title(); ?></h3>
                <div class="btn-contrate-banda"></div>
                <div class="quero-show">
                    <div class="btn-local" id="contrate-mapa" style="float:right;">Local</div>
                    <div class="form-dados">
                        <form id="formulario-contrate" action="/wp-admin/admin-ajax.php">
                            <input type="hidden" id="us6-lat" name="us6-lat" />
                            <input type="hidden" id="us6-lon" name="us6-lon" />
                            <div class="campo01">
                                <label>Nome:</label>
                                <input id="contrate-nome" type="text" name="nome" required>
                            </div>
                            <div class="campo01">
                                <label>E-mail:</label>
                                <input id="contrate-email" type="email" name="email" required>
                            </div>
                            <div class="campo01">
                                <label>Dia</label>
                                <input type="text" name="data" id="contrate-data" required>
                            </div>
                            <div class="campo01">
                                <label>Telefone:</label>
                                <input id="contrate-telefone" type="phone" name="telefone" required>
                            </div>
                            <input type="hidden" name="artista" value="<?php the_title(); ?>" />
                            <input type="hidden" name="action" value="RegistraInteresse"/>
                            <div class="campo01"><input type="submit"  name="submit" value="QUERO ESSE SHOW"  class="btn-quero-show"></div>
                        </form>
                    </div>
                </div>
            </header>
			<div class="artista">
				<div class="artista-logo">
					<?php the_post_thumbnail(); ?>
				</div>
				<div class="artista-conteudo">
					<?php the_content(); ?>
				</div>
			</div>
			<div class="artista">
				<div class="artista-video">
					<div id="video-foto-principal" class="video-principal" style="position:relative;">

                    </div>
                    <ul id="videos-fotos">
                        <li><a target="_blank" href="<?php echo get_field('url_album_facebook'); ?>" title="Mais V&iacute;deos"><img src="<?php echo get_template_directory_uri(). '/img/mais-videos.png' ?>" alt=""></a></li>
                    </ul>
                </div>
				<div class="artista-botoes">
					<a target="_blank" href="<?php echo get_field('url_facebook'); ?>" class="btn01">Oficial</a>
					<div class="btn02">
						<a target="_blank" href="<?php echo get_field('release_tecnico'); ?>">Release</a>
						<a target="_blank" href="<?php echo wp_get_attachment_url( get_field('rider_tecnico') ); ?>">Rider T&eacute;cnico</a>
					</div>
					<div class="btn03">
						<?php $old = $post; $data_evento=null; ?>
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
							$id = $old->ID;
							if(!is_null($data_evento)) { ?>
								<a href="<?php echo get_the_permalink($id_evento); ?>">
									<span> <?php echo date_i18n('d.F /H\H', strtotime($data_evento )) ?> </span>
								</a>
							<?php } else { ?>
							<span>SEM SHOW</span>
							<?php } ?>
						<a class="map" href="#">Local</a>
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
                        while(true){
                            $data_evento = date('Y-m-d',mktime(0,0,0,$m,($de+$contador),$y));
                            $events = get_posts(array(
                                'post_type' => 'tribe_events',
                                'meta_query' => array(
                                    array(
                                        'key' => 'artistas', // name of custom field
                                        'value' => '"' . $artista_id . '"',
                                        'compare' => 'LIKE'
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


<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
