// Henter referansen til header-elementet ved å velge klassen '.header'
let header = document.querySelector('.header');

// Legger til en klikk-lytter på menyknappen med ID '#menu-btn'
document.querySelector('#menu-btn').onclick = () => {
   // Toggler 'active'-klassen på header-elementet ved hvert klikk på menyknappen
   header.classList.toggle('active');
}

// Legger til en rulle-lytter på vinduet
window.onscroll = () => {
   // Fjerner 'active'-klassen fra header-elementet ved rulling
   header.classList.remove('active');
}

// Itererer gjennom alle elementene med klassen '.posts-content'
document.querySelectorAll('.posts-content').forEach(content => {
   // Sjekker om innholdet i hvert '.posts-content'-element er lengre enn 100 tegn
   if (content.innerHTML.length > 100)
      // Begrenser innholdet til de første 100 tegn hvis det er lengre enn 100 tegn
      content.innerHTML = content.innerHTML.slice(0, 100);
});
