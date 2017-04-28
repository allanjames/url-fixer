<?php
/*
	Plugin Name: Brafton URL Fixer
	Plugin URI: http://www.brafton.com/support/wordpress
	Description: simple plugin for updating urls
	Version: 1.0
    Requires: 4.0
	Author: Brafton, Inc.

*/


class BrUrlFixer{
    
    public function __construct(){
        add_action('admin_menu', array($this, 'BrUrlFixer_menu'));
        add_action('wp_ajax_br_fixer','br_callback');
        //add_action('wp_ajax_nopriv_br_fixer','br_callback');
        
    }
    public function BrUrlFixer_menu(){

        $hook = add_options_page( 'Brafton URL Fixer', 'Brafton URL Fixer', 'manage_options', 'br_url_fixer', array($this, 'menuPage'));
    }

    public function test($permalink,$len) {
        $rel = substr($permalink,$len);
        $length = strlen($rel);
        $first;
        $second;
        $found = 0;
        for( $i = 0; $i <= $length; $i++ ) {
            $char = substr($rel,$i,1);
            if($char=='/' && $found == 0) {
                $first = $i;
                $found = 1;
            } elseif($char=='/' && $found == 1) {
                $second = $i;
                $found = 2;
            }
        }
        $sub = substr($rel,$second);
        return $sub;
    }

    public function menuPage(){
        $test = 'test';
        $this->getposturls();
        ?>
<style>
    #retrieve {
        color: #FFF;
        background-color: #00b9eb;
        display: table;
        padding: 7px;
        cursor: pointer;
        border-radius: 5px;
    }
    textarea {
        width: 90%;
        height: 500px;
    }
    form.br-site-changer {
        width: 75%;
        padding: 25px;
        height: 500px;
    }
    form.br-site-changer label {
        width: 200px;
        display: inline-block;
    }
    .form-item {
        margin: 15px 0px;
    }
    .checks{
        display:inline-block;
    }
    form.br-site-changer .form-item input[type="text"] {
        width: 200px;
        display: inline-block;
    }
    .br-site-changer div.notice{
        padding:15px;
    }
    .br-site-changer div.error, .br-site-changer div.notice-success{
        padding:15px 10px;   
    }
    .inl-b {
        display: inline-block;
    }

</style>
<script>

jQuery(document).ready(function($){

    $('#retrieve').click(function(){

        var offset = $('#offset').val();
        var type = $('#type').find(":selected").val();
        var cat_adj = $('#cat_adj').find(":selected").val();
        var data = {
        'action': 'br_fixer',
        'dataType': 'text',
        'id': '1',
        'type': type,
        'cat_adj': cat_adj,
        'adj': offset
        
        };
        
        $.post(ajaxurl, data, function(response) {
            $('textarea').html(response);
        });
    });
});

</script>
        <?php
            $site = site_url();
            $site_len = strlen(site_url());
            $off = strpos(site_url(),'/',1)+$site_len;
        ?>
        <h1>Redirect Exporter</h1>
        <h2>Site URL: <?php echo $site; ?></h2>
        <h2>Site URL Length: <?php echo $site_len; ?></h2>
        <h2>Suggested Offset: <?php echo $off; ?></h2>
        <h2 class="inl-b">Desired Offset:</h2>
        <input type="text" id = "offset" />
        <h2 class="inl-b">Type:</h2>
        <select id="type"> 
            <option value="post">Post</option> 
            <option value="page" selected>Page</option>
        </select>
        <h2 class="inl-b">Strip Category From URL:</h2>
        <select id="cat_adj"> 
            <option value="yes">Yes</option> 
            <option value="no" selected>No</option>
        </select>
        <h2>URL format: <?php echo get_option( 'permalink_structure' ); ?></h2>
        <h2 id="retrieve" >Export</h2>
        <textarea></textarea>
        <?php 
        
    }
    private function getposturls() {
        $post_query = new WP_Query(array('posts_per_page' => -1));
        $url_array = array();
        $i = 0;
        if ($post_query->have_posts()) : while ($post_query->have_posts()) : $post_query->the_post(); 
        
        ?>
        <article <?php post_class() ?> id="post-<?php the_ID(); ?>" style="margin: 0 30px;">
            <p class="entry-title">
                <?php $url_array[$i] = get_the_permalink(); $i++; ?>
            </p>
        </article>
        
    <?php endwhile; endif;
        
    }
}

new BrUrlFixer();

function br_callback() {

            //global $post;
            if(isset($_POST)) {
            $adjustment = $_POST['adj']; 
            $kind = $_POST['type'];
            $strip_cat = $_POST['cat_adj'];
            $post_query = new WP_Query(array('posts_per_page' => -1,'post_type'=>$kind));
            $url_array = array();
            $site_len = strlen(site_url());
            $off = strpos(site_url(),'/',1)+$site_len;
            $i = 0;
            $output;
            if ($post_query->have_posts()) : while ($post_query->have_posts()) : $post_query->the_post(); 
                $cats = get_the_category();
                if($strip_cat=="yes") { $cat_adj = strlen($cats[0]->slug)+1; }
                //$cat = $cats[0];
                $output .= 'Redirect 301 ';
                $output .= substr(get_the_permalink(),$adjustment+$cat_adj).' '.substr(get_the_permalink(),$site_len)."\r\n"; 
                $i++;
                endwhile; 
            endif; 
            echo $output;
            
            die();
            }     
    }


