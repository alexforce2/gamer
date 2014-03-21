$(document).ready(function(){

    var month = new Array('Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря');

    listComment();

    var sessionUser;
    var sessionAuth=0;
    var commentsUserLikes;
    var commentsLikes;
    function renderMenu(){
        // нужно проверять кому принадлежит комментарий

        $(".icon-menu-comment").mouseover(function(){
            if($(".menu-comment").length == 0){
                var userComment = $(this).parent(".user-comment").attr("class").split("-");
                var fieldRemove = (sessionUser == userComment[3]) ? '<div class="menu-comment-remove">Удалить</div>' :  '<div class="menu-comment-spam">Спам</div>';
                var fieldAnswer = (sessionUser == userComment[3]) ? '' :  '<div class="menu-comment-answer">Ответить</div>';
                var res = '<div class="menu-comment">' + fieldAnswer + fieldRemove;
                //'<div class="menu-comment-quote">Цитировать</div>' +
                //'<div class="menu-comment-quote">Редактировать</div>' +
                //'<div class="menu-comment-like">Нравиться</div>' +
                //'<div class="menu-comment-dislike">Не нравиться</div>' + fieldRemove +
                //'<div style="padding-top: 5px;">Все ответы (0)</div></div>';
                $(this).append(res);
                var idComment = $(this).parent(".user-comment").attr("id").split("-");

                $(".menu-comment-remove").click(function(){
                    var idSectionTable = $('#text-comment').parent().attr("id").split("-");
                    $("#user-comment-"+idComment[2]).remove();
                    $.ajax({
                        url:  document.location.href,
                        type: 'POST',
                        data:{'ajax-query': 'true', 'type-class':'comments', 'method': 'RemoveComment', 'id': idComment[2], 'id-section': idSectionTable[0], 'table-id':idSectionTable[1]},
                        dataType: 'html'
                    });
                })

                $(".menu-comment-spam").click(function(){
                    $("#user-comment-"+idComment[2]).html("Вы отметили данное сообщение как спам");
                    /*$.ajax({
                     url:  document.location.href,
                     type: 'POST',
                     data:{'ajax-query': 'true', 'type-class':'model', 'method': 'RemoveComment', 'id': idComment[2]},
                     dataType: 'html'
                     });*/
                })
                $(".menu-comment-answer").click(function(){
                    $("#text-comment").focus();
                    var nick = $("#user-nick-"+idComment[2]).text();
                    var res = 'Ответ пользователю <b>'+ nick +'</b>' +
                        '<input type="hidden" id="user-comment" value="'+userComment[3]+'" />' +
                        '<input type="hidden" id="id-comment" value="'+idComment[2]+'" />' +
                        '<a href="javascript:void(0)" id="close-answer" style="color: black; padding: 0 0 0px 5px; position: relative; bottom: 8px;left: 5px;">X</a>';
                    $("#action-answer").removeClass("hide").html(res);
                    $("#close-answer").click(function(){
                        $("#text-comment").focus();
                        $("#action-answer").addClass("hide").html("");
                    })
                })

            }
        }).mouseleave(function(){
                if(!$(".menu-comment").is(":hover")){
                    $(this).html('');
                }
            })
    }

    function listComment(){
        var infoTag = $('#text-comment').parent().attr("id");
        var idSectionTable = (infoTag!== undefined) ? infoTag.split("-") : '';
        if(infoTag!== undefined){
            $.ajax({
                url:  document.location.href,
                type: 'POST',
                data:{'ajax-query': 'true', 'type-class':'likes', 'method': idSectionTable[2], 'id-section': idSectionTable[0], 'table-id':idSectionTable[1]},
                dataType: 'html',
                success: function (result){
                    commentsUserLikes = $.parseJSON(result);
                }
            });

            $.ajax({
                url:  document.location.href,
                type: 'POST',
                data:{'ajax-query': 'true', 'type-class':'likes', 'method': 'GetCommentsLikes', 'id-section': idSectionTable[0], 'table-id':idSectionTable[1]},
                dataType: 'html',
                success: function (result){
                    commentsLikes = $.parseJSON(result);
                }
            });


            $.ajax({
                url:  document.location.href,
                type: 'POST',
                data:{'ajax-query': 'true', 'type-class':'comments', 'method': 'ListComments', 'id-section': idSectionTable[0], 'table-id':idSectionTable[1]},
                dataType: 'html',
                success: function (result){
                    renderComment(result, true);
                    renderMenu();
                }
            });
        }
    }

    function renderComment(result, param){
        var comments = $.parseJSON(result); // тестовое изменение
        var serverInfo = comments.pop().split("-");
        sessionUser = parseInt(serverInfo[0]);
        sessionAuth = parseInt(serverInfo[1]);
        var result = '';
        // здесь необходимо сделать проверку если есть
        for(var k in comments){
            var voted = '';
            var liked = '';
            var disliked = '';
            var likesRating = '0';
            var ratingColor = 'black';
            for(var i in commentsUserLikes){
                if(comments[k].id === commentsUserLikes[i].id && commentsUserLikes[i].likes==="1"){
                   voted = ' voted';
                   liked = ' liked';
                }
                if(comments[k].id === commentsUserLikes[i].id && commentsUserLikes[i].dislikes==="1"){
                    voted = ' voted';
                    disliked = ' disliked';
                }
            }
            for(var r in commentsLikes){
                if(comments[k].id === commentsLikes[r].id && commentsLikes[r].rating>0){
                    ratingColor = 'green';
                    likesRating = commentsLikes[r].rating;
                }
                if(comments[k].id === commentsLikes[r].id && commentsLikes[r].rating<0){
                    ratingColor = 'red';
                    likesRating = commentsLikes[r].rating;
                }
            }
            var likes = (sessionUser === parseInt(comments[k].id_user))?'':'' +
                    ' <span class="like' + liked + '">Like</span> ' +
                    '<span class="dislike' + disliked + '">Dislike</span>';
            var likesBlock = '<p class="likes' + voted + '" id="3-' + comments[k].id + '">' +
                '<span class="rating" style="color:' + ratingColor + '">' + likesRating + '</span>' +
                 likes+
                '</p>';
            var imgAvatarAnswer = (comments[k].img_avatar_answer == null || comments[k].img_avatar_answer == "") ? '/skins/img/m.jpg' : comments[k].img_avatar_answer;
            var imgAvatar = (comments[k].img_avatar == null || comments[k].img_avatar == "") ? '/skins/img/m.jpg' : comments[k].img_avatar;
            var nickAnswer = (comments[k].nick_answer == null) ? 'Анонимный' : comments[k].nick_answer;
            var nick = (comments[k].nick == null) ? 'Анонимный' : comments[k].nick;
            var userAuthImg = (sessionAuth===1)?'<div class="right icon-menu-comment"></div>':'';
            var userAnswer = (comments[k].id_user_answer != 0) ?
                '<img src="/skins/img/interface/comment-answer.png" style="position: relative; bottom: 5px; left: 30px;" /></td>' +
                    '<td style="width: 75px"><img src="' + imgAvatarAnswer + '" class="avatar-comment" /></td>' +
                    '<td class="info-comment" style="width:1px; padding-right: 20px;"><b id="user-nick-'+comments[k].id+'">'+nickAnswer+'</b>' +
                    '</td>' : '</td>';
            var date = new Date(comments[k].date * 1000);
            result += '<div style="padding: 15px 0;" class="user-comment user-comment-'+comments[k].id_user+'" id="user-comment-' + comments[k].id + '">' +
                '<table class="left table-comment" style="width: 940px;"><tr>' +
                '<td style="width: 75px;"><img src=' + imgAvatar + ' class="avatar-comment" /></div></td>' +
                '<td class="info-comment"><b id="user-nick-'+comments[k].id+'">'+nick+'</b><br>' +
                '<span>'+ date.getDate() + " " + month[date.getMonth()] + " " + date.getHours() + ":" + date.getMinutes() +'</span>' +
                userAnswer +
                '<td><div class="text-comment"><span>'+comments[k].comment+'</span></div></td></tr></table>' +
                userAuthImg +likesBlock+
            '</div><br class="clear">';
        }
        (param == "last-msg") ? $(".content-comment").append(result) : $(".content-comment").html(result);
        initLikes();   // Инициализация лайков в коментариях
    }

    $("#send-comment").click(function(){
        var comment = $.trim($('#text-comment').val());
        var idUserAnswer = ($('#user-comment').val() == undefined) ? 0 : $('#user-comment').val();
        var idSectionTable = $('#text-comment').parent().attr("id").split("-");
        if (comment != "" && comment.length > 2){
            $.ajax({
                url:  document.location.href,
                type: 'POST',
                data:{'ajax-query': 'true', 'type-class':'comments', 'method': 'AddComment', 'id-user-answer': idUserAnswer, 'comment': comment, 'id-section': idSectionTable[0], 'table-id':idSectionTable[1]},
                dataType: 'html',
                success: function (result){
                    $("#text-comment").val('');
                    $("#action-answer").remove();
                    renderComment(result, "last-msg");
                    renderMenu();
                }
            });
        }else{
            alert("Введите комментарий");
        }
    })

    //Вставка BB-кодов
    var codes = $('#codes').children('span');
    var i = codes.length;
    //var array = ['B', 'U', 'I', 'S'];

    while(i--) {
        $(codes[i]).click(function(e){
            addBB('['+ e.currentTarget.firstChild.nodeName+']', '[/'+e.currentTarget.firstChild.nodeName+']')
        })
         /*codes[i].onclick = function(i) {
            return function() {
                addBB('['+i+']', '[/'+i+']');
                return false;
            };
        }(array[i]);*/

    }

    function addBB(ltag, rtag) {
        var textarea = $('#text-comment')[0];
        textarea.focus();
        if(document.selection && document.selection.createRange) {
            sel = document.selection.createRange();
            if (sel.parentElement() == textarea)  sel.text = ltag + sel.text + rtag;
        }
        else if(typeof(textarea) != undefined) {
            var start = textarea.selectionStart, end = textarea.selectionEnd;
            textarea.value = textarea.value.substring(0, start) + ltag + textarea.value.substring(start, end) + rtag + textarea.value.substring(end, textarea.value.length|0);
        }
        else textarea.value += ltag + rtag;
    }

})