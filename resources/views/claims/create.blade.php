<!-- resources/views/claims/create.blade.php -->
@extends('layouts.app')

@section('content')
<h2>Create New Claim</h2>
<form id="createClaimForm">
    <div class="mb-3">
        <label for="submission_date" class="form-label">Submission Date</label>
        <input type="date" class="form-control" id="submission_date" required>
    </div>
    <div id="claimDetails">
        <h4>Claim Details</h4>
    </div>
    <button type="button" class="btn btn-secondary mb-3" onclick="addClaimDetail()">Add Claim Detail</button>
    <div id="additionalExpenses">
        <h4>Additional Expenses</h4>
    </div>
    <button type="button" class="btn btn-secondary mb-3" onclick="addAdditionalExpense()">Add Additional Expense</button>
    <button type="submit" class="btn btn-primary">Submit Claim</button>
</form>
@endsection

@push('scripts')
<script>
    let detailCount = 0;
    let expenseCount = 0;

    function addClaimDetail() {
        const detailsContainer = document.getElementById('claimDetails');
        const detailElement = document.createElement('div');
        detailElement.className = 'card mb-3';
        detailElement.innerHTML = `
            <div class="card-body">
                <h5 class="card-title">Detail #${++detailCount}</h5>
                <div class="mb-3">
                    <label class="form-label">Lecture Date</label>
                    <input type="date" class="form-control" name="details[${detailCount}][lecture_date]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Programme</label>
                    <input type="text" class="form-control" name="details[${detailCount}][programme]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Course</label>
                    <input type="text" class="form-control" name="details[${detailCount}][course]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Start Time</label>
                    <input type="time" class="form-control" name="details[${detailCount}][start_time]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">End Time</label>
                    <input type="time" class="form-control" name="details[${detailCount}][end_time]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Duration (hours)</label>
                    <input type="number" class="form-control" name="details[${detailCount}][duration]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Rate</label>
                    <input type="number" step="0.01" class="form-control" name="details[${detailCount}][rate]" required>
                </div>
            </div>
        `;
        detailsContainer.appendChild(detailElement);
    }

    function addAdditionalExpense() {
        const expensesContainer = document.getElementById('additionalExpenses');
        const expenseElement = document.createElement('div');
        expenseElement.className = 'card mb-3';
        expenseElement.innerHTML = `
            <div class="card-body">
                <h5 class="card-title">Expense #${++expenseCount}</h5>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="additional_expenses[${expenseCount}][description]" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input type="number" step="0.01" class="form-control" name="additional_expenses[${expenseCount}][amount]" required>
                </div>
            </div>
        `;
        expensesContainer.appendChild(expenseElement);
    }

    document.getElementById('createClaimForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const claimData = {
            submission_date: formData.get('submission_date'),
            details: [],
            additional_expenses: []
        };

        for (let i = 1; i <= detailCount; i++) {
            claimData.details.push({
                lecture_date: formData.get(`details[${i}][lecture_date]`),
                programme: formData.get(`details[${i}][programme]`),
                course: formData.get(`details[${i}][course]`),
                start_time: formData.get(`details[${i}][start_time]`),
                end_time: formData.get(`details[${i}][end_time]`),
                duration: formData.get(`details[${i}][duration]`),
                rate: formData.get(`details[${i}][rate]`)
            });
        }

        for (let i = 1; i <= expenseCount; i++) {
            claimData.additional_expenses.push({
                description: formData.get(`additional_expenses[${i}][description]`),
                amount: formData.get(`additional_expenses[${i}][amount]`)
            });
        }

        axios.post('/claims', claimData)
            .then(response => {
                alert('Claim submitted successfully');
                window.location.href = '/claims';
            })
            .catch(error => {
                alert('Error submitting claim. Please try again.');
                console.error('Claim submission error:', error);
            });
    });
</script>
@endpush