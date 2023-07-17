// Realiza a animação da página ancorar até o elemento  gallery-img-agent
window.onload = function(){
    var gallerySection = document.getElementById("gallery-img-agent");
    if(gallerySection){
        gallerySection.scrollIntoView({
            behavior: "smooth",
            block: "start"
    });
    }
};