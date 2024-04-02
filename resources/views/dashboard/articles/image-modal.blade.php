<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">{{$article->title}}'s Images</h5>
                <button type="button" class="btn btn-flat-light close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" alt="" id="modalImage" class="img-fluid rounded g-modal-image w-auto" >
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="prevButton">&laquo;</button>
                <button type="button" class="btn btn-secondary" id="nextButton">&raquo;</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            const images = {!! json_encode($article->images->pluck('asset_url')) !!};
            let currentIndex = null;
            $('.gallery-image').click(function () {
                const imageUrl = $(this).attr('src');
                $('#modalImage').attr('src', imageUrl);
                $('#imageModal').modal('show');
                currentIndex = $(this).data('index');
            });
            function showImage(index) {
                $('#modalImage').attr('src', images[index]);
            }
            $('#prevButton').click(function () {
                currentIndex = currentIndex - 1;
                if(currentIndex < 0) {
                    currentIndex = 0;
                }
                showImage(currentIndex);
            });
            $('#nextButton').click(function () {
                currentIndex = currentIndex + 1;
                if(currentIndex >= images.length) {
                    currentIndex = images.length - 1;
                }
                showImage(currentIndex);
            });
            if (currentIndex !== null) {
                showImage(currentIndex);
            }
        });
    </script>
@endpush
