<!-- Header -->
<div id="header">
    <!-- Top -->
    <div id="top">
        <!-- Logo -->
        <div class="logo"> 
            <a href="/admin/" title="Administration Home" class="tooltip"><img src="/admin/resources/assets/logo.png" alt="My Admin" /></a> 
        </div>
        <!-- End of Logo -->

        <!-- Meta information -->
        <div class="meta">
            <p>Welcome, <?= $f->adminName(); ?>! <a href="/" target="_blank">View the site</a></p>
            <ul>
                <li><a href="/admin/work.php?action=logout" title="End administrator session" class="tooltip"><span class="ui-icon ui-icon-power"></span>Logout</a></li>
                <?php
                $adminId = $_SESSION['loged_admin'];
                
                $query_settings = mysqli_query($conn,"SELECT * FROM administrators WHERE id= $adminId") or die(mysqli_error($conn));
                $adminsQuest = mysqli_fetch_array($query_settings);
                
                ?>
                <li><a href="/admin/module_settings/index.php" title="Change current settings" class="tooltip"><span class="ui-icon ui-icon-wrench"></span>Settings</a></li>
                
                <li><a href="/admin/module_administrators/edit_admin.php?admin_id=<?= $_SESSION['admin_info']['id']; ?>" title="Go to your account" class="tooltip"><span class="ui-icon ui-icon-person"></span>My account</a></li>
            </ul>	

        </div>
        <!-- End of Meta information -->
    </div>
    <!-- End of Top-->

    <?php include("navigation.php"); ?>
</div>
<!-- End of Header -->