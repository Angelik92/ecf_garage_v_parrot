// This event listener ensures the code runs when the DOM is fully loaded.
document.addEventListener('DOMContentLoaded', () => {
    // Get all star elements
    const stars = document.querySelectorAll('.js-star-rating');
    // Get the rating input element
    const rating = document.querySelector('#client_testimonials_rating');
    // Define the color to be used for filled stars
    const colorRed = "#8D0102";

    // Loop through each star element.
    for (star of stars) {
        // Event handler for when the mouse is over a star.
        star.addEventListener("mouseover", function (){
            resetStars();
            this.style.color= colorRed;
            this.classList.remove('bi-star');
            this.classList.add('bi-star-fill');

            //Retrieve the previous element in the DOM, representing the star that comes before the one currently being hovered over
            let previousStar = this.previousElementSibling;

            // Loop through previous stars and change their appearance.
            while(previousStar){
                previousStar.style.color= colorRed;
                previousStar.classList.remove('bi-star');
                previousStar.classList.add('bi-star-fill');
                previousStar = previousStar.previousElementSibling;
            }
        })

        // Event handler for when a star is clicked.
        star.addEventListener("click", function(){
            rating.value = this.dataset.value;
        })

        // Event handler for when the mouse leaves a star.
        star.addEventListener("mouseout", function(){
            resetStars(rating.value)
        })

    }
    // Function to reset the appearance of stars based on the selected rating.
    function resetStars(nb=0){
        for (star of stars) {
            if(star.dataset.value > nb){
                star.classList.remove('bi-star-fill');
                star.classList.add('bi-star');
            } else {
                star.style.color = colorRed;
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill');
            }
        }
    }
})