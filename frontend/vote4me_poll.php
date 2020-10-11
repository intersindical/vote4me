<?php
add_shortcode('VOTE4ME','vote4me_add_shortcode');
function vote4me_add_shortcode($atts, $content = null){
	$a = shortcode_atts( array(
		'id' => '1',
		'type' => '',
		'use_in' => 'post'
	), $atts );

$it_poll_shortcode_args = array (
	'post_type'              => array( 'vote4me_poll' ),
	'post_status'            => array( 'publish' ),
	'nopaging'               => true,
	'order'                  => 'DESC',
	'orderby'                => 'date',
	'p'                      => $a['id']
);

// The Query
$vote4me_post_query = new WP_Query( $it_poll_shortcode_args );
// The Loop
ob_start();
if ( $vote4me_post_query->have_posts()) {

	while ( $vote4me_post_query->have_posts() ) : $vote4me_post_query->the_post();

			$vote4me_options = array();

			$vote4me_options['names'] = array();
			if (get_post_meta( get_the_id(), 'vote4me_poll_option', true )){
				$vote4me_options['names'] = get_post_meta( get_the_id(), 'vote4me_poll_option', true );
			}
			
			$vote4me_options['imgs'] = array();
			$vote4me_options['imgs'] = get_post_meta( get_the_id(), 'vote4me_poll_option_img', true );
			
			$vote4me_options['cover_img'] = array();
			$vote4me_options['cover_img'] = get_post_meta( get_the_id(), 'vote4me_poll_option_cover_img', true );
			
			$vote4me_options['sexes'] = array();
			$vote4me_options['sexes'] = get_post_meta( get_the_id(), 'vote4me_poll_option_sex', true );

			$vote4me_options['territorials'] = array();
			$vote4me_options['territorials'] = get_post_meta( get_the_id(), 'vote4me_poll_option_territorial', true );

			$vote4me_options['secretaries'] = array();
			$vote4me_options['secretaries'] = get_post_meta( get_the_id(), 'vote4me_poll_option_secretaria', true );

			$vote4me_options['ids'] = array();
			$vote4me_options['ids'] = get_post_meta( get_the_id(), 'vote4me_poll_option_id', true );

			// Sort by secretaries
			usort($vote4me_options, function($a, $b) {
				return strcmp($a['secretaries'], $b['secretaries']);
			});

			$vote4me_secretaria_title = "";

			$vote4me_poll_status = get_post_meta( get_the_id(), 'vote4me_poll_status', true );
			
			if ($a['type']) {
				$vote4me_poll_style = $a['type'];
			} else {
				$vote4me_poll_style = get_post_meta( get_the_id(), 'vote4me_poll_style', true );
			}
			$vote4me_poll_vote_total_count = (int)get_post_meta(get_the_id(), 'vote4me_vote_total_count',true);
			$vote4me_poll_container_color_primary = get_post_meta( get_the_id(), 'vote4me_poll_container_color_primary', true );
			?>

			<div class="vote4me_container"<?php if($vote4me_poll_container_color_primary){echo ' style="background: -webkit-linear-gradient(40deg,#eee,<?php echo $vote4me_poll_container_color_primary;?>)!important;
    background: -o-linear-gradient(40deg,#eee,<?php echo $vote4me_poll_container_color_primary;?>)!important;
    background: linear-gradient(40deg,#eee,<?php echo $vote4me_poll_container_color_primary;?>)!important;"';}?>>
				<h1 class="vote4me_title">
					<span class="vote4me_title_exact"><?php the_title();?></span>
				  <span class="vote4me_survey-stage">
				  <span class="vote4me_stage vote4me_live vote4me_active" <?php if($vote4me_poll_status !== 'live') echo 'style="display:none;"';?>>Votació activa</span>
				  <span class="vote4me_stage vote4me_ended vote4me_active" <?php if($vote4me_poll_status !== 'end') echo 'style="display:none;"';?>>Votació tancada</span>
				  </span>
				</h1>
				<div class="vote4me_inner">
				<ul class="vote4me_surveys <?php if($vote4me_poll_style == 'list') echo 'vote4me_list'; else echo 'vote4me_grid';?>">
		<?php
			$i=0;
			if($vote4me_options["names"]){

			foreach($vote4me_options["names"] as $vote4me_option_name):
				$vote4me_poll_vote_count = (int)get_post_meta(
					get_the_id(),
					'vote4me_vote_count_'.(float)$vote4me_options['ids'][$i],true);
				//print_r($vote4me_options['ids'][$i]);
				$vote4me_poll_vote_percentage =0;
				if ($vote4me_poll_vote_count == 0){
					$vote4me_poll_vote_percentage =0;
				} else {
					$vote4me_poll_vote_percentage = (int)$vote4me_poll_vote_count*100/$vote4me_poll_vote_total_count; 
				}
				$vote4me_poll_vote_percentage = (int)$vote4me_poll_vote_percentage;
		?>

		<?php
				if ($vote4me_options['secretaries'][$i] != $vote4me_secretaria_title) {
					$vote4me_secretaria_title = $vote4me_options['secretaries'][$i];
					?>
					<div class="vote4me_secretaria_title"><?php echo $vote4me_secretaria_title;?></div>
					<?php
				}
		?>

		  <li class="vote4me_survey-item">
			<div class="vote4me_survey-item-inner">
				<div class="vote4me_big_cover">
					  <?php
					  	if ($vote4me_options['cover_img']) {
						 	if (isset($vote4me_options['cover_img'][$i])) {
							 	echo '<img src="'.$vote4me_options['cover_img'][$i].'">';
							 }
						}?>		
				</div>
				
				<?php if(isset($vote4me_options['imgs'][$i])){?>
				<div class="vote4me_survey-country vote4me_grid-only">
				  <img src="<?php echo $vote4me_options['imgs'][$i];?>">
				  <div class="vote4me_spinner">
				  	<svg version="1.1" id="vote4me_tick" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 				viewBox="0 0 37 37" xml:space="preserve">
					<path class="vote4me_circ vote4me_path" style="fill:none;stroke: #ffffff;stroke-width:1.5;stroke-linejoin:round;stroke-miterlimit:10;" d="
				M30.5,6.5L30.5,6.5c6.6,6.6,6.6,17.4,0,24l0,0c-6.6,6.6-17.4,6.6-24,0l0,0c-6.6-6.6-6.6-17.4,0-24l0,0C13.1-0.2,23.9-0.2,30.5,6.5z"
				/>
					<polyline class="vote4me_tick vote4me_path" style="fill:none;stroke: #ffffff;stroke-width:1.5;stroke-linejoin:round;stroke-miterlimit:10;" points="
				11.6,20 15.9,24.2 26.4,13.8 "/>
				</svg>
				  </div>
				</div>

				<?php }?>
				<div class="vote4me_survey-name">
				  <?php echo $vote4me_option_name;?>
				</div>
				<div class="vote4me_survey-territorial">
				  <?php echo $vote4me_options['territorials'][$i];?>
				</div>
				<div class="vote4me_survey-secretaria">
				  <?php echo $vote4me_options['secretaries'][$i];?>
				</div>

				<div class="vote4me_survey-item-action<?php if (vote4me_check_for_unique_voting(get_the_id(),$vote4me_options['ids'][$i])) echo ' vote4me_survey-item-action-disabled';?>">
					<?php if(!vote4me_check_for_unique_voting(get_the_id(),$vote4me_options['ids'][$i])){?>
					<form action="" name="vote4me_survey-item-action-form" class="vote4me_survey-item-action-form">
						<input type="hidden" name="vote4me_poll-id" id="vote4me_poll-id" value="<?php echo get_the_id();?>">
						<input type="hidden" name="vote4me_survey-item-id" id="vote4me_survey-item-id" value="<?php echo $vote4me_options['ids'][$i];?>">
						<input type="hidden" name="vote4me_secretaria" id="vote4me_secretaria" value="<?php echo $vote4me_option_secretaries[$i];?>">
						<input type="button" name="vote4me_survey-vote-button" id="vote4me_survey-vote-button" class="vote4me_orange_gradient" value="Vota">
					</form>
				</div>
				<?php }else{ echo '<span style="border-top:1px solid #ccc;border-bottom:1px solid #ccc; padding:0px; margin:5px; display:inline-block; color: #fc6462;">You Already Participated!</span>';}?>
			

				<div class="vote4me_pull-right">

				  <span class="vote4me_survey-progress">
					<span class="vote4me_survey-progress-bg">
					  <span class="vote4me_survey-progress-fg vote4me_orange_gradient" style="width:<?php echo $vote4me_poll_vote_percentage;?>%;"></span>
				  </span>

				  <span class="vote4me_survey-progress-labels">
					  <span class="vote4me_survey-progress-label">
						<?php echo $vote4me_poll_vote_percentage;?>%
					  </span>
					  <input type="hidden" name="vote4me_poll_e_vote_count" id="vote4me_poll_e_vote_count" value="<?php echo $vote4me_poll_vote_count;?>"/>
				  <span class="vote4me_survey-completes">
						<?php echo vote4me_number_shorten($vote4me_poll_vote_count)." / ".vote4me_number_shorten($vote4me_poll_vote_total_count);?>
					  </span>
				  </span>
				  </span>
				</div>
				</div>
		  </li>
<?php
	$i++;
	endforeach;
	echo '</ul> <div style="clear:both;"></div>';	
			}else{
				if( current_user_can('author') || current_user_can('editor') || current_user_can('administrator') ){
					_e('<p class="vote4me_short_code">Please add some questions or may be you missed the option field.</p><br><a href="'.get_edit_post_link(get_the_id()).'" class="vote4me_survey-notfound-button" style="width:auto;max-width:100%;">Edit This Poll</a>','vote4me');
				}else{
					_e('<p class="vote4me_short_code">This Poll is not yet ready contact site administrator</p>','vote4me');
				}
				
			}?>
	 
	</div>
	<div class="vote4me_powered_by">
		<a href="https://educacio.intersindical-csc.cat/" target="_blank" rel="nofollow">Sectorial d'educació de la Intersindical-CSC</a>
	</div>
</div>
<?php endwhile;
}

$output = ob_get_contents();
ob_end_clean();
return $output;
// Restore original Post Data
wp_reset_postdata();

}
add_filter('widget_text', 'do_shortcode');
add_filter('content', 'do_shortcode');
