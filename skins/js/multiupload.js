function multiUploader(config){
    this.config = config;
    this.items = "";
    this.all = [];
    this.countImg = 0;
    var self = this;
    multiUploader.prototype.init = function(){
        if (window.File && window.FileReader && window.FileList && window.Blob) {
            $("#"+this.config.form).find("input[type='file']").on("change", this.read);
            $("#"+ this.config.dragArea).on("dragover", function(e){ e.stopPropagation(); e.preventDefault(); });
            $("#"+ this.config.dragArea).on("drop", this._dropFiles);
            if(this.config.img){
                $('body').on("mouseover", this.editImage);
            }
        }
    }

    multiUploader.prototype.read = function(evt){
        self.idFileInput = $(this).attr("id");
        self.idDivParent = $(this).parent().attr("id");
        self.size = $(this).parent().attr("size");
        self.config.method = (self.size === "original") ? "OriginalImg" : self.config.method ;
        if(evt.target.files){
            this.items = evt.target.files;
            self.all.push(evt.target.files);
            self._startUpload();
        } else
            console.log("Failed file reading");
    }


    multiUploader.prototype._validate = function(format){
        var arr = this.config.support.split(",");
        return arr.indexOf(format);
    }

    multiUploader.prototype._dropFiles = function(e){
        e.stopPropagation(); e.preventDefault();
        self._preview(e.dataTransfer.files);
        self.all.push(e.dataTransfer.files);
    }

    multiUploader.prototype._uploader = function(file,f){
        var data = new FormData();
        var ids = file[f].name._unique();
        self.number = f;
        data.append(self.idFileInput, file[f]);
        data.append('index',ids);
        data.append('method', self.config.method);
        $(".dfiles[rel='"+ids+"']").find(".progress").show();
        $.ajax({
            type:"POST",
            url:this.config.uploadUrl,
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                self.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', self.progressHandlingFunction, false);
                }
                return myXhr;
            },
            data:data,
            cache: false,
            contentType: false,
            processData: false,
            success:function(data){
                if(self.config.method === "MainPageGame"){
                    if(self.idDivParent==="video-upload-btn" && data==false){
                        alert("Видео только с расширением .mp4");
                        return;
                    }
                    if( (self.idDivParent.indexOf("screen")>=0 || self.idDivParent.indexOf("img")>=0) && data==false){
                        alert("Изображения только с расширением .jpg .jpeg .png");
                        return;
                    }
                    var arrFile = $.parseJSON(data);
                    self.renderImage(arrFile);
                }else{
                    var arrFile = $.parseJSON(data);
                    self.renderImage(arrFile);
                }
            }
        });
        if (f+1 < file.length) {
            self._uploader(file,f+1);
        }
    }
    multiUploader.prototype.renderImage = function(arrFile){
        $("#error-img").remove();
        if(self.idFileInput === 'video-file'){
            var singleHtml = '<div class="" style="position: relative; bottom: 2px;">' +
                '<video class="video-js vjs-default-skin" controls preload="none" width="420" height="305" poster="" data-setup="{}">' +
                '<input type="hidden" name="video-link" value="'+arrFile[0]+'">'+
                '<input type="hidden" name="video-img" value="">'+
                '<source src="/'+arrFile[0]+'" type="video/mp4" />'+
                '</video></div><div style="clear: both;"></div><br>';

        }
        else if(self.idFileInput === 'audio-file'){
            var singleHtml = '<audio  controls preload="none" data-setup="{}"><source src="/'+arrFile[0]+'" type="audio/mpeg" /></audio>'+
                '<input type="hidden" name="audio-link" value="'+arrFile[0]+'">'+
                '<div style="clear: both;"></div><br>';
        }
        else{
            var multiHtml = "<div class='edit-image'><img src='/"+arrFile[0]+"' /><input type='hidden' class='"+arrFile[1]+"' name='"+arrFile[1]+"[]' value='"+arrFile[0]+"' /></div>";
            var singleHtml = "<div class='edit-image'><img src='/"+arrFile[0]+"' /><input type='hidden' name='"+arrFile[1]+"' id='"+arrFile[1]+"' value='"+arrFile[0]+"' /></div>";
        }
        if(arrFile.length <= 1){
            $("#"+ self.idDivParent).after('<div id="error-img" class="right error">'+arrFile[0]+'</div>');
        } else {
            if(self.config.multi){
                self.countImg = $("."+arrFile[1]).length;
                $("#"+ self.idDivParent).after(multiHtml);
                if(self.countImg == self.config.limit-1){
                    $("#"+ self.idDivParent).after(multiHtml);
                    $("#"+self.idDivParent).hide();
                }
            } else {
                $("#"+ self.idDivParent).after(singleHtml);
                $("#"+self.idDivParent).hide();
            }
        }
    }

    multiUploader.prototype.editImage = function(){
        $('.edit-image').mouseover(function(){
            if($("#delete-images").length == 0){
                var obj = $(this);
                var $delButton = $('<div/>', {id: "delete-images"}).click(function() {
                    self.deleteImg(obj);
                });
                var widthIconDel = $(this).find('img').width()-15;
                $('.edit-image').css({"width":$(this).find('img').width()+"px"});
                $(this).find('img').before($delButton);
                $(this).find("#delete-images").fadeIn(200).css({'left': widthIconDel+'px'});
            }
            $('#delete-images').mouseover(function(){
                $('#delete-images').stop().animate({opacity: 1}, 200);
            }).mouseleave(function(){
                    $('#delete-images').stop().animate({opacity: 0.6}, 200);
                })
        }).mouseleave(function(){
                $("#delete-images").remove();
            })
        self.all = [];
    }

    multiUploader.prototype.deleteImg = function(link){
        --self.countImg;
        var val = $(link).find("input[type=hidden]").attr("name");
        var showBtn = ($(link).attr("name") === "show-btn") ? true : false;
        if(val == "source_img_s"){
            var name = Array("Иконка", "icon-upload-btn");
        } else {
            var name = Array("Изображение", "img-upload-btn");
        }
        var deletedImg = ($(link[0]).find("input[type=hidden]").val());
        var DelImg = '<input type="hidden" name="deletedImg[]" value="' + deletedImg + '">';
        if(self.idDivParent !== undefined){
            self.idDivParent = self.idDivParent;
        }else{
            var test = $(link[0]).parent().append(DelImg);
        }
        if(showBtn){
            var btn = $($(link).parent().children()[0]).css("display", "inline-block");
        }
        $(link).remove();
        var html = '<div id="'+name[1]+'" class="container upload"><span class="btn">'+name[0]+'</span><input type="file" name="'+val+'" id="'+val+'" /></div>';
        //debugger;
        if(self.config.multi){
            var limit = self.config.limit-1 - self.countImg;
            if(limit == 1){
                $("#"+self.idDivParent).show();
                $(link).after(html);
            }
        }else{
            $("#"+self.idDivParent).show();
            $(link).after(html);
        }
    }

    multiUploader.prototype.xhr = function(){
        if(self.config.visualProgress == "modal"){
            showModal('upload-process-ajax-modal');
            $("#upload-process").html('<div class="progress_container"><div class="progress_bar tip"></div></div>');
        }else{
            $("#upload-process-ajax-modal").html('<div class="info-ajax-modal" ><div class="progress_container">' +
                '<div class="progress_bar tip"></div></div></div>');
        }
        $(".progress_container").css("margin","10px 0");
    }

    multiUploader.prototype._startUpload = function(){
        if(this.config.limit && this.all[0].length > this.config.limit){
            $("#"+this.config.form).append('<b class="error">Максимум можно прикрепить до 6 файлов</b>')
        } else if (this.all.length > 0){
            for(var k=0; k<this.all.length; k++){
                var file = this.all[k];
                this._uploader(file,0);
                $(".error").remove();
            }
        }
    }
    multiUploader.prototype.progressHandlingFunction = function(e){
        if (e.lengthComputable) {
            var percentComplete = parseInt((e.loaded / e.total) * 100);
            $('.tip').animate({width: percentComplete + "%"}, 10);
            if(percentComplete == 100){
                $.arcticmodal('close');
                setInterval(function(){
                    $(".progress_container").remove();
                }, 500)

            }
        }
    }

    String.prototype._unique = function(){
        return this.replace(/[a-zA-Z]/g, function(c){
            return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
        });
    }

    this.init();
}

function initMultiUploader(){
    new multiUploader(config);
}

