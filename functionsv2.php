<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
                   
           $uri = get_stylesheet_directory_uri() . '/blog-custom.css';
           $uri = get_stylesheet_directory_uri() . '/custom.css';
        return $uri;
    }

endif;
wp_enqueue_script( 'custom-css', get_stylesheet_directory_uri() . '/custom.css', array(), '1.0.0', true );
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

wp_register_script( 'custom.js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), rand(), 'all' );
wp_enqueue_script( 'custom.js' );


function add_file_types_to_uploads($file_types){ $new_filetypes = array();
 $new_filetypes['svg'] = 'image/svg+xml'; 
 $file_types = array_merge($file_types, $new_filetypes );
  return $file_types;
   } 
 add_action('upload_mimes', 'add_file_types_to_uploads');

 /*classic editor*/
 add_filter('use_block_editor_for_post', '__return_false');
/*classic widgets block*/
function cbam_child_theme_support() {
    remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'cbam_child_theme_support' );
// END ENQUEUE PARENT ACTION
function project_posttype() {   

    $labels = array( 
        'name' => _x('Project', 'post type general name'), 
        'singular_name' => _x('Project Item', 'post type singular name'), 
        'add_new' => _x('Add New', 'Project'), 
        'add_new_item' => __('Add New Project'), 
        'edit_item' => __('Edit Project'), 
        'new_item' => __('New Project'), 

        'view_item' => __('View Project'), 
        'search_items' => __('Search Project'), 
        'not_found' => __('Nothing found'), 
        'not_found_in_trash' => __('Nothing found in Trash'), 
        'parent_item_colon' => '' 
    );   

    $args = array( 
        'labels' => $labels, 
        'public' => true, 
        'publicly_queryable' => true, 
        'show_ui' => true, 
        'query_var' => true,  
        'rewrite' => array( 'slug' => 'project', 'with_front'=> false ), 
        'capability_type' => 'post', 
        'hierarchical' => true,
        'has_archive' => true,  
        'menu_position' => null, 
        'supports' => array('title','editor','thumbnail') 
    );   

    register_post_type( 'project' , $args ); 
}
add_action( 'init', 'project_posttype' ); 
function project_function(){
    $args = array(
        'post_type' => 'project',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'tax_query' => array(
            array(
            'taxonomy' => 'project_category',
            'field' => 'slug',
            'terms' => 'featured-projects'
            ),
        )
    );
    $loop = new WP_Query($args);
    $html= '<ul class="project_card">';
    while ( $loop->have_posts() ) : $loop->the_post(); 
    // get_the_category( get_the_iD())[0]->name;
    $html.='<li> <fugure><a href="'.get_permalink().'"> <img src="'.get_the_post_thumbnail_url().'"><figcaption><h2>'.get_the_title().'</h2></figcaption></a></figure></li>';    
    endwhile;
    $html.= '</ul>';
    wp_reset_postdata(); 
     wp_reset_query();
    return $html;
}
/*End foreach*/
add_shortcode( 'project_shortcode' ,'project_function');

// Blog insight sidebar //
function blog_sidebar() {
        $html='<div class="blog-sidebar"><div class="search-wrap"><form data-id="1" role="search" method="get" action="/">
                <input type="search" id="search" placeholder="Search " name="s" value="" class="search-input" autocorrect="off" autocapitalize="off" spellcheck="false" autocomplete="off" />
            </form></div>';
   $args = array(
               'taxonomy' => 'category',
               'orderby' => 'name',
               'order'   => 'ASC'
           );
   $cats = get_categories($args);
   
   $html.=do_shortcode('<div class="newslatter"><h4>Join our quarterly newsletter</h4><p>Sign up for our free newsletter containing our latest projects, insights and resources.</p>[gravityform id="3" title="true"]</div>');
                $html.='<div class="category-wrap">
                    <h3 class="sidebar-heading">Categories</h3>
                    <ul>';
                foreach($cats as $cat) { 
                        $category_link = esc_url( get_category_link( $cat->term_id ));
                        $html.='<li><a href="'.$category_link.'">'.$cat->name.'</a></li>';
            }
                    $html.='</ul></div>';
                
                $tags = get_tags();

                $html.='<div class="category-wrap">
                    <h3 class="sidebar-heading">Topics</h3>
                    <ul>';
                 foreach( $tags as $tag ) { 
                        $tag_link = esc_url(  get_tag_link($tag->term_id ));
                        $html.='<li><a href="'.$tag_link.'">'.$tag->name .'</a></li>';
            }
                    $html.='</ul></div>';
  $html.='</div></div>';
    return $html;

  // wp_reset_postdata();

}
add_shortcode('blog-sidebar', 'blog_sidebar');
// Blog insight sidebar End //

//all Blog insight //
function blog_post() {
  $category_ul='<ul class="tabs">';
  $html.='<div class="tabs-content">';
  $all='<div class="list list_box_0 active" id="one0"><div class="row">';
  $i=0;
   $args = array(
               'taxonomy' => 'category',
               'orderby' => 'date', 
               'order'=>'ASC',
               'parent'  => 0,
               'hide_empty'=> false,
           );
    $cats = get_categories($args);
  //  get all works

   $args = array( 
              'post_type' => 'post',
               'tax_query'=> array(
                             array(
                            'taxonomy' => 'category',
                            'field' => 'term_id',
                            'operator' => 'NOT IN',
                            'orderby' => 'date', 
                            'order'=>'ASC',                            
                            ),
                        ),
              'posts_per_page' => -1);                
   $the_query = new WP_Query( $args );
   while ( $the_query->have_posts() ) : $the_query->the_post();

      $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
      $title = get_the_title();
      $content = get_the_content();
      $excerpt =get_the_excerpt();
      $categories = get_the_category();
      $all.='<div class="blog-wrapper"><a href="'.get_the_permalink().'" class="blog-image-head"><h2>'.$title.'</h2><div class="blog-image"> <img src="'.$featured_img_url.'" alt=""></div></a><p>'.$excerpt.'</p><a href="'.get_the_permalink().'" class="blog-btn">READ INSIGHT</a></div>';

      endwhile;
      wp_reset_postdata();

   $category_ul.='<li id="li_0" class="clickme"><a id="0" href="javascript:void(0);" data-tag="one0" class="activelink">All</a></li>';
   foreach($cats as $cat) {   
      $category_ul.='<li id="li_'.$cat->term_id.'" class="clickme"><a id="'.$cat->term_id.'" href="javascript:void(0);" data-tag="one'.$cat->term_id.'">'.$cat->name.'</a></li>';
      $args = array( 'post_type' => 'post', 
                        'posts_per_page' => -1,
                        'orderby' => 'date',
                          'order'=>'ASC', 
                        'tax_query'=> array(
                             array(
                            'taxonomy' => 'category',
                            'terms' => $cat->term_id,
                            'field' => 'term_id',
                            'orderby' => 'date', 
                            'order'=>'ASC',
                            
                            ),

                        )
                );
  
  $the_query = new WP_Query( $args );

  if ( $the_query->have_posts() ) :
    
        $class="hide";
    $count_products=0;
    $html.='<div class="list_box_'.$cat->term_id.' list '.$class.'" id="one'.$cat->term_id.'"><div class="row">';
    while ( $the_query->have_posts() ) : $the_query->the_post();

      $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
      $title = get_the_title();
      $content = get_the_content();
      $excerpt =get_the_excerpt();
      $categories = get_the_category();
      $html.='<div class="blog-wrapper"><a href="'.get_the_permalink().'" class="blog-image-head"><h2>'.$title.'</h2><div class="blog-image"> <img src="'.$featured_img_url.'" alt=""></div></a><p>'.$excerpt.'</p><a href="'.get_the_permalink().'" class="blog-btn">READ INSIGHT</a></div>';
      $count_products++;
      endwhile;
      $btn="";
      if($count_products>4){
        $btn='<button type="button" id="btn_'.$cat->term_id.'" class="load-more">Load More</button>';  
      }
      
      $html.='</div>';
      $html.=$btn;
      $html.='</div>';
      endif;
     wp_reset_postdata();
    $i++;
  }

   $btn='<button type="button" id="btn_0" class="load-more">Load More</button>';
   $all.='</div>';
   $all.=$btn;
    $category_ul.='</ul>';
    wp_reset_postdata();
    return $category_ul."</div>".$html.$all;

}
add_shortcode('blogs_post', 'blog_post');
// Spaeking Engagement code
