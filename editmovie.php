<?php
require_once("templates/header.php");

//Verifica se o usuário está autenticado
require_once("dao/UserDAO.php");
require_once("models/User.php");
require_once("dao/MovieDAO.php");

$user = new User();

$userDao = new UserDAO($conn, $BASE_URL);

$userData =  $userDao->verifyToken(true);

$movieDao = new MovieDAO($conn, $BASE_URL);

$id = filter_input(INPUT_GET, "id");

if (empty($id)) {

    $message->setMessage("O filme não foi encontrado", "error", "index.php");
} else {

    $movie = $movieDao->findById($id);

    // Verifica se o filme existe
    if (!$movie) {
        $message->setMessage("O filme não foi encontrado", "error", "index.php");
    }
}

// Checar se o filme tem image 

if (empty($movie->image)) {
    $movie->image = "movie_cover.jpg";
}


// Checar se o filme é do usuário

$userOwnsMovie = false;

if (!empty($userData)) {
    if ($userData->id === $movie->users_id) {
        $userOwnsMovie = true;
    }
}

?>

<div id="main-container" class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 offset-md-1">
                <h1><?= $movie->title ?></h1>
                <p class="page-description">Altere os dados do filme no formulário abaixo:</p>
                <form id="edit-movie-form" action="<?= $BASE_URL ?>movie_process.php" method="POST" enctype="multipart/form">
                    <input type="hidden" name="type" value="update">
                    <input type="hidden" name="type" value="<?= $movie->id ?>">
                    <div class="form-group">
                        <label for="title">Título:</label>
                        <input type="text" class="form-control my-2" id="title" name="title" value="<?= $movie->title ?>">
                    </div>

                    <div class="form-group">
                        <label for="image">Imagem:</label>
                        <input type="file" class="form-control my-2" id="image" name="image">
                    </div>

                    <div class="form-group">
                        <label for="length">Duração:</label>
                        <input type="text" class="form-control my-2" id="length" name="length" value="<?= $movie->length ?>">
                    </div>

                    <div class="form-group">
                        <label for="category">Categoria:</label>
                        <select name="category" id="category" class="form-control my-2">
                            <option value="">Selecione</option>
                            <option value="Ação" <?= $movie->category === "Ação" ? "selected" : " " ?>>Ação</option>
                            <option value="Drama" <?= $movie->category === "Drama" ? "selected" : " " ?>>Drama</option>
                            <option value="Romance" <?= $movie->category === "Romance" ? "selected" : " " ?>>Romance</option>
                            <option value="Comédia" <?= $movie->category === "Comédia" ? "selected" : " " ?>>Comédia</option>
                            <option value="Aventura" <?= $movie->category === "Aventura" ? "selected" : " " ?>>Aventura</option>
                            <option value="Mistério" <?= $movie->category === "Mistério" ? "selected" : " " ?>>Mistério</option>
                            <option value="Psicológico" <?= $movie->category === "Psicológico" ? "selected" : " " ?>>Psicológico</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="trailer">Trailer:</label>
                        <input type="text" class="form-control my-2" id="trailer" name="trailer" placeholder="Insira o link do trailer" value="<?= $movie->trailer ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição:</label>
                        <textarea name="description" id="description" cols="48" rows="5" placeholder="Descreva o filme..." class="my-2"><?= $movie->description ?></textarea>
                    </div>
                    <input type="submit" class="btn card-btn" value="Editar filme">
                </form>
            </div>

            <div class="col-md-3">
                <div>
                    <div class="movie-image-container" style="background-image: url(<?= $BASE_URL ?>/img/movies/<?= $movie->image ?>);"></div>
                    <iframe src="<?= $movie->trailer ?>" frameborder="0" width="560" height="315" allow="accelerometer; autoplay; clipboard-write; encryted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once("templates/footer.php");
?>