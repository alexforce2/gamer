var sliderNext = {
    init: function(){
        $("body").on("click", ".slider-action", function(){
            var page = $(this).attr("page");
            sliderNext.sendAjax(page);
            history.pushState(null, null, "?page=" + page);
        });
        sliderNext.backNextPage();
    },
    sendAjax: function(page){
        $.ajax({
            type: 'POST',
            url: document.location.href,
            data: {"page": page},
            success: function(data){
                $('#main').html(data).hide().fadeIn(250);
                sliderNext.changeTitle(page);
            }
        });
    },
    backNextPage: function(){
        window.onpopstate = function(){
            var arrPath = history.location.href.split("=");
            sliderNext.sendAjax(arrPath[1]);

        }
    },
    changeTitle: function(page){
       var arr = page.split('-');
        for(var i = 1; i < arr.length; i++){
            arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1);
        }
        for(var key in links){
            if(key == arr.join('')){
                $('title').text(links[key]);break;
            }
        }
    }
};
var links = {
    legendTournament: 'Легендарные Off-line турниры',
    nextTournament: 'Ближайшие Off-line турниры',
    winner: 'Победители Off-line турниров'
};

$(document).ready(function(){
    sliderNext.init();

});
