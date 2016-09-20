	</main><!-- .site-content -->

	<footer>
		<div class="l4">
			<div class="alinha">
				<div class="contato">
					<header>
						<h4>Fale Conosco</h4>
					</header>

					<div class="form-contato">
						<?php $formulario = get_option('showbook_theme_contact_form_shortcode'); ?>
						<?php echo do_shortcode($formulario); ?>
					</div>
					<footer>
						<div class="fone">
							<p>Ou ligue e agende uma reuni&atilde;o conosco:</p>
							<p class="destaca-fone"><small>+ 55 11</small> 94101.2936</p>
						</div>
						<div class="face-bottom">
							<p>Acesse tamb&eacute;m:</p>
							<a href="https://www.facebook.com/showbookagencia/" target="_blank">/showbookagencia</a>
						</div>
					</footer>
				</div>
			</div>
		</div>
	</footer>

<?php wp_footer(); ?>
</body>
</html>
