<!-- resources/views/claims/index.blade.php -->
@extends('layouts.app')

@section('content')
<h2>My Claims</h2>
<a href="/claims/create" class="btn btn-primary mb-3">Create New Claim</a>
<div id="claimsList"></div>
@endsection

@push('scripts')
<script>
    function fetchClaims() {
        axios.get('/claims')
            .then(response => {
                const claims = response.data;
                const claimsList = document.getElementById('claimsList');
                claimsList.innerHTML = '';

                claims.forEach(claim => {
                    const claimElement = document.createElement('div');
                    claimElement.className = 'card mb-3';
                    claimElement.innerHTML = `
                        <div class="card-body">
                            <h5 class="card-title">Claim #${claim.id}</h5>
                            <p class="card-text">Submission Date: ${claim.submission_date}</p>
                            <p class="card-text">Total Amount: $${claim.total_amount}</p>
                            <p class="card-text">Status: ${claim.status}</p>
                            <a href="/claims/${claim.id}" class="btn btn-info">View Details</a>
                        </div>
                    `;
                    claimsList.appendChild(claimElement);
                });
            })
            .catch(error => {
                console.error('Error fetching claims:', error);
            });
    }

    fetchClaims();
</script>
@endpush