   // =====================================================
    // BOOKS DATA
    // ✅ Fixed: removed broken Unsplash image URLs.
    //           Using FontAwesome icons as covers instead.
    //           (Replace cover field with real image paths
    //            once you have actual book cover images.)
    // =====================================================
    const booksData = [
      { id: 1,  title: "The Great Gatsby",                    author: "F. Scott Fitzgerald",     category: "fiction",   callNumber: "PS3511.I4 G37 1925",        isbn: "978-0743273565", quantity: 5, available: 3 },
      { id: 2,  title: "To Kill a Mockingbird",               author: "Harper Lee",               category: "fiction",   callNumber: "PS3562.E3781 T6 1960",       isbn: "978-0061120084", quantity: 4, available: 1 },
      { id: 3,  title: "A Brief History of Time",             author: "Stephen Hawking",          category: "science",   callNumber: "QB981 H37 1988",              isbn: "978-0553176842", quantity: 3, available: 2 },
      { id: 4,  title: "The Selfish Gene",                    author: "Richard Dawkins",          category: "science",   callNumber: "QH366.2 D38 1976",            isbn: "978-0192860926", quantity: 2, available: 2 },
      { id: 5,  title: "Pride and Prejudice",                 author: "Jane Austen",              category: "fiction",   callNumber: "PR4034.P7 1993",              isbn: "978-0141439518", quantity: 6, available: 2 },
      { id: 6,  title: "The Oxford English Dictionary",       author: "Oxford University Press",  category: "reference", callNumber: "PE1625 O9",                   isbn: "978-0198611868", quantity: 1, available: 1 },
      { id: 7,  title: "Sapiens",                             author: "Yuval Noah Harari",        category: "history",   callNumber: "GN281 H225 2014",             isbn: "978-0062316097", quantity: 5, available: 3 },
      { id: 8,  title: "Steve Jobs",                          author: "Walter Isaacson",          category: "biography", callNumber: "QA76.2 J63 2011",             isbn: "978-1451648537", quantity: 4, available: 0 },
      { id: 9,  title: "The Art of War",                      author: "Sun Tzu",                  category: "history",   callNumber: "U101 S85",                    isbn: "978-1590302256", quantity: 3, available: 2 },
      { id: 10, title: "Clean Code",                          author: "Robert C. Martin",         category: "science",   callNumber: "QA76.9 C6 M37 2008",          isbn: "978-0132350884", quantity: 2, available: 1 },
      { id: 11, title: "The Da Vinci Code",                   author: "Dan Brown",                category: "fiction",   callNumber: "PS3552 R685 D2 2003",         isbn: "978-0307474278", quantity: 5, available: 2 },
      { id: 12, title: "A Brief History of Nearly Everything",author: "Bill Bryson",              category: "science",   callNumber: "QC61 B79 2003",               isbn: "978-0767908185", quantity: 3, available: 3 }
    ];

    // Icon per category
    const categoryIcon = {
      fiction:   'fa-book-open',
      science:   'fa-flask',
      reference: 'fa-atlas',
      biography: 'fa-user-circle',
      history:   'fa-landmark'
    };

    function displayBooks(books) {
      const container   = document.getElementById('booksContainer');
      const noResults   = document.getElementById('noResults');
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
        const availClass = book.available === 0 ? 'unavailable'
                         : book.available <= 2  ? 'low'
                         : '';
        const availText  = book.available === 0 ? 'Unavailable'
                         : book.available <= 2  ? `${book.available} copies left`
                         : `${book.available} copies available`;

        const icon = categoryIcon[book.category] || 'fa-book';

        const card = document.createElement('div');
        card.className = 'book-card';
        card.innerHTML = `
          <div class="book-cover" style="display:flex;align-items:center;justify-content:center;font-size:4rem;color:rgba(255,255,255,0.6);">
            <i class="fas ${icon}"></i>
          </div>
          <div class="book-info">
            <div class="book-title">${book.title}</div>
            <div class="book-author">By: ${book.author}</div>
            <div class="book-call-number">${book.callNumber}</div>
            <span class="availability ${availClass}">${availText}</span>
            <button
              class="borrow-btn"
              ${book.available === 0 ? 'disabled' : ''}
              onclick="borrowBook(${book.id}, '${book.title.replace(/'/g,"\\'")}')">
              ${book.available === 0 ? '❌ Unavailable' : '📖 Borrow'}
            </button>
          </div>
        `;
        container.appendChild(card);
      });
    }

    function borrowBook(bookId, bookTitle) {
      // ✅ Phase 2 note: replace localStorage with a fetch() call to
      //    /backend/borrow_book.php when the backend is ready.
      let borrowed = JSON.parse(localStorage.getItem('borrowedBooks')) || [];

      if (borrowed.find(b => b.id === bookId)) {
        showToast(`You already borrowed "${bookTitle}"`, false);
        return;
      }

      const dueDate = new Date();
      dueDate.setDate(dueDate.getDate() + 14);

      borrowed.push({
        id: bookId,
        title: bookTitle,
        borrowDate: new Date().toLocaleDateString(),
        dueDate: dueDate.toLocaleDateString()
      });

      localStorage.setItem('borrowedBooks', JSON.stringify(borrowed));

      // Update available count in local data
      const book = booksData.find(b => b.id === bookId);
      if (book) book.available = Math.max(0, book.available - 1);

      showToast(`"${bookTitle}" added to your borrowed books!`, true);
      setTimeout(filterBooks, 500);
    }

    function showToast(message, success = true) {
      const toast = document.createElement('div');
      toast.className = 'success-toast';
      toast.style.background = success ? '#28a745' : '#dc3545';
      toast.textContent = (success ? '✅ ' : '⚠️ ') + message;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 3000);
    }

    function filterBooks() {
      const query    = document.getElementById('searchInput').value.toLowerCase();
      const category = document.getElementById('categoryFilter').value.toLowerCase();

      const filtered = booksData.filter(book => {
        const matchSearch   = book.title.toLowerCase().includes(query)
                           || book.author.toLowerCase().includes(query)
                           || book.isbn.includes(query);
        const matchCategory = !category || book.category === category;
        return matchSearch && matchCategory;
      });

      displayBooks(filtered);
    }

    // Scroll-to-top visibility
    window.addEventListener('scroll', () => {
      document.getElementById('scrollTopBtn').classList.toggle('show', window.scrollY > 300);
    });

    // Init
    displayBooks(booksData);