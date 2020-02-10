document.addEventListener('click',function(e) {
    if(e.target && e.target.getAttribute('href') === '#') {
        e.preventDefault();
    }
});
