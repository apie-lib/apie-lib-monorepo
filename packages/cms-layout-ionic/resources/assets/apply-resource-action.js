(function (resourceActionListElm) {
    const stringToHTML = function (str) {
        let dom = document.createElement('div');
        dom.innerHTML = str;
        return dom;
    
    };

    for (const button of resourceActionListElm.querySelectorAll("*[data-url]")) {
        button.addEventListener('click', async function () {
            const dataUrl = button.dataset.url;
            const dataSmallPage = button.dataset.smallPage;
            if (dataSmallPage === 'true') {
                try {
                    const ajaxUrl = new URL(dataUrl, window.location.href);
                    ajaxUrl.searchParams.set('layout', 'sidebar');
                    const response = await fetch(ajaxUrl.toString(), {headers: {'X-Requested-With': 'XMLHttpRequest'}, credentials: 'same-origin'});
                    if (response.ok) {
                        const container = document.getElementById('rightPanelContent');
                        container.innerHTML = await response.text();
                        container.querySelectorAll('apie-ionic-form').forEach((form) => form.setAttribute('action', dataUrl));
                        container.querySelectorAll('script').forEach((oldScript) => {
                            const script = document.createElement('script');
                            Array.from(oldScript.attributes).forEach((attr) => script.setAttribute(attr.name, attr.value));
                            script.textContent = oldScript.textContent;
                            oldScript.replaceWith(script);
                        });
                        await document.getElementById('rightPanel').open();
                        return;
                    }
                } catch (err) {
                }
            }
            location.href = dataUrl;
        }); 
    }
    resourceActionListElm.classList.remove('resource-actions-unprocessed');
}(document.querySelector('.resource-actions-unprocessed')));