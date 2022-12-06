<header>
        <nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-light bg-white border-bottom box-shadow mb-3">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img class="img-fluid" src="images/logo.png" alt="BSA" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse collapse d-sm-inline-flex flex-sm-row-reverse">
                <ul class="navbar-nav">
    <li class="nav-item">
        <a  class="nav-link text-dark">Hola <?php echo "{$username}"; ?>!</a>
    </li>

        <li class="nav-item">
            <a class="nav-link text-dark" href="admin.php">Usuarios</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-dark" href="precios.php">Precios</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-dark" href="salir.php">Salir</a>
        </li>
    
</ul>
                    <form class="form-inline mt-2 mt-md-0">
                        <input class="form-control mr-sm-2 tt-query" autocomplete="off" spellcheck="false" type="text" placeholder="Buscar" aria-label="Search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                        <span><i class="fas fa-check"></i></span>
                    </form>
                </div>
            </div>
        </nav>
    </header>
