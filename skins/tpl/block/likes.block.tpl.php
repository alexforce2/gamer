<?php
$color = ($likes['ucg-likes']['likes'] > 0) ? "green" : "";
$color = ($likes['ucg-likes']['likes'] < 0) ? "red" : $color;
$likesCount = ($likes['ucg-likes']['likes'] > 0 || $likes['ucg-likes']['likes'] < 0) ? $likes['ucg-likes']['likes'] : "0";
if (!empty($this->user)) {
    ?>
    <p class="likes<?= ($likes['user-likes'] !== false) ? ' voted' : '' ?>" id="<?= $likes['likes-group']; ?>-<?= $likes['id-ucg']; ?>">
                                        <span class="rating" style="color:<?= $color ?>;">
                                            <?= $likesCount; ?>
                                        </span>

        <span class="like<?= ($likes['user-likes']['likes'] === "1") ? ' liked' : '' ?>">Like</span>
        <span class="dislike<?= ($likes['user-likes']['dislikes'] === "1") ? ' disliked' : '' ?>">Dislike</span>

    </p>
<? } else { ?>
    <p><span class="rating" style="color:<?= $color ?>;"><?= $likesCount; ?></span></p>
<? } ?>