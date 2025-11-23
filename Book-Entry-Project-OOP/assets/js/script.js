document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById('bookForm');

  // Rating star click
  const stars = document.querySelectorAll('#ratingStars .star');
  let ratingValue = 0;
  stars.forEach(star => {
    star.addEventListener('click', function() {
      ratingValue = this.dataset.value;
      stars.forEach(s => s.innerHTML = '&#9734;'); // reset
      for(let i=0;i<ratingValue;i++) stars[i].innerHTML = '&#9733;';
    });
  });

  // Validate all fields and collect errors
    const validationResults = [];

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    if(!validateBookName()){
      return false;
    }
    if(!validateAuthor()){
      return false;
    }
    if(!validatePublisher()){
      return false;
    }
    if(!valiateCategory()){
      return false;
    }
    if(!validateAvailableAs()){
      return false;
    }
    if(!price()){
      return false;
    }
    if(!validateReview()){
      return false;
    }
    if(!validateImage()){
      return false;
    }
    if(!validatePreview()){
      return false;
    }
  });
})