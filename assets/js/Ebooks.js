document.addEventListener('DOMContentLoaded', () => {
    const ebooksData = [
        { id: 101, title: "Advanced Web Development", author: "Shahad Ahmed", category: "science", format: "PDF", size: "4.2 MB", icon: "fa-file-code" },
        { id: 102, title: "Modern Art History", author: "YIC Arts", category: "arts", format: "ePub", size: "2.8 MB", icon: "fa-palette" },
        { id: 103, title: "The Quantum Realm", author: "Dr. Physics", category: "science", format: "PDF", size: "5.5 MB", icon: "fa-atom" },
        { id: 104, title: "Classic Tales", author: "Storyteller", category: "fiction", format: "ePub", size: "1.2 MB", icon: "fa-book" }
    ];

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
                <div class="book-cover"><i class="fas ${book.icon}"></i></div>
                <div class="book-info">
                    <div class="book-title">${book.title}</div>
                    <p class="book-author">By ${book.author}</p>
                    <div class="ebook-specs">
                        <span><i class="fas fa-tag"></i> ${book.category}</span>
                        <span><i class="fas fa-hdd"></i> ${book.size}</span>
                    </div>
                    <button class="download-btn" onclick="handleDownload('${book.title}', '${book.format}')">
                        <i class="fas fa-download"></i> Download ${book.format}
                    </button>
                </div>
            `;
            ebooksGrid.appendChild(card);
        });
    }

    function filterEbooks() {
        const query = searchInput.value.toLowerCase();
        const category = categoryFilter.value.toLowerCase();

        const filtered = ebooksData.filter(book => {
            const matchesSearch = book.title.toLowerCase().includes(query) || book.author.toLowerCase().includes(query);
            const matchesCategory = !category || book.category === category;
            return matchesSearch && matchesCategory;
        });
        displayEbooks(filtered);
    }

    searchInput.addEventListener('input', filterEbooks);
    categoryFilter.addEventListener('change', filterEbooks);
    displayEbooks(ebooksData);
});

function handleDownload(title, format) {
    const toast = document.createElement('div');
    toast.className = 'success-toast';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> Starting ${format} download for "${title}"`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}