    <!-- return-modal.php -->
    <div id="openReturnModal" class="hidden fixed inset-0 text-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg max-w-sm w-full">
        <h3 class="text-lg font-medium mb-4">Return Book</h3>
        <form id="returnForm" method="POST" action="user-return.php">
          <input type="hidden" name="transaction_id" id="returnTransactionId">
          <p>Are you sure you want to return this book?</p>
          <div class="mt-4 flex justify-end space-x-2">
            <button type="button" class="closeReturnModal px-4 py-2 bg-gray-300 rounded">Cancel</button>
            <button type="submit" name="return_book" class="px-4 py-2 bg-red-500 text-white rounded">Yes</button>
          </div>
        </form>
      </div>
    </div>