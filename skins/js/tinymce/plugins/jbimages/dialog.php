<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>Загрузить файл</title>
	<script type="text/javascript" src="js/dialog.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.min.js"></script>
    <script type="text/javascript">
        function actionUpload(){
            debugger;
            urlPath = $('#url').val();
            typeFile = $('#type-method-file').val();
            filesDialog.uploadFile(urlPath, typeFile);
        }
        function changeTypeUpload(param){
            $("#div-"+param).html('<input type="file" class="file" name="file[]" onchange="actionUpload()" />');
            if(param == 'img'){
                $("#div-img").slideToggle("fast");
                $("#div-file").slideUp("fast");
                $("#type-method-file").val('img');
            } else if(param == 'file'){
                $("#div-file").slideToggle("fast");
                $("#div-img").slideUp("fast");
                $("#type-method-file").val('file');
            }
        }
    </script>

</head>
<body>
    <div>
        <b style="cursor: pointer; " onclick="changeTypeUpload('img')">Загрузить изображение</b>
        <div id="div-img" style="display: none">
        </div>
    </div><br>

    <div>
        <b style="cursor: pointer; " onclick="changeTypeUpload('file')">Загрузить файл</b>
        <div id="div-file" style="display: none">
        </div>
    </div>
    <input type="hidden" id="url" value="<?=$_SERVER['HTTP_REFERER']; ?>" />
    <input type="hidden" id="type-method-file" />
</body>
</html>