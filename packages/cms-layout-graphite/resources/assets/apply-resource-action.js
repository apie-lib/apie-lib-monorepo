(function (resourceActionListElm) {
    for (const button of resourceActionListElm.querySelectorAll("*[data-url]")) {
        button.addEventListener('click', function () {
            const dataUrl = button.dataset.url;
            const dataSmallPage = button.dataset.smallPage;
            //console.log(button, dataUrl, dataSmallPage);
            location.href = dataUrl;
        }); 
    }
    resourceActionListElm.classList.remove('resource-actions-unprocessed');
}(document.querySelector('.resource-actions-unprocessed')));