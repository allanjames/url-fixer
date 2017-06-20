<?php
/*
	Plugin Name: Brafton Redirect Exporter
	Plugin URI: http://www.brafton.com/support/wordpress
	Description: simple plugin for updating urls
	Version: 1.0
    Requires: 4.0
	Author: Brafton, Inc.
*/

class BrUrlFixer{
    
    public function __construct(){
        add_action('admin_menu', array($this, 'BrUrlFixer_menu'));
        add_action('wp_ajax_br_fixer','bre_callback');
        add_action('admin_enqueue_scripts','bre_styles');        
    }
    public function BrUrlFixer_menu(){

        add_options_page( 'Brafton Redirect Exporter', 'Brafton Redirect Exporter', 'manage_options', 'br_url_fixer', array($this, 'menuPage'));
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
        $site = site_url();
        $site_len = strlen(site_url());
        $full = get_option( 'permalink_structure' );
        $off = strpos($full,'/',1)+$site_len;

        ?>
        <div id="bre_content">
        <h1>Brafton Redirect Exporter</h1>
        <img src="data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjMDAwMDAwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTAwIDEwMCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTAwIDEwMCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHBhdGggZD0iTTk0LDMxdi0yaDF2LTFoLTF2LTJoLTJ2MmgtMXYtMmgtMnYyaC0ydi0xdi02aC00djZoM3YxSDY2djFoMC4zNzV2My45NTJMNjEsMzl2OGgxMXYtOCAgbC01LjM3NS02LjA0OFYyOWgxMi4yMTNMODUsMzUuMTYyVjQ3aC0zdjJjLTEuNzc4LDAtMywwLTMsMHY4SDMwdi04VjI5aC0zLjc1di0zLjAxYzAuNjA1LTAuMDMyLDIuMzQ3LTAuMTg5LDQuNzUtMC45OSAgYy0xLjY1NywwLTMuOTk1LTEuMzctNC43NS0xLjg0MVYyM2gtMC41djZIMjN2MTBoLTZ2MTBINWMwLDAsMTcsOSwxNywxN2MzLDAsNjAsMCw2MywwYzAtNCw2LTExLDEwLTExYzAtNCwwLTYsMC02ICBjLTAuOTMyLDAtMi44MjgsMC01LDB2LTJoLTNWMjloMnYyaDJ2LTJoMXYySDk0eiBNNjEuMzU5LDM5bDUuMTQxLTUuODE1TDcxLjY3MiwzOUg2MS4zNTl6IE0yMCw0NWgtMnYtMmgyVjQ1eiBNMjAsNDJoLTJ2LTJoMlY0MnogICBNMjMsNDVoLTJ2LTJoMlY0NXogTTIzLDQyaC0ydi0yaDJWNDJ6IE0yNiw0NWgtMnYtMmgyVjQ1eiBNMjYsNDJoLTJ2LTJoMlY0MnogTTI2LDM5aC0ydi0yaDJWMzl6IE0yNiwzNmgtMnYtMmgyVjM2eiBNMjksNDVoLTJ2LTIgIGgyVjQ1eiBNMjksNDJoLTJ2LTJoMlY0MnogTTI5LDM5aC0ydi0yaDJWMzl6IE0yOSwzNmgtMnYtMmgyVjM2eiBNMjksMzNoLTV2LTNoNVYzM3ogTTg0LDI1di0zaDJ2M0g4NHogTTc5LjE5MywyOUg4NXY1LjgwNyAgTDc5LjE5MywyOXoiLz48cGF0aCBkPSJNMzEsNDh2OGgxMXYtOEgzMXogTTMzLDU1aC0xdi02aDFWNTV6IE0zNSw1NWgtMXYtNmgxVjU1eiBNMzcsNTVoLTF2LTZoMVY1NXogTTM5LDU1aC0xdi02aDFWNTV6ICAgTTQxLDU1aC0xdi02aDFWNTV6Ii8+PHJlY3QgeD0iNDMiIHk9IjQ4IiB3aWR0aD0iMTEiIGhlaWdodD0iOCIvPjxyZWN0IHg9IjU1IiB5PSI0OCIgd2lkdGg9IjExIiBoZWlnaHQ9IjgiLz48cmVjdCB4PSI2NyIgeT0iNDgiIHdpZHRoPSIxMSIgaGVpZ2h0PSI4Ii8+PHJlY3QgeD0iNDkiIHk9IjM5IiB3aWR0aD0iMTEiIGhlaWdodD0iOCIvPjxyZWN0IHg9IjM3IiB5PSIzOSIgd2lkdGg9IjExIiBoZWlnaHQ9IjgiLz48L3N2Zz4=" width="200px" />
        <h2>Site URL: <span id="bre_site_len" class="bre_background"><?php echo $site; ?></span></h2>
        <h2>Site URL Length: <span class="bre_background"><?php echo $site_len; ?></span></h2>
        <h2>Suggested Offset: <span class="bre_background"><?php echo $off; ?></span></h2>
        <h2 class="inl-b">Desired Offset:</h2>
        <input type="text" id = "offset" class="bre_background"/>
        <h2 class="inl-b">Type:</h2>
        <select id="type"> 
            <option value="post" selected="selected">Post</option> 
            <option value="page">Page</option>
        </select>
        <h2 class="inl-b">Strip Category From URL:</h2>
        <select id="cat_adj"> 
            <option value="yes">Yes</option> 
            <option value="no" selected="selected">No</option>
        </select>
        <h2>Old Post url format: <span id="bre_url" class="bre_background" data-url="<?php $sample_url = $site.$full; echo $sample_url; ?>"></span></h2>
        <h2>Existing Post url format: <span class="bre_background"><?php echo get_option( 'permalink_structure' ); ?></span></h2>
        <h2 id="retrieve" >Update HTACCESS</h2>
        <h2 id="csv" >export to CSV</h2>
        <textarea></textarea>
        <?php 
        
    }

