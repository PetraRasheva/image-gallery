<div class="modal fade" id="editModal<?= $image['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Image Description</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Form to Update Description -->
            <form method="POST" action="index.php">
                <div class="modal-body">
                    <input type="hidden" name="image_id" value="<?= $image['id'] ?>">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required><?= htmlspecialchars($image['description']) ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-outline-dark">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>