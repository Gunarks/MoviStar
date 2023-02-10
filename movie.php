<?php
require_once("templates/header.php");

// Verifica se o usuário está autenticado
require_once("dao/MovieDAO.php");
require_once("models/Movie.php");

// Pegar o id do filme
$id = filter_input(INPUT_GET, "id");

// $movie;s

$movieDao = new MovieDAO($conn, $BASE_URL);

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

// Resgatar as reviews dos filmes

$alreadyReviewed = false;

?>

<div class="container-fluid" id="main-container">
    <div class="row">
        <div class="offset-md-1 col-md-6 movie-container">
            <h2 class="page-title"><?= $movie->title ?></h2>
            <p class="movie-details">
                <span>Duração: <?= $movie->length ?></span>
                <span class="pipe"></span>
                <span><?= $movie->category ?></span>
                <span class="pipe"></span>
                <span><i class="fas fa-star"></i> 8</span>
            </p>
            <iframe src="<?= $movie->trailer ?>" frameborder="0" width="560" height="315" allow="accelerometer; autoplay; clipboard-write; encryted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <p><?= $movie->description ?></p>
        </div>
        <div class="col-md-4">
            <div class="movie-image-container" style="background-image: url( <?= $BASE_URL ?>/img/movies/<?= $movie->image ?>)"></div>
        </div>
        <div class="offset-md-1 col-md-10" id="reviews-container">
            <h3 id="reviews-title">Avaliações:</h3>
            <!-- Verifica se habilita a review para o usuário ou não -->
            <?php if (!empty($userData) && !$userOwnsMovie && !$alreadyReviewed) : ?>
                <div class="col-md-12" id="review-form-container">
                    <h4>Envie a sua avaliação</h4>
                    <p class="page-description">Preencha o formulário com a nota e o comentário sobre o filme</p>
                    <form action="<?= $BASE_URL ?>review_process.php" method="POST" id="review-form">
                        <input type="hidden" name="type" value="create">
                        <input type="hidden" name="movies_id" value="<?= $movie->id ?>">
                        <div class="form-group mb-3">
                            <label for="rating" class="mb-2">Nota do filme</label>
                            <select name="rating" id="rating" class="form-control">
                                <option value="10">10</option>
                                <option value="9">9</option>
                                <option value="8">8</option>
                                <option value="7">7</option>
                                <option value="6">6</option>
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                                <option value="0">0</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="reivew" class="mb-2">Seu comentário:</label>
                            <textarea name="review" id="review" rows="3" class="form-control" placeholder="O que você achou do filme?"></textarea>
                        </div>
                        <input type="submit" class="btn card-btn" value="Enviar comentário">
                    </form>
                </div>
            <?php endif; ?>
            <!-- Comentários -->
            <div class="col-md-12 review">
                <div class="row">
                    <div class="col-md-1">
                        <div class="profile-image-container review-image" style="background-image: url(<?= $BASE_URL ?>img/users/user.png);"></div>
                    </div>
                    <div class="col-md-9 author-details-container">
                        <h4 class="author-name">
                            <a href="#">Milkice teste</a>
                        </h4>
                        <p><i class="fas fa-star"></i> 9</p>
                    </div>
                    <div class="col-md-12">
                        <p class="comment-title">Comentários:</p>
                        <p>Este é comentário do usuário</p>
                    </div>
                </div>
            </div>

            <div class="col-md-12 review">
                <div class="row">
                    <div class="col-md-1">
                        <div class="profile-image-container review-image" style="background-image: url(<?= $BASE_URL ?>img/users/user.png);"></div>
                    </div>
                    <div class="col-md-9 author-details-container">
                        <h4 class="author-name">
                            <a href="#">Milkice teste</a>
                        </h4>
                        <p><i class="fas fa-star"></i> 9</p>
                    </div>
                    <div class="col-md-12">
                        <p class="comment-title">Comentários:</p>
                        <p>Este é comentário do usuário</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once("templates/footer.php"); ?>