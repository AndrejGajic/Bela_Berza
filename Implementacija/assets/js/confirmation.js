//funkcije koje postavljaju modale koji od administratora zahtevaju da potvrdi odluku o potvrdi ili odbacivanju registracije

function setConfirmModal(username, url){
    let btn = document.getElementById('confirmBtn');
    let text = document.getElementById('confirmText');

    btn.onclick=function(){
        window.location=url;
    }
    text.innerHTML='Da li ste sigurni da zelite da potvrdite registraciju korisnika '+username+'?';
}

function setRejectModal(username, url){
    let btn = document.getElementById('rejectBtn');
    let text = document.getElementById('rejectText');

    btn.onclick=function(){
        window.location=url;
    }
    text.innerHTML='Da li ste sigurni da zelite da odbijete registraciju korisnika '+username+'?';
}