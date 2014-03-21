/*

 <p class="likes<?=( $data['user-likes']!==false ) ? ' voted' : ''?>" id="2-<?=$data['id_ucg']?>">
     <span class="rating" style="color:<?=$color?>;"><?=$data['record-likes']['likes']?></span>
     <span class="like<?=( $data['user-likes']['likes']==="1" ) ? ' liked' : ''?>">Like</span>
     <span class="dislike<?=( $data['user-likes']['dislikes']==="1" ) ? ' disliked' : ''?>">Dislike</span>
 </p>

 */
function likes(){
    var self = this;
    likes.prototype.init = function(){
        $(".like").click( function(e){
            self.vote(e, "Like");
        });

        $(".dislike").click( function(e){
            self.vote(e, "Dislike");
        });
    }

    likes.prototype.vote = function(e, method){
        var parentElement = $(e.currentTarget).parent();
        var currentElement = $(e.currentTarget);
        var fieldIdArray = ($(e.currentTarget).parent().attr("id")).split("-");
        var classes = ($(e.currentTarget).parent().attr("class")).split(" ");
        var voted = (classes[1] === "voted" ) ? "true" : "false";
        $.ajax({
            type: 'POST',
            url: document.location.href,
            dataType: 'html',
            data: {'ajax-query': 'true', 'type-class':'likes', "method": method, "voted": voted, "likes-group":fieldIdArray[0], "record":fieldIdArray[1]},
            success: function(data){
                data = data.split("$")
                color = (data[1]>0)?"green":"";
                color = (data[1]<0)?"red":color;
                if( data[0]==="liked" ){
                    $(parentElement).attr("class", "likes voted");
                    $(currentElement).attr("class", "like liked");
                    $(parentElement).find(".dislike").attr("class", "dislike");
                    $(parentElement).find(".rating").html(data[1]);
                    $(parentElement).find(".rating").css("color", color);
                }
                if( data[0]==="disliked" ){
                    $(parentElement).attr("class", "likes voted");
                    $(currentElement).attr("class", "dislike disliked");
                    $(parentElement).find(".like").attr("class", "like");
                    $(parentElement).find(".rating").html(data[1]);
                    $(parentElement).find(".rating").css("color", color);
                }
            }
        });
    }

    this.init();
}

function initLikes(){
    new likes();
}

