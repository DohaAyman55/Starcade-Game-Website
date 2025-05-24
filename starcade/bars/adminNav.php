<div id="navigation">
    <div id="nav-left">
        <img id="icon" src="../images/icons/spaceship.png"/>
        <h1>STARCADE</h1>
    </div>
    <div id="nav-right">
        <div id="nav-choices">
            <a href="Dashboard.php">HOME</a>
            <a href="#about">ABOUT</a>
            <a href="#contact">CONTACT US</a>

            <?php if (isset($_SESSION['username'])): ?>
                <div class="dropdown-menu">
                    <button class="dropdown-button"><?php echo htmlspecialchars($_SESSION['username']); ?> ▼</button>
                        <div class="dropdown-content">
                        <a href="adminProfile.php">View Profile</a>
                        <a href="../php/logout.php">Logout</a>
                    </div>
                </div>                    
            <?php else: ?>
                <a href="../LoginForm.php">LOGIN</a>
            <?php endif; ?>        
        </div>   
        <div> 
            <form class="search-bar">                                   <!--HANDLE IN JAVASCRIPT-->
                <input type="search" placeholder="Search" required>
                <button type="submit">
                    <img src="../images/icons/search.png" width="16px" height="16px" alt="search"/>
                </button>
            </form>
        </div>    
    </div>
</div>