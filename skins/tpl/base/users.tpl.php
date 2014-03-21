<script type="text/javascript" src="/skins/js/validation.js"></script>
<div id="featured-section" style="position: relative; z-index: 10;" class="FL">
    <table>
        <tr>
            <td style="vertical-align: top;">
                <div id="content" style="width: 668px;">

                    <div id="content-profile">

                        <div class='left'>

                            <div class="game-content">

                                <ol><h1>Пользователи!</h1>
                                    <?php
                                    foreach($data['users-completed-game'] as $user){?>
                                        <li><a href="/base/UserGames/<?=$user['id']?>"><?=$user['nick']?></a></li>
                                    <?php }
                                    ?>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>