<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark mb-0">
            {{ __('Short URLs') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="row">
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('member'))
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('short-urls.create') }}" class="btn btn-primary">Generate Short URL</a>
            </div>
            @endif
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3 text-uppercase small fw-bold text-muted">Original URL</th>
                                        <th scope="col" class="py-3 text-uppercase small fw-bold text-muted">Short URL</th>
                                        <th scope="col" class="py-3 text-uppercase small fw-bold text-muted">Hits</th>
                                        <th scope="col" class="py-3 text-uppercase small fw-bold text-muted">Owner</th>
                                        <th scope="col" class="py-3 text-uppercase small fw-bold text-muted">Company</th>
                                        <th scope="col" class="py-3 text-uppercase small fw-bold text-muted">Created</th>
                                        <th scope="col" class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($shortUrls as $shortUrl)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <span class="fw-bold text-dark">
                                                    {{ $shortUrl->url ?? '—' }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <a class="text-decoration-none" href="{{ route('short-urls.redirect', $shortUrl->short_url) }}" target="_blank" rel="noopener noreferrer">
                                                    {{ $shortUrl->short_url ? url('/s/' . $shortUrl->short_url) : '—' }}
                                                </a>
                                            </td>
                                            <td class="py-3">
                                                <span class="badge rounded-pill bg-primary-soft text-primary px-3">
                                                    {{ $shortUrl->hits }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <span class="badge rounded-pill bg-primary-soft text-primary px-3">
                                                    {{ $shortUrl->user->name ?? '—' }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <span class="badge rounded-pill bg-primary-soft text-primary px-3">
                                                    {{ $shortUrl->company->name ?? '—' }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-muted small">
                                                {{ $shortUrl->created_at?->format('M j, Y g:i A') ?? '—' }}
                                            </td>
                                            <td class="pe-4 py-3 text-end">
                                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('member'))
                                                    <form action="{{ route('short-urls.destroy', $shortUrl) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this short URL?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="ps-4 py-4 text-muted" colspan="7">
                                                No short URLs found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

