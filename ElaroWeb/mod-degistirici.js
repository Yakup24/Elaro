function geceModunaGec() {
    document.body.classList.add('gece-modu');
    localStorage.setItem('mod', 'gece');
}

function gunduzModunaGec() {
    document.body.classList.remove('gece-modu');
    localStorage.setItem('mod', 'gunduz');
}

// Sayfa yüklendiğinde kullanıcının tercihini kontrol et
window.onload = function() {
    if (localStorage.getItem('mod') === 'gece') {
        geceModunaGec();
    }
};
