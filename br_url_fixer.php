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
    #retrieve, #csv {
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

    function retrieve(csv) {
        var ext;
        if(csv==1) {
            ext = 1;
        } else { ext = 2; }
        var offset = $('#offset').val();
        var type = $('#type').find(":selected").val();
        var cat_adj = $('#cat_adj').find(":selected").val();
        var vars = {
        'action': 'br_fixer',
        'dataType': 'text',
        'id': '1',
        'type': type,
        'cat_adj': cat_adj,
        'adj': offset,
        'ext' : ext
        };
        return vars;
    }

    $('#retrieve').click(function(){

        var data = retrieve(csv=0);        
        
        $.post(ajaxurl, data, function(response) {
            $('textarea').html(response);
        });
    });

    $('#csv').click(function(){

        var data = retrieve(csv=1);
        //var test_array = [["name1", 2, 3], ["name2", 4, 5], ["name3", 6, 7], ["name4", 8, 9], ["name5", 10, 11]];
        var csvContent = "data:text/csv;charset=utf-8,";
        $.post(ajaxurl, data, function(response) {
            //console.log(response);
            var test_array = response;
            test_array.forEach(function(infoArray, index){
                //console.log(infoArray.old);
                //dataString = infoArray.split(" ");
                var info = infoArray;
                //csvContent += dataString+ "\n";
            });

            var encodedUri = encodeURI(csvContent);
            window.open(encodedUri);
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
        <img src="data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjMDAwMDAwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTAwIDEwMCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTAwIDEwMCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHBhdGggZD0iTTk0LDMxdi0yaDF2LTFoLTF2LTJoLTJ2MmgtMXYtMmgtMnYyaC0ydi0xdi02aC00djZoM3YxSDY2djFoMC4zNzV2My45NTJMNjEsMzl2OGgxMXYtOCAgbC01LjM3NS02LjA0OFYyOWgxMi4yMTNMODUsMzUuMTYyVjQ3aC0zdjJjLTEuNzc4LDAtMywwLTMsMHY4SDMwdi04VjI5aC0zLjc1di0zLjAxYzAuNjA1LTAuMDMyLDIuMzQ3LTAuMTg5LDQuNzUtMC45OSAgYy0xLjY1NywwLTMuOTk1LTEuMzctNC43NS0xLjg0MVYyM2gtMC41djZIMjN2MTBoLTZ2MTBINWMwLDAsMTcsOSwxNywxN2MzLDAsNjAsMCw2MywwYzAtNCw2LTExLDEwLTExYzAtNCwwLTYsMC02ICBjLTAuOTMyLDAtMi44MjgsMC01LDB2LTJoLTNWMjloMnYyaDJ2LTJoMXYySDk0eiBNNjEuMzU5LDM5bDUuMTQxLTUuODE1TDcxLjY3MiwzOUg2MS4zNTl6IE0yMCw0NWgtMnYtMmgyVjQ1eiBNMjAsNDJoLTJ2LTJoMlY0MnogICBNMjMsNDVoLTJ2LTJoMlY0NXogTTIzLDQyaC0ydi0yaDJWNDJ6IE0yNiw0NWgtMnYtMmgyVjQ1eiBNMjYsNDJoLTJ2LTJoMlY0MnogTTI2LDM5aC0ydi0yaDJWMzl6IE0yNiwzNmgtMnYtMmgyVjM2eiBNMjksNDVoLTJ2LTIgIGgyVjQ1eiBNMjksNDJoLTJ2LTJoMlY0MnogTTI5LDM5aC0ydi0yaDJWMzl6IE0yOSwzNmgtMnYtMmgyVjM2eiBNMjksMzNoLTV2LTNoNVYzM3ogTTg0LDI1di0zaDJ2M0g4NHogTTc5LjE5MywyOUg4NXY1LjgwNyAgTDc5LjE5MywyOXoiLz48cGF0aCBkPSJNMzEsNDh2OGgxMXYtOEgzMXogTTMzLDU1aC0xdi02aDFWNTV6IE0zNSw1NWgtMXYtNmgxVjU1eiBNMzcsNTVoLTF2LTZoMVY1NXogTTM5LDU1aC0xdi02aDFWNTV6ICAgTTQxLDU1aC0xdi02aDFWNTV6Ii8+PHJlY3QgeD0iNDMiIHk9IjQ4IiB3aWR0aD0iMTEiIGhlaWdodD0iOCIvPjxyZWN0IHg9IjU1IiB5PSI0OCIgd2lkdGg9IjExIiBoZWlnaHQ9IjgiLz48cmVjdCB4PSI2NyIgeT0iNDgiIHdpZHRoPSIxMSIgaGVpZ2h0PSI4Ii8+PHJlY3QgeD0iNDkiIHk9IjM5IiB3aWR0aD0iMTEiIGhlaWdodD0iOCIvPjxyZWN0IHg9IjM3IiB5PSIzOSIgd2lkdGg9IjExIiBoZWlnaHQ9IjgiLz48L3N2Zz4=" width="200px" />
        <h2>Site URL: <?php echo $site; ?></h2>
        <h2>Site URL Length: <?php echo $site_len; ?></h2>
        <h2>Suggested Offset: <?php echo $off; ?></h2>
        <h2 class="inl-b">Desired Offset:</h2>
        <input type="text" id = "offset" />
        <h2 class="inl-b">Type:</h2>
        <select id="type"> 
            <option value="post" selected>Post</option> 
            <option value="page">Page</option>
        </select>
        <h2 class="inl-b">Strip Category From URL:</h2>
        <select id="cat_adj"> 
            <option value="yes">Yes</option> 
            <option value="no" selected>No</option>
        </select>
        <h2>URL format: <?php echo get_option( 'permalink_structure' ); ?></h2>
        <h2 id="retrieve" >Update HTACCESS</h2>
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
            $csv = $_POST['ext'];
            $post_query = new WP_Query(array('posts_per_page' => -1,'post_type'=>$kind));
            $url_array = array();
            $site_len = strlen(site_url());
            $off = strpos(site_url(),'/',1)+$site_len;
            $i = 0;
            $output;
            $master = array();
            if ($post_query->have_posts()) : while ($post_query->have_posts()) : $post_query->the_post(); 
                $cats = get_the_category();
                if($strip_cat=="yes") { $cat_adj = strlen($cats[0]->slug)+1; }
                $output .= 'Redirect 301 ';
                $master[$i]['index'] = $i;
                $master[$i]['old'] = substr(get_the_permalink(),$adjustment+$cat_adj);
                $master[$i]['new'] = substr(get_the_permalink(),$site_len);
                $output .= substr(get_the_permalink(),$adjustment+$cat_adj).' '.substr(get_the_permalink(),$site_len)."\r\n"; 
                $i++;
                endwhile; 
            endif;
            if($csv==2){ 
                $file = get_home_path().'.htaccess';
                if(is_writable($file)) :
                    $htac = file_get_contents($file);
                    $htac .= "\n".$output."\n";
                    echo "HTACCESS UPDATED"."\n".$htac;
                    file_put_contents($file, $htac); 
                else:
                    echo "HTACCESS file is not writable";
                endif;
            } else {
                header('Content-Type: application/json');

                echo json_encode($master);
            }
            die();
            }     
    }


