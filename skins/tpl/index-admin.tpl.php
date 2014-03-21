<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title><?= $this->title; ?></title>
    <link rel="stylesheet" href="/skins/css/admin.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/skins/css/video-js.css" type="text/css" media="screen"/>

    <script type="text/javascript" src="/skins/js/jquery.min.js"></script>
    <script type="text/javascript" src="/skins/js/script.js"></script>
    <script type="text/javascript" src="/skins/js/script-admin.js"></script>
    <script type="text/javascript" src="/skins/js/arctic-modal.min.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="/skins/js/jquery.customSelect.js"></script>
    <script type="text/javascript" src="/skins/js/service.js"></script>
    <script type="text/javascript" src="/skins/js/multiupload.js"></script>
    <script type="text/javascript" src="/skins/js/likes.js"></script>
    <script type="text/javascript" src="/skins/js/comments.js"></script>
    <script type="text/javascript" src="/skins/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="/skins/js/tinymce/jquery.tinymce.min.js"></script>
    <script type="text/javascript">

        (function($){
            // add a new method to JQuery

            $.fn.equalHeight = function() {
                // find the tallest height in the collection
                // that was passed in (.column)
                tallest = 0;
                this.each(function(){
                    thisHeight = $(this).height();
                    if( thisHeight > tallest)
                        tallest = thisHeight;
                });

                // set each items height to use the tallest value found
                this.each(function(){
                    $(this).height(tallest);
                });
            }
        })(jQuery);


        $(function(){
            //$('.column').equalHeight();
        });
        $(document).ready(function () {

            var showText = 'Показать';
            var hideText = 'Скрыть';
            var is_visible = false;
            $('.toggle').prev().append(' <a href="#" class="toggleLink">' + hideText + '</a>');
            $('.toggle').show();
            $('a.toggleLink').click(function () {
                if ($(this).text() == showText) {
                    $(this).text(hideText);
                    $(this).parent().next('.toggle').slideDown('slow');
                }
                else {
                    $(this).text(showText);
                    $(this).parent().next('.toggle').slideUp('slow');
                }
                return false;
            });
        });
        $(function(){
//        $('.column').equalHeight();

            var contTop = $('#header').height() + $('#secondary_bar').height();
            var searchH = $('#sidebar .quick_search').outerHeight() + $('#sidebar hr').outerHeight();
            var sidebarBot = $('#sidebar footer').height();
            $('.content_wr').css({"top":contTop -2 + "px"});
            $('#main .autoscroll_wr').css({"top":contTop + "px"});
            $('#sidebar .autoscroll_wr').css({"top":contTop + searchH + "px","bottom": sidebarBot + "px"});
        });

        $(document).ready(function () {
            resizeMenu();
            function resizeMenu(){
                var height =  $(".content").height() + 15;
                if(height > $(window).height()){
                    $("#sidebar").height(height);
                    $("#main").height(height);
                }
            }





            $(".tab_content").hide();
            $("ul.tabs li:first").addClass("active").show();
            $(".tab_content:first").show();

            //On Click Event
            $("ul.tabs li").click(function () {

                $("ul.tabs li").removeClass("active");
                $(this).addClass("active"); //Add "active" class to selected tab
                $(".tab_content").hide(); //Hide all tab content

                var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
                $(activeTab).fadeIn(); //Fade in the active ID content
                return false;
            });

        });
    </script>


</head>


<body>

<header id="header">
    <hgroup>
        <h1 class="site_title"><a href="/administration/main">Администрирование GS11</a></h1>
        <h2 class="section_title"><?= $this->title; ?></h2>
        <div class="btn_view_site"></div>
    </hgroup>
</header>
<section id="secondary_bar">
    <div class="user">
        <p>root</p>
        <a class="logout_user" href="/administration/logout" title="Выход"></a>
    </div>
    <div class="breadcrumbs_container">
        <article class="breadcrumbs">
            <a href="index.html">Website Admin</a>
            <div class="breadcrumb_divider"></div>
            <a class="current">Dashboard</a>
        </article>
    </div>
</section>

