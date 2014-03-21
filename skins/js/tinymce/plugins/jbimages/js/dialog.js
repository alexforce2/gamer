var filesDialog = {
	resized : false,
	iframeOpened : false,
	timeoutStore : false,

    uploadFile: function (urlPath, typeFile){
        var data = new FormData();
        var error = '';
        jQuery.each($('.file')[0].files, function (i, file) {
            data.append('file-' + i, file);
        });
        data.append('type-file', typeFile);
        data.append('method', 'UploadImgTinyMce');
        if (error != '') {
            $('#info').html(error);
        } else {
            $.ajax({
                url: urlPath,
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    debugger;
                    var w = filesDialog.getWin();
                    tinymce = w.tinymce;
                    if(typeFile == "img")
                        tinymce.EditorManager.activeEditor.insertContent('<a class="gallery-tinymce" href="/'+ data +'"><img src="/' + data +'" /></a>');
                    else
                        tinymce.EditorManager.activeEditor.insertContent('<a href="/' + data +'" />Добавьте название</a>');
                    filesDialog.close();
                },
                error: errorHandler = function (i) {
                    $(".progress_container").remove();
                    $('#upload-process').html('Ошибка загрузки файлов');
                }
            });
        }
    },
	getWin : function() {
		return (!window.frameElement && window.dialogArguments) || opener || parent || top;
	},
	close : function() {
		var t = this;
		function close() {
			tinymce.EditorManager.activeEditor.windowManager.close(window);
			tinymce = tinyMCE = t.editor = t.params = t.dom = t.dom.doc = null; // Cleanup
		};
		(tinymce.isOpera) ? this.getWin().setTimeout(close, 0) : close();
	}

};