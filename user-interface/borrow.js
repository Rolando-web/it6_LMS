    // For Filter
    function submitForm() {
      const category = document.querySelector('.filter-btn.active').dataset.category;
      const search = document.getElementById('searchInput').value;
      const sort = document.getElementById('sortSelect').value;

      document.getElementById('categoryInput').value = category;
      document.getElementById('searchInput').value = search;
      document.getElementById('sortSelect').value = sort;

      document.getElementById('filterForm').submit();
    }

    // LOADMORE SECTION
    document.addEventListener("DOMContentLoaded", () => {
      const books = document.querySelectorAll("#booksGrid > div"); // Select direct children of #booksGrid
      const totalBooks = books.length;
      const loadMoreBtn = document.getElementById("loadMoreBtn");
      let visibleCount = 8; // Show 6 books initially

      // Hide all books beyond the first 8
      books.forEach((book, index) => {
        if (index >= visibleCount) {
          book.style.display = "none"; // Hide extra books
        }
      });

      // Load more books on button click
      loadMoreBtn.addEventListener("click", () => {
        const nextBatch = Array.from(books).slice(visibleCount, visibleCount + 8);
        nextBatch.forEach((book) => (book.style.display = "")); // Show hidden books
        visibleCount += 8;

        // Hide the "Load More" button if all books are shown
        if (visibleCount >= totalBooks) {
          loadMoreBtn.style.display = "none";
        }
      });
    });
    // LOADMORE SECTION

    // Function to update return date based on selected duration
    function updateReturnDate() {
      const duration = parseInt(document.getElementById("borrowDuration").value);
      const borrowDate = new Date(); // Use current date
      const returnDate = new Date(borrowDate);
      returnDate.setDate(borrowDate.getDate() + duration);
      document.getElementById("returnDate").value = returnDate.toISOString().split("T")[0];
    }

    // Open Borrow Modal
    document.querySelectorAll(".openBorrowModal").forEach((btn) => {
      btn.addEventListener("click", () => {
        const bookId = btn.dataset.bookId;
        const title = btn.dataset.bookTitle;
        const author = btn.dataset.bookAuthor;

        document.getElementById("borrowBookTitle").textContent = title;
        document.getElementById("borrowBookAuthor").textContent = author;
        document.getElementById("confirmBorrow").dataset.bookId = bookId;

        document.getElementById("borrowModal").classList.remove("hidden");

        //  Set initial return date
        updateReturnDate();


        // Update return date when duration changes
        document.getElementById("borrowDuration").addEventListener("change", () => {
          updateReturnDate();
        });


      });
    });


    document.querySelectorAll(".openBorrowModal").forEach((btn) => {
      btn.addEventListener("click", () => {
        const bookId = btn.dataset.bookId;
        const title = btn.dataset.bookTitle;
        const author = btn.dataset.bookAuthor;

        document.getElementById("borrowBookTitle").textContent = title;
        document.getElementById("borrowBookAuthor").textContent = author;

        // store bookId in confirm button
        document.getElementById("confirmBorrow").dataset.bookId = bookId;

        document.getElementById("borrowModal").classList.remove("hidden");

        // Close Success Modal
        if (closeSuccess) {
          closeSuccess.addEventListener("click", () => {
            successModal.classList.add("hidden");
          });
        }
        // Extra: close modal if you click outside of it
        [borrowModal, successModal].forEach((modal) => {
          if (modal) {
            modal.addEventListener("click", (e) => {
              if (e.target === modal) {
                modal.classList.add("hidden");
              }
            });
          }
        });

        // Cancel Borrow → Close Borrow Modal
        if (cancelBorrow) {
          cancelBorrow.addEventListener("click", () => {
            borrowModal.classList.add("hidden");
          });
        }
      });
    });
