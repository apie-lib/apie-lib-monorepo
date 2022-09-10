function setInnerHtml(elm, html) {
    elm.innerHTML = html || '';
    Array.from(elm.querySelectorAll("script")).forEach( oldScript => {
        const newScript = document.createElement("script");
        Array.from(oldScript.attributes)
            .forEach( attr => newScript.setAttribute(attr.name, attr.value) );
        newScript.appendChild(document.createTextNode(oldScript.innerHTML));
        oldScript.parentNode.replaceChild(newScript, oldScript);
    });
}

const provideId = (function () {
    let counter = 1;
    return function () {
        return 'form-component-' + (counter++);
    };
}());
