<?php
use classes\render as Render;
use classes\url as Url;

?>

<script type="text/javascript">
    function create_paging(current, total) {
        var page_size = 10;
        var template = $("#paging_template").html();
        var paging_html = "";
        var total_page = Math.floor((total / page_size) + 1);
        //if (total_page<1) total_page=1;
        var ismore = (current + 10) < total_page;
        var isless = (current - 10) > 0;
        var min = isless ? (current - 9) : 1;
        var max = ismore ? (current + 9) : total_page;
        isless && (paging_html += (template.replace(/{i}/g, min - 1).replace(/{title}/g, "...")));
        for (i = min; i <= max; i++) {
            paging_html += (template.replace(/{i}/g, i).replace(/{title}/g, i));
        }
        ismore && (paging_html += (template.replace(/{i}/g, max + 1).replace(/{title}/g, "...")));
        $(paging_html).find("a[data-page='" + current + "']").addClass("current");
        $("td#paging").html(paging_html);
        $("td#paging").find("a[data-page='" + current + "']").addClass("current");

    }
</script>

