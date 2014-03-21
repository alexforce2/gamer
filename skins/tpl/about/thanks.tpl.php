<style>
    #main{width:960px;margin:0 auto;padding-top:10px;}
    h2{text-align:center;margin-top:0;}
    .thanks-vk,.thanks-yo,.thanks-go{margin:20px 40px 0 40px;float:left;position:relative;}
    .info-thanks{font-size:11px; border: 1px solid #e9e9e9; width: 200px; height:200px; position: absolute !important; z-index: 100; left: 100px; bottom:20px; padding: 15px; background-color: white;display:none}
    .close{display:block;float:right;margin-top:-10px;cursor:pointer;}
</style>
<script>
    $(document).ready(function(){
        $('#main').on('click','.click-thanks',function(e){
            var blockThanks = $(e.target).next().attr('class');
            var cssClass = $(e.target).parent('div').attr('class');
            var id = $(this).siblings('div').children('div').attr('id');
            $(this).parent('div').siblings('div').find('.info-thanks').hide(300);
            $.ajax({
                type: 'POST',
                url: document.location.href,
                data: {"id": id},
                success: function(data){
                    var result = '';
                    var obj = $.parseJSON(data);
                    result = "<img src='/storage"+obj.source_img+"' />" +
                        "<p>Партнер "+obj.name_partner+"</p>" +
                        "<a href='"+obj.link+"'>Подробнее о "+obj.link_anchor+"</a>" +
                        "<p>"+obj.text+"</p>";
                    $('.'+cssClass+' > '+'.'+blockThanks).toggle(300);
                    $('#'+id).html(result);
                }
            });
        });
        $('.close').on('click',function(){
            $(this).parent('div').hide(300);
        });
        $(document).on('click',function(e){
         var elem = $(e.target).hasClass('click-thanks');
         if($(e.target).closest('.info-thanks').length || elem==true)return;
            $('.info-thanks').hide(300);
            e.stopPropagation();
         })


    });
</script>
<div id="main">

    <? include $_SERVER['DOCUMENT_ROOT']. '/skins/tpl/block/menu-about.block.tpl.php';?>
    <h2>Хотим выразить огромную благодарнсть нашим партнёрам</h2>
    <div class="thanks-vk">
        <img src="/storage/thanks/vk-thanks.png"  class="click-thanks"/>
        <div class="info-thanks"><span class="close">Закрыть</span>
            <div id="vkontakte" ></div>
        </div>
    </div>
    <div class="thanks-yo">
        <img src="/storage/thanks/Yt-thanks.png" class="click-thanks" />
        <div class="info-thanks"><span class="close">Закрыть</span>
            <div  id="youtube"></div>
        </div>
    </div>
    <div class="thanks-go" >
        <img src="/storage/thanks/gg-thanks.png"  class="click-thanks" />
        <div class="info-thanks"><span class="close">Закрыть</span>
            <div id="google"></div>
        </div>
    </div>
</div>