var gs11Service = function()
{
    this.requestParams = {};
};

var serviceGS11 = gs11Service.prototype;
var serviceGS = gs11Service();
serviceGS11.uploadAvatar = function (urlPath){
    var data = new FormData();
    var error = '';
    jQuery.each($('#file')[0].files, function (i, file) {
        data.append('file-' + i, file);
    });

    if (error != '') {
        $('#info').html(error);
    } else {
        $.ajax({
            url: urlPath,
            type: 'POST',
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                showModal('upload-process-ajax-modal');
                $("#upload-process").html('<div class="progress_container"><div class="progress_bar tip"></div></div>');
                $(".progress_container").css("margin","10px 0");
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
                }
                return myXhr;
            },
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                location.reload();
            },
            error: errorHandler = function (i) {
                $(".progress_container").remove();
                $('#upload-process').html('Ошибка загрузки файлов');
            }
        });
    }
    function progressHandlingFunction(e) {
        if (e.lengthComputable) {
            var percentComplete = parseInt((e.loaded / e.total) * 100);
            $('.progress_bar').animate({width: percentComplete + "%"}, 10);
        }
    }
}