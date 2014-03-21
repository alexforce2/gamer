<?php
use classes\render as Render;
use classes\url as Url;

?>

<!--<form method="POST" id="filter_form" action="--><?//= Url::Action("filter") ?><!--">-->
<!--    <div class="place" style="margin-right: 10px;">-->
<!--        <div id="year_field" class="left">-->
<!--            <label>Год</label>-->
<!--            <br/>-->
<!--            <select id="year_filter" class="block">-->
<!--                <option></option>-->
<!--                <option>2013</option>-->
<!--                <option>2012</option>-->
<!--                <option>2011</option>-->
<!--                <option>2010</option>-->
<!--                <option>2009</option>-->
<!--            </select>-->
<!--        </div>-->
<!--        <div id="month_field" class="left hide">-->
<!--            <label>Месяц</label>-->
<!--            <br/>-->
<!--            <select id="month_filter" class="block">-->
<!--                <option value=""></option>-->
<!--                <option value="1">Январь</option>-->
<!--                <option value="2">Февраль</option>-->
<!--                <option value="3">Март</option>-->
<!--                <option value="4">Апрель</option>-->
<!--                <option value="5">Май</option>-->
<!--                <option value="6">Июнь</option>-->
<!--                <option value="7">Июль</option>-->
<!--                <option value="8">Август</option>-->
<!--                <option value="9">Сентябрь</option>-->
<!--                <option value="10">Октябрь</option>-->
<!--                <option value="11">Ноябрь</option>-->
<!--                <option value="12">Декабрь</option>-->
<!--            </select>-->
<!--        </div>-->
<!--        <div id="day_field" class="left hide">-->
<!--            <label>День</label>-->
<!--            <input id="day_filter" class="block">-->
<!---->
<!--        </div>-->
<!--    </div>-->
<!--</form>-->

<script type="text/javascript">
    //Todo: скрипт вынести в отдельный файл
    //$.datepicker.setDefaults($.datepicker.regional['ru']);
    $(function () {
            var filter_url = $("#filter_form").attr("action");
            var $year_filter = $("#year_filter");
            var $month_filter = $("#month_filter")
            $("#year_filter")
                .change(function () {
                    var $this = $(this);
                    $this.val() != '' ? $("#month_field").show() : hide_month_field();
                    filter_list();
                });

            $("#month_filter")
                .change(function () {

                    var $this = $(this);
                    $this.val() != '' ? $("#day_field").show() : hide_day_field();
                    var minDate = new Date($year_filter.val(), $month_filter.val() - 1, 1);
                    var maxDate = new Date($year_filter.val(), $month_filter.val(), 1);
                    ;
                    maxDate.setDate(maxDate.getDate() - 1);
                    $("#day_filter")
                        .val("")
                        .datepicker('option', {minDate: minDate, maxDate: maxDate});
                    filter_list();
                });

            /*$("#day_filter")
                .datepicker()
                .change(function () {
                    filter_list();
                });*/

            $("body").on("click", "a.pager-item", function () {
                var data = $(this).data();
                filter_list(data.page);
                return false;
            });

            $("body").on("click", "a[data-action='delete']", function () {
                var link = this;
                if (confirm("Вы действительно хотите удалить запись?")) {
                    $.ajax({
                        url: $(this).attr("href"),
                        type: "POST",
                        success: function (data) {
                            alert("Запись удалена.");
                            $(link).closest("tr").remove();
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });
                }
                return false;
            });

            function hide_month_field() {
                $("#month_filter").val("");
                $("#month_field").hide();
                hide_day_field();
            }

            function hide_day_field() {
                $("#day_filter").val("");
                $("#day_field").hide();
            }


            function filter_list(page) {
                var data = {};
                if ($("#year_filter").val() != "") data["year"] = $("#year_filter").val();
                if ($("#month_filter").val() != "") data["month"] = $("#month_filter").val();
                var day = $("#day_filter").val();
                if (day) data["day"] = day;
                data["page"] = page;
                $.ajax({
                    url: filter_url,
                    data: data,
                    dataType: "json",
                    success: function (data) {
                        update_table(data);
                    }
                });
            }
        }
    );


</script>