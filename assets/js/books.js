document.addEventListener('DOMContentLoaded', () => {
    let booksData = []; 

    async function loadBooks() {
        try {
            const response = await fetch('/api/fetch_data.php?type=books');
            booksData = await response.json();
            filterBooks(); 
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }

    const categoryIcon = {
        fiction:   'fa-book-open',
        science:   'fa-flask',
        reference: 'fa-atlas',
        biography: 'fa-user-circle',
        history:   'fa-landmark'
    };

    function displayBooks(books) {
        const container = document.getElementById('booksContainer');
        const noResults = document.getElementById('noResults');
        const resultCount = document.getElementById('resultCount');

        container.innerHTML = '';
        if (books.length === 0) {
            noResults.style.display = 'block';
            resultCount.textContent = 'No books found';
            return;
        }

        noResults.style.display = 'none';
        resultCount.textContent = `Showing ${books.length} book(s)`;

        books.forEach(book => {
            const availClass = book.available == 0 ? 'unavailable' : book.available <= 2 ? 'low' : '';
            const availText  = book.available == 0 ? 'Unavailable' : book.available <= 2 ? `Only ${book.available} left` : 'Available';
            const icon = categoryIcon[book.category.toLowerCase()] || 'fa-book';

            const card = document.createElement('div');
            card.className = 'book-card';
            card.innerHTML = `
                <div class="book-cover"><i class="fas ${icon}"></i></div>
                <div class="book-info">
                    <div class="book-title">${book.title}</div>
                    <p class="book-author">By ${book.author}</p>
                    <div class="book-meta">
                        <span><strong>Category:</strong> ${book.category}</span>
                        <span><strong>Call #:</strong> ${book.call_number}</span>
                    </div>
                    <div class="availability-badge ${availClass}">${availText}</div>
                    <button class="borrow-btn" ${book.available == 0 ? 'disabled' : ''} 
                        onclick="handleBorrow(${book.id}, '${book.title.replace(/'/g, "\\'")}')">
                        <i class="fas fa-hand-holding"></i> Borrow
                    </button>
                </div>`;
            container.appendChild(card);
        });
    }

    window.filterBooks = function() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value.toLowerCase();

        const filtered = booksData.filter(book => {
            const matchSearch = book.title.toLowerCase().includes(query) || 
                                book.author.toLowerCase().includes(query) || 
                                (book.isbn && book.isbn.includes(query));
            const matchCategory = !category || book.category.toLowerCase() === category;
            return matchSearch && matchCategory;
        });
        displayBooks(filtered);
    };

    loadBooks(); 
});