@if($resources->total() > 10)
    <div class="d-flex justify-content-start py-1">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item {{ $resources->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ isset($_GET['doctors_page']) ? $resources->previousPageUrl() . '&doctors_page=requests&requestStatus=' . \App\Constants\DoctorRequestStatusConstants::PENDING->value : $resources->previousPageUrl() }}" aria-label="Previous">
                        <span class="{{ $resources->onFirstPage() ? '' : 'text-primary' }}" aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item">
                    <span class="page-link">{{ $resources->currentPage() }} - {{ $resources->lastPage() }}</span>
                </li>
                <li class="page-item {{ $resources->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ isset($_GET['doctors_page']) ? $resources->nextPageUrl() . '&doctors_page=requests&requestStatus=' . \App\Constants\DoctorRequestStatusConstants::PENDING->value : $resources->nextPageUrl() }}" aria-label="Next">
                        <span class="{{ $resources->hasMorePages() ? 'text-primary' : '' }}" aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endif
