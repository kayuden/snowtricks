document.addEventListener('turbo:load', function () {

    // Scroll to top arrow
    const scrollToTopButton = document.getElementById('scrollToTop');
    window.addEventListener('scroll', function () {
        if (window.scrollY > 100) {
            scrollToTopButton.style.display = 'block';
        } else {
            scrollToTopButton.style.display = 'none';
        }
    });

    // Load more button
    const loadMoreBtn = document.getElementById('load-more-btn');
    const trickList = document.getElementById('trick-list');

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function () {
            const offset = parseInt(loadMoreBtn.getAttribute('data-offset'));

            loadMoreBtn.disabled = true;
            loadMoreBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...`;

            const url = loadMoreBtn.dataset.url;
            fetch(`${url}?offset=${offset}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // debug
                    trickList.insertAdjacentHTML('beforeend', data.html);
                    loadMoreBtn.setAttribute('data-offset', offset + 10);

                    if (!data.hasMore) {
                        loadMoreBtn.remove();
                    } else {
                        loadMoreBtn.disabled = false;
                        loadMoreBtn.innerText = "Load more";
                    }
                })
                .catch(error => {
                    console.error('Loading error:', error);
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.innerText = "Load more";
                });
        });
    }
});