import * as bootstrap from 'bootstrap';

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

    //init modal
    const modalElement = document.getElementById('trickModal');
    const modal = new bootstrap.Modal(modalElement);
    const editModalElement = document.getElementById('trickEditModal');
    const editModal = new bootstrap.Modal(editModalElement);

    //listen link via event delegation
    trickList.addEventListener('click', async (e) => {
        const openModalLink = e.target.closest('.open-trick-modal');
        const openEditLink = e.target.closest('.open-trick-edit-modal');

        if (openModalLink) {
            e.preventDefault();
            //trick id recover
            const trickId = openModalLink.dataset.id;

            try {
                //AJAX request
                const response = await fetch(`/admin/trick/modal/show/${trickId}`);
                if (!response.ok) throw new Error('Content loading error');
                const html = await response.text();

                //content injection in the modal
                document.getElementById('trickModalBody').innerHTML = html;

                //modal display
                modal.show();
            } catch (error) {
                console.error('AJAX Error (show):', error);
            }
        }

        if (openEditLink) {
            e.preventDefault();
            const trickId = openEditLink.dataset.id;

            try {
                const response = await fetch(`/admin/trick/modal/edit/${trickId}`);
                if (!response.ok) throw new Error('Form loading error');
                const html = await response.text();

                //content injection in the modal
                document.getElementById('trickEditModalBody').innerHTML = html;

                //modal display
                editModal.show();
            } catch (error) {
                console.error('AJAX Error (edit) :', error);
            }
        }
    });
});
