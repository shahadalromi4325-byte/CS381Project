document.addEventListener('DOMContentLoaded', () => {
    let ebooksData = []; // Data will be loaded here

    async function loadEbooks() {
        try {
            const response = await fetch('/api/fetch_data.php?type=ebooks');
            ebooksData = await response.json();
            filterEbooks();
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }

    const ebooksGrid = document.getElementById('ebooksGrid');
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');

    function displayEbooks(books) {
        ebooksGrid.innerHTML = '';
        if (books.length === 0) {
            ebooksGrid.innerHTML = '<p class="no-results">No digital books found.</p>';
            return;
        }

        books.forEach(book => {
            const card = document.createElement('div');
            card.className = 'book-card';
            card.innerHTML = `
                <div class="book-cover"><i class="fas ${book.icon || 'fa-file-pdf'}"></i></div>
                <div class="book-info">
                    <div class="book-title">${book.title}</div>
                    <p class="book-author">By ${book.author}</p>
                    <div class="ebook-specs">
                        <span><i class="fas fa-tag"></i> ${book.category}</span>
                        <span><i class="fas fa-hdd"></i> ${book.size}</span>
                    </div>
                    <button class="download-btn" onclick="handleDownload('${book.title.replace(/'/g, "\\'")}', '${book.format}')">
                        <i class="fas fa-download"></i> Download ${book.format}
                    </button>
                </div>`;
            ebooksGrid.appendChild(card);
        });
    }

    function filterEbooks() {
        const query = searchInput.value.toLowerCase();
        const category = categoryFilter.value.toLowerCase();

        const filtered = ebooksData.filter(book => {
            const matchesSearch = book.title.toLowerCase().includes(query) || book.author.toLowerCase().includes(query);
            const matchesCategory = !category || book.category.toLowerCase() === category;
            return matchesSearch && matchesCategory;
        });
        displayEbooks(filtered);
    }

    searchInput.addEventListener('input', filterEbooks);
    categoryFilter.addEventListener('change', filterEbooks);
    
    loadEbooks();
});