<!-- Header -->
<div id="header">
    <!-- Top -->
    <div id="top">
        <!-- Logo -->
        <div class="logo"> 
            <a href="/issedit/" title="Administration Home" class="tooltip"><img src="/issedit/resources/assets/logo.png" alt="My Admin" /></a> 
        </div>
        <!-- End of Logo -->

        <!-- Meta information -->
        <div class="meta">
            <p>Welcome, <?= $f->adminName(); ?>! <a href="/" target="_blank">View the site</a></p>
            <ul>
                <li><a href="/issedit/work.php?action=logout" title="End administrator session" class="tooltip"><span class="ui-icon ui-icon-power"></span>Logout</a></li>
                <?php
                $adminId = $_SESSION['loged_admin'];
                
                $query_settings = mysql_query("SELECT * FROM administrators WHERE id= $adminId") or die(mysql_error());
                $adminsQuest = mysql_fetch_array($query_settings);
                
                ?>
                <li><a href="/issedit/module_settings/index.php" title="Change current settings" class="tooltip"><span class="ui-icon ui-icon-wrench"></span>Settings</a></li>
                
                <li><a href="/issedit/module_administrators/edit_admin.php?admin_id=<?= $_SESSION['admin_info']['id']; ?>" title="Go to your account" class="tooltip"><span class="ui-icon ui-icon-person"></span>My account</a></li>
            </ul>	

        </div>
        <!-- End of Meta information -->
    </div>
    <!-- End of Top-->

    <?php include("navigation.php"); ?>
</div>
<!-- End of Header -->