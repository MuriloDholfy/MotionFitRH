<nav class="navbar">
    <div class="nav-logo ml-2">
        <a href="../Home/index.php" class="" aria-current="page" title="Home">
            <img src="../../img/icons/navbar/logo.png" alt="Logo"></img>
        </a>
    </div>
    <div class="search">
    <input type="text" class="search__input" id="searchInput" placeholder="Procurar">
        <button class="search__button"ID="searchButton">
            <svg class="search__icon" aria-hidden="true" viewBox="0 0 24 24">
                <g>
                    <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z">
                    </path>
                </g>
            </svg>
        </button>
        <div class="nav-profile ml-2">
            <label class="popup">
                <input type="checkbox" />
                <div tabindex="0" class="burger mt-2">
                    <svg viewBox="0 0 24 24" fill="white" height="20" width="20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2c2.757 0 5 2.243 5 5.001 0 2.756-2.243 5-5 5s-5-2.244-5-5c0-2.758 2.243-5.001 5-5.001zm0-2c-3.866 0-7 3.134-7 7.001 0 3.865 3.134 7 7 7s7-3.135 7-7c0-3.867-3.134-7.001-7-7.001zm6.369 13.353c-.497.498-1.057.931-1.658 1.302 2.872 1.874 4.378 5.083 4.972 7.346h-19.387c.572-2.29 2.058-5.503 4.973-7.358-.603-.374-1.162-.811-1.658-1.312-4.258 3.072-5.611 8.506-5.611 10.669h24c0-2.142-1.44-7.557-5.631-10.647z">
                        </path>
                    </svg>
                </div>
                <nav class="popup-window">
                    <legend>Perfil</legend>
                    <ul>
                        <li>
                            <a href="../Perfil/index.php">
                            <button>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2c2.757 0 5 2.243 5 5.001 0 2.756-2.243 5-5 5s-5-2.244-5-5c0-2.758 2.243-5.001 5-5.001zm0-2c-3.866 0-7 3.134-7 7.001 0 3.865 3.134 7 7 7s7-3.135 7-7c0-3.867-3.134-7.001-7-7.001zm6.369 13.353c-.497.498-1.057.931-1.658 1.302 2.872 1.874 4.378 5.083 4.972 7.346h-19.387c.572-2.29 2.058-5.503 4.973-7.358-.603-.374-1.162-.811-1.658-1.312-4.258 3.072-5.611 8.506-5.611 10.669h24c0-2.142-1.44-7.557-5.631-10.647z">
                                        </path>
                                    </svg>
                                    <span>Perfil</span>
                                </button>
                            </a>
                            </li>
                            <li>
                            <a href="../../model/logout.php">
                            <button>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.598 9h-1.055c1.482-4.638 5.83-8 10.957-8 6.347 0 11.5 5.153 11.5 11.5s-5.153 11.5-11.5 11.5c-5.127 0-9.475-3.362-10.957-8h1.055c1.443 4.076 5.334 7 9.902 7 5.795 0 10.5-4.705 10.5-10.5s-4.705-10.5-10.5-10.5c-4.568 0-8.459 2.923-9.902 7zm12.228 3l-4.604-3.747.666-.753 6.112 5-6.101 5-.679-.737 4.608-3.763h-14.828v-1h14.826z">
                                    </path>
                                </svg>
                                <span>Sair</span>
                            </button>
                            </a>
                        </li>
                    </ul>
                </nav>
            </label>
        </div>
    </div>
</nav>
<script>
    document.getElementById('searchButton').addEventListener('click', function() {
        navigateBasedOnSearch();
    });

    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            navigateBasedOnSearch();
        }
    });

    function navigateBasedOnSearch() {
        var query = document.getElementById('searchInput').value.toLowerCase();
        let url;

        switch (query) {
            case 'home':
                url = '../Home/index.php';
                break;
            case 'vaga':
                url = '../Projetos/vagas.php';
                break;
            case 'vagas':
                url = '../Projetos/vagas.php';
                break;
            case 'dash':
                url = '../Dash/index.php';
                break;
            case 'dashboard':
                url = '../Dash/index.php';
                break;
            case 'RH':
                url = '../Work/index.php';
                break;
            case 'gerente Regional':
                url = '../Equipe/index.php';
                break;
            case 'equipe':
                url = '../Equipe/index.php';
                break;
            default:
                alert('Página não encontrada. Tente novamente.');
                return;
        }

        // Redireciona para a URL correspondente
        window.location.href = url;
    }
</script>

