// Henter referansen til navbar-elementet ved å velge klassen '.header .flex .navbar'
let navbar = document.querySelector('.header .flex .navbar');

// Legger til en klikk-lytter på menyknappen med ID '#menu-btn'
document.querySelector('#menu-btn').onclick = () => {
   // Toggler 'active'-klassen på navbar-elementet ved hvert klikk på menyknappen
   navbar.classList.toggle('active');
   // Fjerner 'active'-klassen fra searchForm og profile-elementene ved klikk på menyknappen
   searchForm.classList.remove('active');
   profile.classList.remove('active');
}

// Henter referansen til profile-elementet ved å velge klassen '.header .flex .profile'
let profile = document.querySelector('.header .flex .profile');

// Legger til en klikk-lytter på brukerknappen med ID '#user-btn'
document.querySelector('#user-btn').onclick = () => {
   // Toggler 'active'-klassen på profile-elementet ved hvert klikk på brukerknappen
   profile.classList.toggle('active');
   // Fjerner 'active'-klassen fra searchForm og navbar-elementene ved klikk på brukerknappen
   searchForm.classList.remove('active');
   navbar.classList.remove('active');
}

// Henter referansen til searchForm-elementet ved å velge klassen '.header .flex .search-form'
let searchForm = document.querySelector('.header .flex .search-form');

// Legger til en klikk-lytter på søkeknappen med ID '#search-btn'
document.querySelector('#search-btn').onclick = () => {
   // Toggler 'active'-klassen på searchForm-elementet ved hvert klikk på søkeknappen
   searchForm.classList.toggle('active');
   // Fjerner 'active'-klassen fra navbar og profile-elementene ved klikk på søkeknappen
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

// Legger til en rulle-lytter på vinduet
window.onscroll = () => {
   // Fjerner 'active'-klassen fra profile, navbar og searchForm-elementene ved rulling
   profile.classList.remove('active');
   navbar.classList.remove('active');
   searchForm.classList.remove('active');
}

// Itererer gjennom alle elementene med klassen '.content-150'
document.querySelectorAll('.content-150').forEach(content => {
   // Sjekker om innholdet i hvert '.content-150'-element er lengre enn 150 tegn
   if (content.innerHTML.length > 150)
      // Begrenser innholdet til de første 150 tegn hvis det er lengre enn 150 tegn
      content.innerHTML = content.innerHTML.slice(0, 150);
});
