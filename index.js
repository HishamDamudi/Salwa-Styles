$('.navTrigger').click(function () {
  $(this).toggleClass('active');
  console.log("Clicked menu");
  $("#mainListDiv").toggleClass("show_list");
  $("#mainListDiv").fadeIn();

});
//jquery-click-scroll
//by syamsul'isul' Arifin

function scrollToSection(sectionId) {
  var section = document.getElementById(sectionId);
  var sectionPosition = section.offsetTop;
  window.scrollTo({
      top: sectionPosition,
      behavior: 'smooth' 
  });
}

const emailInput = document.getElementById('email-input');
const subscribeForm = document.getElementById('subscribe-form');

// Handle form submission
subscribeForm.addEventListener('submit', function(event) {
event.preventDefault(); // Prevent default form submission

// Check if preventDefault is working
console.log('Form submission prevented!');

// Check if the email field contains "admin@123"
if (emailInput.value.toLowerCase().includes('admin@123')) {
  // Redirect to the admin login page
  console.log('Redirecting to admin login page...');
  window.location.href = 'login.php';
} else {
  alert("Subscribed successfully!");
}
});

document.querySelectorAll('.read-more').forEach(function(button) {
  button.addEventListener('click', function() {
    var shortDesc = this.previousElementSibling.previousElementSibling;
    var fullDesc = this.previousElementSibling;
    
    if (fullDesc.style.display === "none") {
      fullDesc.style.display = "block";
      shortDesc.style.display = "none";
      this.textContent = "Read less";
    } else {
      fullDesc.style.display = "none";
      shortDesc.style.display = "block";
      this.textContent = "Read more";
    }
  });
});

let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}