function createFiler(element) {
    // Check if the element is not null
    if (element === null) {
        return;
    }

    // Select elements within the DOM
    const filter = element.querySelector(".js-filter");
    const content = element.querySelector(".js-filter-content");
    const form = element.querySelector(".js-filter-form");
    const pagination = element.querySelector('.js-filter-pagination');
    function bindEvents() {
        // Add event listeners for various input types
        form.querySelectorAll('input[type=checkbox], input[type=number], input[type=text]').forEach(input => {
            input.addEventListener('change',  loadForm.bind(this))
        });
    }

    async function loadForm() {
        // Collect form data
        const data = new FormData(form);
        const url = new URL(form.getAttribute('action') || window.location.href);
        const params = new URLSearchParams();

        data.forEach((value, key) => {
            params.append(key, value);
        });

        // Load data via AJAX request
        return loadUrl(url.pathname + "?" + params.toString());
    }

    async function loadUrl(url) {
        // Construct the AJAX URL
        const ajaxUrl = url + '&ajax=1';
        const response = await fetch(ajaxUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (response.status >= 200 && response.status < 300) {
            const data = await response.json();
            // Update the content and URL
            content.innerHTML = data.content;
            history.replaceState({}, "", url);
        } else {
            console.error(response);
        }
    }
    bindEvents();

    return {
        loadForm,
        loadUrl
    };
}

// Select the filter element and create the filter
const filterElement = document.querySelector('.js-filter');
const filer = createFiler(filterElement)