
<style>#profile{
    background:<?php echo $logininfo[3]?>;

}</style>
<script src = "static/js/nav.js"></script>
    <nav class = "nav" id = "nav" >
        <?php if (!$logininfo){?>
            <div class = "nav__cell--special" id = "unlock_more">Sign Up to Access More</div>
            <?php } ?>
                <a href = "index.php?filename=Home" class = "nav__cell"  id = "home">
                    <input type = "button" value = "About" class = "nav__btn">
                </a>
                <?php if ($logininfo){?>
                <a href = "index.php?filename=Notes" class = "nav__cell" id = "notes">                    
                    <input type = "button"  value = "My Notes" class = "nav__btn">
                   
                </a>
                
                <a href = "index.php?filename=Create" class = "nav__cell" id = "create">
                    <input type = "button"  value = "Create" class = "nav__btn">
                </a>
                <?php }?>
                <a class = "nav__cell" id = "search" href = "index.php?filename=Search">
                    <input type = "button"  value = "Library" class = "nav__btn">
                </a>
                <a target = "_blank" href = "https://rdev.x10.mx" class = "nav__cell nav__cell--rdev">
                    <div >
                        <input alt = "rdev website" class = "nav__cell-image nav__cell-image--rdev" type = "image" src = "static/assets/webp/rdev.webp">
                    </div>
                </a>
                <div class = "nav__cell nav__cell--pfp" style = "position:relative;">
                    <div style = "position:relative">
                        <img class = "nav__cell-image" src = "<?php echo $pfpfilename?>" id = "profile" onerror = "this.src = 'static/assets/webp/default.webp'" alt = "profile">
                        <?php
                        if ($notifcount){
                        ?>
                        <div class = "nav__cell-notif-count">
                        <?php echo $notifcount; ?>
                        </div>
                        <?php } ?>
                        
                        </div>
                </div>
        
        <!-- <img src = "assets/placeholder.png" id = "website_logo"> -->
    </nav>
