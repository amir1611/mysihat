import 'bootstrap';

document.addEventListener('click', (event) => {
    if (event.target.closest('[data-sidebar-toggle]')) {
        document.body.classList.toggle('sidebar-open');
    }
});
