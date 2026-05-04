<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark mb-0">
            {{ __('Company') }} - {{ $company->name }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="row">
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('companies.index') }}" class="btn btn-secondary">Back</a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="card-title">Company Details</h5>
                                <p class="card-text">Name: {{ $company->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>