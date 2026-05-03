
    // =====================================================
    // ✅ Phase 2 note: localStorage is used here as a
    //    temporary frontend-only store. When the PHP backend
    //    (borrow_book.php / return_book.php) is ready,
    //    replace localStorage calls with fetch() requests.
    // =====================================================

    function isOverdue(dueDateStr) {
      return new Date(dueDateStr) < new Date();
    }

    function daysUntilDue(dueDateStr) {
      const today = new Date(); today.setHours(0,0,0,0);
      const due   = new Date(dueDateStr); due.setHours(0,0,0,0);
      return Math.ceil((due - today) / 86400000);
    }

    function displayBorrowedBooks() {
      const borrowed    = JSON.parse(localStorage.getItem('borrowedBooks')) || [];
      const list        = document.getElementById('borrowedList');
      const noBorrowed  = document.getElementById('noBorrowed');
      const borrowCount = document.getElementById('borrowCount');

      borrowCount.textContent = `You have ${borrowed.length} borrowed book${borrowed.length !== 1 ? 's' : ''}`;

      if (borrowed.length === 0) {
        list.innerHTML = '';
        noBorrowed.style.display = 'block';
        return;
      }

      noBorrowed.style.display = 'none';
      list.innerHTML = '';

      borrowed.forEach((book, index) => {
        const daysLeft = daysUntilDue(book.dueDate);
        const overdue  = isOverdue(book.dueDate);
        const dueSoon  = daysLeft <= 3 && daysLeft > 0;

        const item = document.createElement('div');
        item.className = 'borrowed-item';
        item.innerHTML = `
          <div class="borrowed-info">
            <div class="borrowed-title"><i class="fas fa-book"></i> ${book.title}</div>
            <div class="borrowed-detail">
              <span class="detail-label">Borrowed:</span> ${book.borrowDate}
            </div>
            <div class="borrowed-detail">
              <span class="detail-label">Due Date:</span>
              <span class="${overdue ? 'overdue' : dueSoon ? 'due-soon' : ''}">
                ${book.dueDate}
                ${overdue  ? ' ⚠️ OVERDUE!'
                : dueSoon  ? ` (${daysLeft} day${daysLeft !== 1 ? 's' : ''} left)`
                : ''}
              </span>
            </div>
          </div>
          <div class="borrowed-actions">
            <button class="return-btn" onclick="returnBook(${index})">
              <i class="fas fa-undo"></i> Return
            </button>
            <button class="renew-btn" onclick="renewBook(${index})">
              <i class="fas fa-sync"></i> Renew (14 days)
            </button>
          </div>
        `;
        list.appendChild(item);
      });
    }

    function returnBook(index) {
      let borrowed = JSON.parse(localStorage.getItem('borrowedBooks')) || [];
      const book   = borrowed[index];
      borrowed.splice(index, 1);
      localStorage.setItem('borrowedBooks', JSON.stringify(borrowed));
      showToast(`"${book.title}" returned successfully!`);
      setTimeout(displayBorrowedBooks, 400);
    }

    function renewBook(index) {
      let borrowed = JSON.parse(localStorage.getItem('borrowedBooks')) || [];
      const book   = borrowed[index];
      const newDue = new Date(book.dueDate);
      newDue.setDate(newDue.getDate() + 14);
      borrowed[index].dueDate = newDue.toLocaleDateString();
      localStorage.setItem('borrowedBooks', JSON.stringify(borrowed));
      showToast(`"${book.title}" renewed until ${newDue.toLocaleDateString()}`);
      setTimeout(displayBorrowedBooks, 400);
    }

    function showToast(message) {
      const toast = document.createElement('div');
      toast.className = 'success-toast';
      toast.textContent = '✅ ' + message;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 3000);
    }

    // Scroll-to-top visibility
    window.addEventListener('scroll', () => {
      document.getElementById('scrollTopBtn').classList.toggle('show', window.scrollY > 300);
    });

    // Init
    displayBorrowedBooks();