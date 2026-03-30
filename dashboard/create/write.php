   <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

 
 <div class="container my-5">
        <div class="row">
       <div class="write-card">
                <h4 class="write-title">✍️ Write New Blog</h4>

                <form method="POST">

                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul..." required>
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label class="form-label">Konten</label>
                        <textarea name="content" id="editor"></textarea>
                    </div>

                    <!-- Button -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary btn-publish">
                            Publish
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>

<?php

?>