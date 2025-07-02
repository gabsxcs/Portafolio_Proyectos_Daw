const menuBtn = document.querySelector('.menu-btn');
const menu = document.querySelector('nav.menu');

menuBtn.addEventListener('click', () => {
    menu.classList.toggle('active'); 
});

//Cambio a modo oscuro
const botonOscuro = document.querySelector('.icon-contrast');

botonOscuro.addEventListener('click', function() {

if (document.documentElement.classList.contains('light')) {
    document.documentElement.classList.replace('light', 'dark');
} else {
    document.documentElement.classList.replace('dark', 'light');
}

});