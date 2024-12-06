document.querySelectorAll('.stars').forEach(starsContainer => {
    const stars = starsContainer.querySelectorAll('.star');
    const ratingText = starsContainer.nextElementSibling;
    const movieId = starsContainer.getAttribute('data-movie');

    let rating = 0;

    stars.forEach(star => {
        star.addEventListener('click', () => {
            rating = star.getAttribute('data-value');
            updateStars();
            updateRatingText();
            sendRating(movieId, rating); // Enviar a avaliação para o servidor
        });

        star.addEventListener('mouseover', () => {
            highlightStars(star.getAttribute('data-value'));
        });

        star.addEventListener('mouseout', () => {
            updateStars(); // Retorna ao estado atual de avaliação
        });
    });

    function highlightStars(value) {
        stars.forEach(star => {
            star.classList.toggle('selected', star.getAttribute('data-value') <= value);
        });
    }

    function updateStars() {
        stars.forEach(star => {
            star.classList.toggle('selected', star.getAttribute('data-value') <= rating);
        });
    }

    function updateRatingText() {
        ratingText.innerText = `Avaliação: ${rating} estrelas`;
    }

    function sendRating(movieId, rating) {
        fetch('save_rating.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ movie_id: movieId, rating: rating }),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Avaliação salva:', data);
        })
        .catch((error) => {
            console.error('Erro:', error);
        });
    }
});
