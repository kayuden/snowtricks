import * as bootstrap from 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    //init modal
    const modalElement = document.getElementById('trickModal');
    const modal = new bootstrap.Modal(modalElement);

    //listen link
    document.querySelectorAll('.open-trick-modal').forEach(link => {
        //click management
        link.addEventListener('click', async (e) => {
            e.preventDefault();
            //trick id recover
            const trickId = e.currentTarget.dataset.id;

            try {
                //AJAX request
                const response = await fetch(`/admin/trick/modal/${trickId}`);
                if (!response.ok) throw new Error('Erreur de chargement du contenu');
                const html = await response.text();

                //content injection in the modal
                document.getElementById('trickModalBody').innerHTML = html;

                //modal title based on h1
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const title = doc.querySelector('h1')?.textContent;
                if (title) {
                    document.getElementById('trickModalLabel').textContent = title;
                }

                //modal display
                modal.show();
            } catch (error) {
                console.error('Erreur AJAX :', error);
            }
        });
    });
});

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