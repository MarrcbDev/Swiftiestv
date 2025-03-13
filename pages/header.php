<header>
        <h2><?php echo htmlspecialchars($username) . $adminTag; ?></h2>
        <select id="themeSelector" style="padding: 5px; outline: none; background-color:rgba(255, 255, 255, 0.16); border: none; text-align: center;">
            <option value="default.css" disabled selected>Tema</option>
            <option value="team_debut.css">Debut</option>
            <option value="team_fearless.css">Fearless</option>
            <option value="team_speaknow.css">Speak Now</option>
            <option value="team_red.css">Red</option>
            <option value="team_1989.css">1989</option>
            <option value="team_reputation.css">Reputation</option>
            <option value="team_lover.css">Lover</option>
            <option value="team_folklore.css">Folklore</option>
            <option value="team_evermore.css">Evermore</option>
            <option value="team_midnights.css">Midnights</option>
            <option value="team_ttpd.css">TTPD</option>
        </select>



        <a href="logout.php">Cerrar Sesión</a>
    </header>

    <nav> 
        <a  href="./dashboard.php">Puntos</a>
        <a  href="./ranking.php">Ranking</a>
    </nav>