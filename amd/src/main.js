define([], function () {

    /**
     * Inicializa la barra de control de cursos
     */
    function init() {

        const items = document.querySelectorAll('.mycoursesux-item');
        const searchInput = document.getElementById('mycoursesux-search-input');
        const sortSelect = document.getElementById('mycoursesux-sort-select');
        const filterButtons = document.querySelectorAll('[data-filter]');
        const layoutButtons = document.querySelectorAll('[data-layout]');
        const container = document.getElementById('mycoursesux-container');

        let currentFilter = 'all';

        /**
         * Aplica filtros de búsqueda y estado
         */
        function applyFilters() {

            const search = searchInput.value.toLowerCase();

            items.forEach(item => {

                const name = item.dataset.name.toLowerCase();
                const status = item.dataset.status;

                let visible = true;

                if (currentFilter !== 'all' && !status.includes(currentFilter)) {
                    visible = false;
                }

                if (!name.includes(search)) {
                    visible = false;
                }

                item.style.display = visible ? '' : 'none';
            });
        }

        /**
         * Ordena los cursos según criterio seleccionado
         */
        function applySort() {

            const value = sortSelect.value;
            const sorted = Array.from(items);

            sorted.sort((a, b) => {

                if (value === 'name') {
                    return a.dataset.name.localeCompare(b.dataset.name);
                }

                if (value === 'progress') {
                    return b.dataset.progress - a.dataset.progress;
                }

                return 0;
            });

            sorted.forEach(el => container.appendChild(el));
        }

        // FILTROS
        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {

                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                currentFilter = btn.dataset.filter;
                applyFilters();
            });
        });

        // SEARCH
        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        // SORT
        if (sortSelect) {
            sortSelect.addEventListener('change', applySort);
        }

        // LAYOUT SWITCH
        layoutButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const layout = btn.dataset.layout;
                window.location.search = '?layout=' + layout;
            });
        });

    }

    return {
        init: init
    };
});