<div class="content_wr">
<? include $tplAuth->menu; ?>

<section id="main" class="column">
    <div class="autoscroll_wr">
        <? include $contentView; ?>
    </div>
</section>
    </div>

</body>
</html>

<?
/*



<h4 class="alert_info">Welcome to the free MediaLoot admin panel template, this could be an informative message.</h4>

<article class="module width_full">
    <header><h3>Stats</h3></header>
    <div class="module_content">
        <article class="stats_graph">
            <img src="http://chart.apis.google.com/chart?chxr=0,0,3000&chxt=y&chs=520x140&cht=lc&chco=76A4FB,80C65A&chd=s:Tdjpsvyvttmiihgmnrst,OTbdcfhhggcTUTTUadfk&chls=2|2&chma=40,20,20,30" width="520" height="140" alt="" />
        </article>

        <article class="stats_overview">
            <div class="overview_today">
                <p class="overview_day">Today</p>
                <p class="overview_count">1,876</p>
                <p class="overview_type">Hits</p>
                <p class="overview_count">2,103</p>
                <p class="overview_type">Views</p>
            </div>
            <div class="overview_previous">
                <p class="overview_day">Yesterday</p>
                <p class="overview_count">1,646</p>
                <p class="overview_type">Hits</p>
                <p class="overview_count">2,054</p>
                <p class="overview_type">Views</p>
            </div>
        </article>
        <div class="clear"></div>
    </div>
</article><!-- end of stats article -->

<article class="module width_3_quarter">
    <header><h3 class="tabs_involved">Content Manager</h3>
        <ul class="tabs">
            <li><a href="#tab1">Posts</a></li>
            <li><a href="#tab2">Comments</a></li>
        </ul>
    </header>

    <div class="tab_container">
        <div id="tab1" class="tab_content">
            <table class="tablesorter" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Entry Name</th>
                    <th>Category</th>
                    <th>Created On</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Lorem Ipsum Dolor Sit Amet</td>
                    <td>Articles</td>
                    <td>5th April 2011</td>
                    <td><input type="image" src="/skins/img/admin/icn_edit.png" title="Edit"><input type="image" src="/skins/img/admin/icn_trash.png" title="Trash"></td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Ipsum Lorem Dolor Sit Amet</td>
                    <td>Freebies</td>
                    <td>6th April 2011</td>
                    <td><input type="image" src="/skins/img/admin/icn_edit.png" title="Edit"><input type="image" src="/skins/img/admin/icn_trash.png" title="Trash"></td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Sit Amet Dolor Ipsum</td>
                    <td>Tutorials</td>
                    <td>10th April 2011</td>
                    <td><input type="image" src="/skins/img/admin/icn_edit.png" title="Edit"><input type="image" src="/skins/img/admin/icn_trash.png" title="Trash"></td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Dolor Lorem Amet</td>
                    <td>Articles</td>
                    <td>16th April 2011</td>
                    <td><input type="image" src="/skins/img/admin/icn_edit.png" title="Edit"><input type="image" src="/skins/img/admin/icn_trash.png" title="Trash"></td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Dolor Lorem Amet</td>
                    <td>Articles</td>
                    <td>16th April 2011</td>
                    <td><input type="image" src="/skins/img/admin/icn_edit.png" title="Edit"><input type="image" src="/skins/img/admin/icn_trash.png" title="Trash"></td>
                </tr>
                </tbody>
            </table>
        </div><!-- end of #tab1 -->

        <div id="tab2" class="tab_content">
            <table class="tablesorter" cellspacing="0">
                <thead>
                <tr>
                    <th></th>
                    <th>Comment</th>
                    <th>Posted by</th>
                    <th>Posted On</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Lorem Ipsum Dolor Sit Amet</td>
                    <td>Mark Corrigan</td>
                    <td>5th April 2011</td>
                    <td><input type="image" src="/skins/img/admin/icn_edit.png" title="Edit"><input type="image" src="/skins/img/admin/icn_trash.png" title="Trash"></td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Ipsum Lorem Dolor Sit Amet</td>
                    <td>Jeremy Usbourne</td>
                    <td>6th April 2011</td>
                    <td><input type="image" src="/skins/img/admin/icn_edit.png" title="Edit"><input type="image" src="/skins/img/admin/icn_trash.png" title="Trash"></td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Sit Amet Dolor Ipsum</td>
                    <td>Super Hans</td>
                    <td>10th April 2011</td>
                    <td><input type="image" src="/skins/img/admin/icn_edit.png" title="Edit"><input type="image" src="/skins/img/admin/icn_trash.png" title="Trash"></td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Dolor Lorem Amet</td>
                    <td>Alan Johnson</td>
                    <td>16th April 2011</td>
                    <td><input type="image" src="/skins/img/admin/icn_edit.png" title="Edit"><input type="image" src="/skins/img/admin/icn_trash.png" title="Trash"></td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Dolor Lorem Amet</td>
                    <td>Dobby</td>
                    <td>16th April 2011</td>
                    <td><input type="image" src="/skins/img/admin/icn_edit.png" title="Edit"><input type="image" src="/skins/img/admin/icn_trash.png" title="Trash"></td>
                </tr>
                </tbody>
            </table>

        </div><!-- end of #tab2 -->

    </div><!-- end of .tab_container -->

</article><!-- end of content manager article -->

<article class="module width_quarter">
    <header><h3>Messages</h3></header>
    <div class="message_list">
        <div class="module_content">
            <div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
                <p><strong>John Doe</strong></p></div>
            <div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
                <p><strong>John Doe</strong></p></div>
            <div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
                <p><strong>John Doe</strong></p></div>
            <div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
                <p><strong>John Doe</strong></p></div>
            <div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
                <p><strong>John Doe</strong></p></div>
        </div>
    </div>
    <footer>
        <form class="post_message">
            <input type="text" value="Message" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
            <input type="submit" class="btn_post_message" value=""/>
        </form>
    </footer>
</article><!-- end of messages article -->

<div class="clear"></div>

<article class="module width_full">
    <header><h3>Post New Article</h3></header>
    <div class="module_content">
        <fieldset>
            <label>Post Title</label>
            <input type="text">
        </fieldset>
        <fieldset>
            <label>Content</label>
            <textarea rows="12"></textarea>
        </fieldset>
        <fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
            <label>Category</label>
            <select style="width:92%;">
                <option>Articles</option>
                <option>Tutorials</option>
                <option>Freebies</option>
            </select>
        </fieldset>
        <fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
            <label>Tags</label>
            <input type="text" style="width:92%;">
        </fieldset><div class="clear"></div>
    </div>
    <footer>
        <div class="submit_link">
            <select>
                <option>Draft</option>
                <option>Published</option>
            </select>
            <input type="submit" value="Publish" class="alt_btn">
            <input type="submit" value="Reset">
        </div>
    </footer>
</article><!-- end of post new article -->

<h4 class="alert_warning">A Warning Alert</h4>

<h4 class="alert_error">An Error Message</h4>

<h4 class="alert_success">A Success Message</h4>

<article class="module width_full">
    <header><h3>Basic Styles</h3></header>
    <div class="module_content">
        <h1>Header 1</h1>
        <h2>Header 2</h2>
        <h3>Header 3</h3>
        <h4>Header 4</h4>
        <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Cras mattis consectetur purus sit amet fermentum. Maecenas faucibus mollis interdum. Maecenas faucibus mollis interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>

        <p>Donec id elit non mi porta <a href="#">link text</a> gravida at eget metus. Donec ullamcorper nulla non metus auctor fringilla. Cras mattis consectetur purus sit amet fermentum. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>

        <ul>
            <li>Donec ullamcorper nulla non metus auctor fringilla. </li>
            <li>Cras mattis consectetur purus sit amet fermentum.</li>
            <li>Donec ullamcorper nulla non metus auctor fringilla. </li>
            <li>Cras mattis consectetur purus sit amet fermentum.</li>
        </ul>
    </div>
</article><!-- end of styles article -->
<div class="spacer"></div>

 * */


?>