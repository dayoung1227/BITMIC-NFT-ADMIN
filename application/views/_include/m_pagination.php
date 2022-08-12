<script type="text/javascript">

    var $target_div = $("#<?=$target_div;?>");
    var link_url = "<?=$link_url;?>";
    var change_div ="<?=$target_div;?>";

    var order_type = "<?=$order_type;?>";
    var order_asc = "<?=$order_asc;?>";
    var search_type = "<?=$search_type;?>";
    var search_str = "<?=$search_str;?>";
    var search_str1 = "<?=$search_str1;?>";
    var search_str2 = "<?=$search_str2;?>";
    var curr_page = "<?=$curr_page;?>";

    $(document).ready(function() {
        var bd_params = {
            "search_type" : search_type,
            "search_str" : search_str,
            "search_str1" : search_str1,
            "search_str2" : search_str2,
            "order_type" : order_type,
            "order_asc" : order_asc,
            "curr_page" : curr_page
        };

        if(bd_params.search_str != ""){
            $target_div.find(".search_str").attr("value", bd_params.search_str);
            $target_div.find(".search_type").val(bd_params.search_type).attr("selected", "selected");
        } else if (bd_params.search_str1 != "") {
            $target_div.find(".search_str1").attr("value", bd_params.search_str1);
            $target_div.find(".search_str2").attr("value", bd_params.search_str2);
            $target_div.find(".search_type").val(bd_params.search_type).attr("selected", "selected");
        }

        $target_div.find(".search_btn").on("click", function(){
            var $this = $(this);
            var bd_params = {
                "search_type" : $this.parent().find(".search_type option:selected").val(),
                "search_str" : $this.parent().find(".search_str").val(),
                "search_str1" : $this.parent().find(".search_str1").val(),
                "search_str2" : $this.parent().find(".search_str2").val(),
                "order_type" : order_type,
                "order_asc" : order_asc,
                "curr_page" : 1
            }
            req(system_config, link_url ,{data:bd_params, div : change_div});
        });

        $target_div.find(".search_str").on("keydown", function(e){
            if( e.keyCode ===  13 ){
                var $this = $(this);
                var bd_params = {
                    "search_type" : $this.parent().find(".search_type option:selected").val(),
                    "search_str" : $this.parent().find(".search_str").val(),
                    "search_str1" : $this.parent().find(".search_str1").val(),
                    "search_str2" : $this.parent().find(".search_str2").val(),
                    "order_type" : order_type,
                    "order_asc" : order_asc,
                    "curr_page" : 1
                }
                req(system_config, link_url ,{data:bd_params, div : change_div});
            }
        });

        $target_div.find('.board_order').on("click", function(){
            var $this = $(this);

            var bd_params = {
                "search_type" : $target_div.find(".search_type option:selected").val(),
                "search_str" : $target_div.find(".search_str").val(),
                "search_str1" : $target_div.find(".search_str1").val(),
                "search_str2" : $target_div.find(".search_str2").val(),
                "order_type" : $this.data("order"),
                "order_asc" : "asc",
                "curr_page" : 1
            }

            if($this.hasClass("orderdown")){
                bd_params.order_asc = "desc";
            }
            req(system_config, link_url ,{data:bd_params, div : change_div});

        });

        $target_div.find('.board_order').addClass("cursor");
        $target_div.find('.board_order').removeClass("orderup");
        $target_div.find('.board_order').removeClass("orderdown");

        $order_div = $target_div.find('[data-order=' + bd_params.order_type + ']');

        if(bd_params.order_asc === "asc"){
            $order_div.addClass("orderdown");
        }else{
            $order_div.addClass("orderup");
        }

    });

    function navi_go(cpage){
        var bd_params = {
            "search_type" : $target_div.find(".search_type option:selected").val(),
            "search_str" : $target_div.find(".search_str").val(),
            "search_str1" : $target_div.find(".search_str1").val(),
            "search_str2" : $target_div.find(".search_str2").val(),
            "order_type" : order_type,
            "order_asc" : order_asc,
            "curr_page" : cpage
        }

        req(system_config, link_url, {data:bd_params, div : change_div});
    }
</script>