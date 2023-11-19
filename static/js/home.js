

document.addEventListener("DOMContentLoaded", () => {

    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.remove("paused");
        }
      });
    }, { threshold: 0.5 }); // Adjust the threshold as needed
  
    const observeElements = (elements) => {
        elements.forEach(el => {
          observer.observe(el);
        });
      };
    
    const fadeLeft = document.querySelectorAll('.fade-left');
    const fadeRight = document.querySelectorAll('.fade-right');
    const fadeUp = document.querySelectorAll('.fade-top');
    const fadeDown = document.querySelectorAll('.fade-bottom');

    observeElements(fadeLeft);
    observeElements(fadeRight);
    observeElements(fadeUp);
    observeElements(fadeDown);;
  });
  