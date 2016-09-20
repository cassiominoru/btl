<?php get_header(); ?>

	<div id="primary" class="l2">
        <div class="container">
    		<?php while ( have_posts() ) : the_post(); ?>
    			<h1><?php the_title(); ?></h1>
    			<p>
    				<?php the_content(); ?>
    			</p>
    		<?php endwhile; // end of the loop. ?>
        </div>
	</div><!-- .content-area -->


<?php get_footer(); ?>
