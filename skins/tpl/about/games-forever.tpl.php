<style>
    #main{width:960px;margin:0 auto;overflow:hidden;padding-top:10px;}
    .right-menu{margin-left:230px;}
    .left-content, .center-content, .right-content{float:left;margin-left:20px;width:220px;}
    .inner-content h3{margin:0 0 15px 0;color:#1fbba6;}
    .inner-content p{margin-bottom:5px;color:#63738e;font-size:16px; }
    .inner-content a{color:#1fbba6;font-size:16px;}
    .inner-content{margin-bottom:15px;}
</style>

<div id="main">
    <?include $_SERVER['DOCUMENT_ROOT'] . '/skins/tpl/block/menu-about.block.tpl.php';?>
    <div class="right-menu">
        <?$arrClass = array("left-content","center-content","right-content");
        foreach($data['games_forever'] as $key=>$value){?>
        <div class="<?echo $arrClass[$key]?>">
            <div class="inner-content">
                <h3><?echo $value['name_game']?></h3>
                <p><?echo $value['description_game']?></p>
                <a href="<?echo $value['link_game']?>"><?echo $value['link_game_anchor']?></a>
            </div>
            <img src="/storage/legend-game/<?echo $value['source_img']?>"/>
        </div>
        <?}?>
    </div>
    <? include "/skins/tpl/block/share-soc.block.tpl.php"; ?>
</div>