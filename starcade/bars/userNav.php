<div id="navigation">
    <div id="nav-left">
        <img id="icon" src="images/icons/spaceship.png"/>
        <h1>STARCADE</h1>
    </div>
    <div id="nav-right">
        <div id="nav-choices">
            <a href="<?php echo isset($_SESSION['username']) ? 'mainpage.php' : 'Home.php'; ?>">HOME</a>
            <a href="#about">ABOUT</a>
            <a href="#contact">CONTACT US</a>
            <a href="leaderboard.php">LEADERBOARD</a>

            <?php if (isset($_SESSION['username'])): ?>
                <div class="dropdown-menu">
                    <button class="dropdown-button"><?php echo htmlspecialchars($_SESSION['username']); ?> ▼</button>
                    <div class="dropdown-content">
                        <a href="profile.php">View Profile</a>
                        <a href="php/logout.php">Logout</a>
                    </div>
                </div>                    
            <?php else: ?>
                <a href="LoginForm.php">LOGIN</a>
            <?php endif; ?>        
        </div>   
        <div> 
            <form class="search-bar" action="search.php" method="GET">
                <input type="search" name="query" placeholder="Search" required>
                <button type="submit">
                    <img src="images/icons/search.png" width="16px" height="16px" alt="search"/>
                </button>
            </form>
        </div>    
    </div>
</div>
