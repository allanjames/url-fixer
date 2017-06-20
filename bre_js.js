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

        $('#offset').keyup(function(){
            var offVal = $(this).val();
            var dataUrl = $('#bre_url').attr('data-url');
            var testUrl = dataUrl.substring(0,offVal)+"/";
            console.log(offVal);
            $('#bre_url').html(testUrl);
        });

        $('#csv').click(function(){

            var data = retrieve(csv=1);
            var csvContent = "data:text/csv;charset=utf-8,";
            $.post(ajaxurl, data, function(response) {
                //console.log(JSON.parse(response));
                var test_array = JSON.parse(response);
                var len = Object.keys(test_array).length;
                var i;
                
                for(i=0; i<len; i++) {
                    csvContent+=test_array[i].old+','+test_array[i].new+', \n';
                }
                var encodedUri = encodeURI(csvContent);
                window.open(encodedUri);
            });
        });
});