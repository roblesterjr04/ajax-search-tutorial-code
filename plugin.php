<?php
	
/*
Plugin Name: AJAX Search
Plugin URI: /
Description: An AJAX Search Example.
Author: RML Soft
Version: 1
*/

function my_ajax_listener() {
     // This function runs with every load, we want to make sure we only perform when our call comes in.
     if (isset($_POST['ajax_search'])) {
          // We made an ajax call, so lets perform a search and return our results.
          $search_term = $_POST['ajax_search'];
          $results = new WP_Query(array('s'=>$search_term));
          
          if ($results->have_posts()) {
	          while ($results->have_posts()) {
		            $results->the_post();
	               // I am using generic HTML in this example, but you would use html from your index template to form each post.
	               ?>
	                    <div class="post">
	                         <h3><a href="<?php the_permalink() ?>">
	                              <?php the_title() ?>
	                         </a></h3>
	                         <?php the_excerpt() // The excerpt already includes the p tags. ?>
	                    </div>
	               <?php
	          }
	          wp_reset_postdata();
          }
          exit; // Have to stop the rest of wordpress from loading!!!!!!
     }
}
add_action( 'init', 'my_ajax_listener' );

function my_ajax_js() {
     ?>
     <script type="text/javascript">
          var ajax_host = "<?php echo site_url(); ?>"; // Here we get our site url
          jQuery(function() {
               // Javascript here.
               jQuery('#searchsubmit').click(function(event) {
                    // #searchsubmit is the ID of my search button, but it might be different on your site. So you'll want to check that.
                    // Lets cancel the default action.
                    event.preventDefault();
 
                    // Now lets make our ajax call!!!!
                    jQuery.ajax(
                         {
                              url: ajax_host, // Our host address, as defined above
                              context: document.body,
                              type: 'POST',
                              data: {
	                              ajax_search: jQuery('input[name="s"]').val() // The search query from the search box
	                           }
                         }
                    ).done(function(data) {
                         // Now our call is done, lets handle the results.
                         jQuery('.content').html(data); // Change .content to whatever element you want to populate the results to.
 
                         // THATS IT!
                    });
               });
          });
     </script>
     <?php
}
add_action( 'wp_head', 'my_ajax_js' );
