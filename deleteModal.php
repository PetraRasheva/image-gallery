<div class="modal fade" id="deleteModal<?= $image['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this image?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="index.php">
                    <input type="hidden" name="id" value="<?= $image['id'] ?>"> 
                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>