    private function getposturls() {
        $post_query = new WP_Query(array('posts_per_page' => -1));
        $url_array = array();
        $i = 0;
        if ($post_query->have_posts()) : while ($post_query->have_posts()) : $post_query->the_post(); 
            $url_array[$i] = get_the_permalink(); 
            $i++;
         endwhile; 
        endif;
    }
}

new BrUrlFixer();

function bre_callback() {

            if(isset($_POST)) {
            $bre_global = $_POST;
            $bre_array = bre_sanitize($bre_global);
            $bre_adjustment = $bre_array["adj"]; 
            $bre_kind = $bre_array["type"];
            $bre_strip_cat = $bre_array["cat_adj"];
            $bre_csv = $bre_array["ext"];
            $bre_post_query = new WP_Query(array('posts_per_page' => -1,'post_type'=>$bre_kind));
            $bre_url_array = array();
            $bre_site_len = strlen(site_url());
            $bre_off = strpos(site_url(),'/',1)+$bre_site_len;
            $bre_count = 0;
            $bre_output = "\r\n";
            $bre_master = array();
            if ($bre_post_query->have_posts()) : while ($bre_post_query->have_posts()) : $bre_post_query->the_post(); 
                $bre_cats = get_the_category();
                if($bre_strip_cat=="yes") { $bre_cat_adj = strlen($bre_cats[0]->slug)+1; } else {
                    $bre_cat_adj = null;
                }
                $bre_output .= 'Redirect 301 ';
                $bre_master[$bre_count]['index'] = $bre_count;
                $bre_master[$bre_count]['old'] = substr(get_the_permalink(),$bre_adjustment+$bre_cat_adj);
                $bre_master[$bre_count]['new'] = substr(get_the_permalink(),$bre_site_len);
                $bre_output .= substr(get_the_permalink(),$bre_adjustment+$bre_cat_adj).' '.substr(get_the_permalink(),$bre_site_len)."\r\n"; 
                $bre_count++;
                endwhile; 
            endif;
            if($bre_csv==2){ 
                $bre_file = get_home_path().'.htaccess';
                if(is_writable($bre_file)) :
                    $bre_out =  "\n".$bre_output."\n";
                    $bre_htac = file_get_contents($bre_file);
                    $bre_out .= $bre_htac;
                    echo "HTACCESS UPDATED"."\n".$bre_out;
                    file_put_contents($bre_file, $bre_out);
                else:
                    echo "HTACCESS file is not writable";
                endif;
            } else {
                //header('Content-Type: application/json');

                echo json_encode($bre_master,JSON_FORCE_OBJECT);
            }
            die();
            }     
}

function bre_sanitize($bre_post) {
    
    $bre_clean = array();
    foreach( $bre_post as $bre_key => $bre_value) {
        switch($bre_key) {
            case 'adj':
                $bre_value = isset($bre_value) ? intval($bre_value) : 0; 
                $bre_clean_value = htmlentities($bre_value);
                $bre_clean[$bre_key] = $bre_clean_value;
                break;
            case 'type':
                $bre_value = isset($bre_value) ?  $bre_value : 'post';
                if(strlen($bre_value)>4) $bre_value = 'post';
                $bre_clean_value = htmlentities($bre_value);
                $bre_clean[$bre_key] = $bre_clean_value;
                break;
            case 'cat_adj':
                if(strlen($bre_value)>3) $bre_value = 'no';
                $bre_clean_value = htmlentities($bre_value);
                $bre_clean[$bre_key] = $bre_clean_value;
                break;
            default:
                $bre_clean_value = htmlentities($bre_value);
                $bre_clean[$bre_key] = $bre_clean_value;
        }
    }
    return $bre_clean;
}

function bre_styles() {
    wp_enqueue_style('bre_style',plugins_url('style.css', __FILE__));
    wp_enqueue_script('bre_script',plugins_url('bre_js.js', __FILE__));
}

