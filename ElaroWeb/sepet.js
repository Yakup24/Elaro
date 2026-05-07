
document.addEventListener('DOMContentLoaded', () => {
  // === SLIDER ===
  let currentIndex = 0;
  const slides = document.querySelectorAll('.anaslider-slide');
  const sliderWrapper = document.querySelector('.anaslider-wrapper');
  const totalSlides = slides.length;

  function showSlide(index) {
    if (sliderWrapper) {
      sliderWrapper.style.transform = `translateX(-${index * 100}%)`;
    }
  }

  function nextSlide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    showSlide(currentIndex);
  }

  if (slides.length > 0) {
    setInterval(nextSlide, 4000);
  }

  // === SEPET ===
  const sepeteEkleButonlari = document.querySelectorAll('.sepete-ekle');

  sepeteEkleButonlari.forEach(btn => {
    btn.addEventListener('click', () => {
      const ad = btn.getAttribute('data-isim');
      const fiyat = btn.getAttribute('data-fiyat');
      const resim = btn.getAttribute('data-resim');

      fetch('sepete-ekle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `ad=${encodeURIComponent(ad)}&fiyat=${encodeURIComponent(fiyat)}&resim=${encodeURIComponent(resim)}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert(ad + " sepete eklendi!");
        }
      });
    });
  });
});
