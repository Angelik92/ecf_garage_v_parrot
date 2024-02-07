
window.onload = function () {
    // Get all elements
    const ratingElements = document.querySelectorAll('.js-rating-client');

    //// Loop through each element
    ratingElements.forEach(ratingElement => {
        // Get the value of the 'data-rating' attribute
        const ratingValue  = ratingElement.getAttribute('data-rating');
        const starIcons = ratingElement.querySelectorAll('.js-star-show-rating');

        starIcons.forEach((star, index) => {
            if (index >= ratingValue ) {
                star.classList.remove('bi-star-fill');
                star.classList.add('bi-star');
            } else {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill');
            }
        });
    });
};