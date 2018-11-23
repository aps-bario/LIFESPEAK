<DIV class="topmenu">
            <DIV class="topmenuitem"> <!--style="float:left;"-->
                <a  href="../public/lifespeak.php">Home</a>
            </DIV>
            <DIV class="topmenuitem">
                <a href="../visitors/visitorspage.php">Visitors</a>
            </DIV>
            <DIV class="topmenuitem">                
                <a href="../clients/clientspage.php">Clients</a>
                </DIV>
            <DIV class="topmenuitem">  
                <a href="../coaches/coachespage.php">Coaches</a>
            </DIV>
            <DIV class="topmenuitem">
                 <a href="../admin/adminpage.php">Admin</a>
            </DIV>
            <DIV class="topmenuitem"><?php 
if(isset($_SESSION['UserStatus'])) { 
    echo '<a href="../public/login.php">Logout</a>';
} else {
    echo '<a href="../public/login.php">Login</a>';
}?>
</DIV>
</DIV>