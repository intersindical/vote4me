<?php
if (!isset($_SESSION)) session_start();
get_header();

while ( have_posts() ) : the_post();
			$vote4me_option_names = array();
			if(get_post_meta( get_the_id(), 'vote4me_poll_option', true )){
				$vote4me_option_names = get_post_meta( get_the_id(), 'vote4me_poll_option', true );
			}
			$vote4me_option_imgs = array();
			$vote4me_option_imgs = get_post_meta( get_the_id(), 'vote4me_poll_option_img', true );
			$vote4me_poll_option_cover_img = array();
			$vote4me_poll_option_cover_img = get_post_meta( get_the_id(), 'vote4me_poll_option_cover_img', true );
			$vote4me_option_sexes = array();
			$vote4me_option_sexes = get_post_meta( get_the_id(), 'vote4me_poll_option_sex', true );
			$vote4me_option_territorials = array();
			$vote4me_option_territorials = get_post_meta( get_the_id(), 'vote4me_poll_option_territorial', true );
			$vote4me_poll_status = get_post_meta( get_the_id(), 'vote4me_poll_status', true );
			$vote4me_poll_option_id = get_post_meta( get_the_id(), 'vote4me_poll_option_id', true );
			$vote4me_poll_style = get_post_meta( get_the_id(), 'vote4me_poll_style', true );
			$vote4me_poll_vote_total_count = (int)get_post_meta(get_the_id(), 'vote4me_vote_total_count',true);
			$vote4me_poll_container_color_primary = get_post_meta( $post->ID, 'vote4me_poll_container_color_primary', true );?>
			
			<div class="vote4me_container"<?php if($vote4me_poll_container_color_primary){echo ' style="background: -webkit-linear-gradient(40deg,#eee,<?php echo $vote4me_poll_container_color_primary;?>)!important;
    background: -o-linear-gradient(40deg,#eee,<?php echo $vote4me_poll_container_color_primary;?>)!important;
    background: linear-gradient(40deg,#eee,<?php echo $vote4me_poll_container_color_primary;?>)!important;"';}?>>
				<h1 class="vote4me_title">
					<span class="vote4me_title_exact"><?php the_title();?></span>
				  <span class="vote4me_survey-stage">
				  <span class="vote4me_stage vote4me_live vote4me_active" <?php if($vote4me_poll_status !== 'live') echo 'style="display:none;"';?>>Live</span>
				  <span class="vote4me_stage vote4me_ended vote4me_active" <?php if($vote4me_poll_status !== 'end') echo 'style="display:none;"';?>>Ended</span>
				  </span>
				</h1>
				<div class="vote4me_inner">
				<ul class="vote4me_surveys <?php if($vote4me_poll_style == 'list') echo 'vote4me_list'; else echo 'vote4me_grid';?>">
		<?php
			$i=0;
			if($vote4me_option_names){
			foreach($vote4me_option_names as $vote4me_option_name):
			$vote4me_poll_vote_count = (int)get_post_meta(get_the_id(), 'vote4me_vote_count_'.(float)$vote4me_poll_option_id[$i],true);
			//print_r($vote4me_poll_option_id[$i]);
			$vote4me_poll_vote_percentage =0;
			if($vote4me_poll_vote_count == 0){
			$vote4me_poll_vote_percentage =0;
			}else{
			$vote4me_poll_vote_percentage = (int)$vote4me_poll_vote_count*100/$vote4me_poll_vote_total_count; 
			}
			$vote4me_poll_vote_percentage = (int)$vote4me_poll_vote_percentage;
			?>
			<?php if($vote4me_poll_style == 'list'){?>
				<li class="vote4me_survey-item">
			<div class="vote4me_survey-item-inner">
				<div class="vote4me_survey-name">
				  <?php echo $vote4me_option_name;?>
				</div>

			<div class="vote4me_survey-item-action <?php if(vote4me_check_for_unique_voting(get_the_id(),$vote4me_poll_option_id[$i])) echo 'vote4me_survey-item-action-disabled';?>">
				<?php if(!vote4me_check_for_unique_voting(get_the_id(),$vote4me_poll_option_id[$i])):?>
				<form action="" name="vote4me_survey-item-action-form" class="vote4me_survey-item-action-form">
					<input type="hidden" name="vote4me_poll-id" id="vote4me_poll-id" value="<?php echo get_the_id();?>">
					<input type="hidden" name="vote4me_survey-item-id" id="vote4me_survey-item-id" value="<?php echo $vote4me_poll_option_id[$i];?>">
					<input type="button" name="vote4me_survey-vote-button" id="vote4me_survey-vote-button" class="vote4me_orange_gradient" value="Vote">
				</form>
				<?php else:?>
					<span class="vote4me_green_gradient vote4me_list_style_already_voted">Already Voted</span>
				<?php endif;?>
			</div>
			<div class="vote4me_spinner">
						<svg version="1.1" id="vote4me_tick" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			 				viewBox="0 0 37 37" xml:space="preserve">
						<path class="vote4me_circ vote4me_path" style="fill:none;stroke: #ffffff;stroke-width:2;stroke-linejoin:round;stroke-miterlimit:10;" d="
						M30.5,6.5L30.5,6.5c6.6,6.6,6.6,17.4,0,24l0,0c-6.6,6.6-17.4,6.6-24,0l0,0c-6.6-6.6-6.6-17.4,0-24l0,0C13.1-0.2,23.9-0.2,30.5,6.5z"
						/>
							<polyline class="vote4me_tick vote4me_path" style="fill:none;stroke: #ffffff;stroke-width:2;stroke-linejoin:round;stroke-miterlimit:10;" points="
						11.6,20 15.9,24.2 26.4,13.8 "/>
						</svg>
			</div>
				<div class="vote4me_pull">

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
			<?php }else{?>
		  <li class="vote4me_survey-item">

			<div class="vote4me_survey-item-inner vote4me_card_front">
				<div class="vote4me_big_cover">
					  <?php if($vote4me_poll_option_cover_img){
	
						 	if(isset($vote4me_poll_option_cover_img[$i])){
							 	echo '<img src="'.$vote4me_poll_option_cover_img[$i].'">';
							 }
					}?>		
				</div>
				
				<?php if(isset($vote4me_option_imgs[$i])){?>
				<div class="vote4me_survey-country vote4me_grid-only">
				  <img src="<?php echo $vote4me_option_imgs[$i];?>">
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

				<div class="vote4me_survey-item-action<?php if(vote4me_check_for_unique_voting(get_the_id(),$vote4me_poll_option_id[$i])) echo ' vote4me_survey-item-action-disabled';?>">
					<?php if(!vote4me_check_for_unique_voting(get_the_id(),$vote4me_poll_option_id[$i])){?>
					<form action="" name="vote4me_survey-item-action-form" class="vote4me_survey-item-action-form">
						<input type="hidden" name="vote4me_poll-id" id="vote4me_poll-id" value="<?php echo get_the_id();?>">
						<input type="hidden" name="vote4me_survey-item-id" id="vote4me_survey-item-id" value="<?php echo $vote4me_poll_option_id[$i];?>">
						<input type="button" name="vote4me_survey-vote-button" id="vote4me_survey-vote-button" class="vote4me_orange_gradient" value="Vote">
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
		<?php }?>
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
		<a href="https://educacio.intersindical-csc.cat/" target="_blank" rel="nofollow">Intersindical-CSC</a>
	</div>
</div>
<?php endwhile;

get_footer();
?>