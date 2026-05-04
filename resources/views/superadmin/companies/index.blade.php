<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark mb-0">
            {{ __('Companies') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="row">
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('companies.create') }}" class="btn btn-primary">Create Company</a>
            </div>
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4 py-3 text-uppercase small fw-bold text-muted">Company Name</th>
                                        <th scope="col" class="py-3 text-uppercase small fw-bold text-muted">Users</th>
                                        <th scope="col" class="pe-4 py-3 text-uppercase small fw-bold text-muted text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companies as $company)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <span class="fw-bold text-dark">{{ $company->name }}</span>
                                            </td>
                                            <td class="py-3">
                                                <span class="badge rounded-pill bg-primary-soft text-primary px-3">
                                                    {{ $company->users_count }} Members
                                                </span>
                                            </td>
                                            <td class="pe-4 py-3 text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('companies.show', $company) }}" class="btn btn-sm btn-outline-primary">
                                                        View
                                                    </a>
                                                    <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-outline-secondary">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('companies.destroy', $company) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this company?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>