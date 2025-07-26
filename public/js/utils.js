var mockAjax = (function mockAjax() {
    var timeout;
    return function (duration) {
        clearTimeout(timeout); // abort last request
        return new Promise(function (resolve, reject) {
            timeout = setTimeout(resolve, duration || 700, whitelist)
        })
    }
})()


function initTagify(inputElm, options = {}) {
    const settings = {
        ...options,
    }

    const tagify = new Tagify(inputElm, settings);

    document.querySelector('.tags--removeAllBtn')?.addEventListener('click', tagify.removeAllTags.bind(tagify))

    if (options.ajax) {
        tagify.on('input', async (e) => {
            tagify.whitelist = null;
            tagify.loading(true);

            await fetch(`${options.ajax.url}?searchQuery=${e.detail.value}`)
                .then(response => response.json())
                .then(data => {
                    tagify.settings.whitelist = options['processResults'] ? options.processResults(data) : data;

                    console.log(tagify.settings.whitelist)

                    tagify
                        .loading(false)
                        .dropdown.show(e.detail.value);
                })
                .catch(err => tagify.dropdown.hide())
        })
    }

    return tagify;
}
