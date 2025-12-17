@extends('layouts.app')

@section('title', 'View Submissions')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-database me-2"></i>Form Submissions</h2>
            <div>
                <button type="button" class="btn btn-primary-custom me-2" onclick="fetchSubmissions()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh Data
                </button>
                <button type="button" class="btn btn-success-custom" onclick="showStatistics()">
                    <i class="fas fa-chart-bar me-2"></i>View Statistics
                </button>
            </div>
        </div>
        
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Data is fetched from the backend API at: <strong>{{ env('BACKEND_API_URL', 'http://127.0.0.1:5000/api') }}</strong>
        </div>
        
        <!-- Statistics Modal -->
        <div class="modal fade" id="statisticsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-chart-bar me-2"></i>Submission Statistics</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="statisticsContent" class="row">
                            <!-- Statistics will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Search by Name or Email</label>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search..." onkeyup="filterSubmissions()">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter by Country</label>
                        <select id="countryFilter" class="form-select" onchange="filterSubmissions()">
                            <option value="">All Countries</option>
                            <option value="USA">USA</option>
                            <option value="UK">UK</option>
                            <option value="Canada">Canada</option>
                            <option value="Australia">Australia</option>
                            <option value="India">India</option>
                            <option value="Germany">Germany</option>
                            <option value="Japan">Japan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter by Gender</label>
                        <select id="genderFilter" class="form-select" onchange="filterSubmissions()">
                            <option value="">All Genders</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sort By</label>
                        <select id="sortFilter" class="form-select" onchange="filterSubmissions()">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="name_asc">Name A-Z</option>
                            <option value="name_desc">Name Z-A</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Loading Spinner -->
        <div id="loading" class="text-center my-5" style="display: none;">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Fetching data from backend API...</p>
        </div>
        
        <!-- Error Message -->
        <div id="errorMessage" class="alert alert-danger" style="display: none;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <span id="errorText"></span>
            <div class="mt-2">
                <small>Make sure:</small>
                <ul class="mb-0">
                    <li>Backend API is running on port 5000</li>
                    <li>CORS is properly configured in backend</li>
                    <li>API endpoint is accessible</li>
                </ul>
            </div>
        </div>
        
        <!-- Submissions Count -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 id="submissionCount">Loading submissions...</h5>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary" onclick="viewAll()">All</button>
                <button type="button" class="btn btn-outline-primary" onclick="viewWithImages()">With Images</button>
                <button type="button" class="btn btn-outline-primary" onclick="viewToday()">Today</button>
            </div>
        </div>
        
        <!-- Submissions List -->
        <div id="submissionsList" class="row">
            <!-- Submissions will be loaded here -->
        </div>
        
        <!-- No Data Message -->
        <div id="noData" class="text-center my-5" style="display: none;">
            <div class="empty-state">
                <i class="fas fa-database fa-5x text-muted mb-4"></i>
                <h4>No Submissions Found</h4>
                <p class="text-muted mb-4">No form submissions have been made yet or there's an issue fetching data.</p>
                <button class="btn btn-primary-custom" onclick="fetchSubmissions()">
                    <i class="fas fa-sync-alt me-2"></i>Try Again
                </button>
            </div>
        </div>
        
        <!-- Pagination -->
        <nav id="pagination" class="mt-4" style="display: none;">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled" id="prevPage">
                    <a class="page-link" href="#" onclick="changePage(-1)">Previous</a>
                </li>
                <li class="page-item">
                    <span class="page-link" id="pageInfo">Page 1 of 1</span>
                </li>
                <li class="page-item" id="nextPage">
                    <a class="page-link" href="#" onclick="changePage(1)">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Submission Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .empty-state {
        padding: 40px 0;
    }
    
    .submission-card {
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
        height: 100%;
    }
    
    .submission-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }
    
    .card-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        color: white;
    }
    
    .badge-interest {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        margin: 2px;
    }
    
    .stat-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        margin-bottom: 15px;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .image-preview {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
</style>
@endpush

@push('scripts')
<script>
    // Helper functions
    function showElement(id, show) {
        const element = document.getElementById(id);
        if (element) {
            element.style.display = show ? 'block' : 'none';
        }
    }
    
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    let allSubmissions = [];
    let filteredSubmissions = [];
    let currentPage = 1;
    const itemsPerPage = 6;
    
    // Fetch submissions on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetchSubmissions();
    });
    
    // Fetch submissions from API
    async function fetchSubmissions() {
        // Show loading, hide others
        showElement('loading', true);
        showElement('errorMessage', false);
        showElement('noData', false);
        showElement('submissionsList', false);
        showElement('pagination', false);
        
        document.getElementById('submissionsList').innerHTML = '';
        document.getElementById('submissionCount').textContent = 'Connecting to backend (port 5000)...';
        
        try {
            console.log('Fetching data from:', API_BASE_URL + '/form/submissions');
            
            // First check if backend is reachable
            const backendCheck = await fetch(API_BASE_URL.replace('/api', ''), {
                method: 'GET',
                mode: 'cors'
            }).catch(() => null);
            
            if (!backendCheck || !backendCheck.ok) {
                throw new Error(`Backend not reachable at ${API_BASE_URL.replace('/api', '')}. Make sure it's running on port 5000.`);
            }
            
            // Now fetch submissions
            const response = await fetch(API_BASE_URL + '/form/submissions', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                mode: 'cors'
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (data.success) {
                allSubmissions = data.data;
                filteredSubmissions = [...allSubmissions];
                
                if (allSubmissions.length > 0) {
                    showElement('loading', false);
                    updateSubmissionCount();
                    displaySubmissions();
                    showElement('pagination', true);
                    updatePagination();
                    showToast(`Successfully loaded ${allSubmissions.length} submissions from port 5000`, 'success');
                } else {
                    showElement('loading', false);
                    showElement('noData', true);
                    document.getElementById('submissionCount').textContent = 'No submissions found';
                }
            } else {
                throw new Error(data.message || 'API returned error');
            }
        } catch (error) {
            console.error('Fetch error:', error);
            showElement('loading', false);
            showElement('errorMessage', true);
            document.getElementById('errorText').innerHTML = `
                <strong>Connection Error to Backend (Port 5000):</strong><br>
                ${error.message}<br><br>
                
                <small>Troubleshooting:</small>
                <ol class="small">
                    <li>Make sure backend is running: <code>php artisan serve --port=5000</code></li>
                    <li>Test backend directly: <a href="http://127.0.0.1:5000" target="_blank">http://127.0.0.1:5000</a></li>
                    <li>Test API directly: <a href="http://127.0.0.1:5000/api/form/submissions" target="_blank">http://127.0.0.1:5000/api/form/submissions</a></li>
                    <li>Check if port 5000 is not blocked by firewall</li>
                </ol>
                
                <div class="mt-3">
                    <button class="btn btn-sm btn-primary me-2" onclick="fetchSubmissions()">
                        <i class="fas fa-sync-alt me-1"></i>Retry Connection
                    </button>
                    <button class="btn btn-sm btn-info" onclick="window.open('http://127.0.0.1:5000/api/form/submissions', '_blank')">
                        <i class="fas fa-external-link-alt me-1"></i>Test Backend API
                    </button>
                </div>
            `;
        }
    }
    
    // Display submissions
    function displaySubmissions() {
        const container = document.getElementById('submissionsList');
        container.innerHTML = '';
        
        if (filteredSubmissions.length === 0) {
            showElement('noData', true);
            return;
        }
        
        // Calculate start and end index for pagination
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const currentSubmissions = filteredSubmissions.slice(startIndex, endIndex);
        
        currentSubmissions.forEach(submission => {
            const col = document.createElement('div');
            col.className = 'col-md-6 col-lg-4 mb-4';
            
            const interests = submission.interests || [];
            const interestsHTML = interests.map(interest => 
                `<span class="badge badge-interest">${interest}</span>`
            ).join('');
            
            const hasImage = submission.image_url || submission.image_path;
            const imageHTML = hasImage ? 
                `<div class="text-center mb-3">
                    <i class="fas fa-image text-primary"></i> Image Attached
                </div>` : '';
            
            col.innerHTML = `
                <div class="card submission-card h-100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 text-truncate" title="${submission.name}">
                                <i class="fas fa-user me-2"></i>${submission.name}
                            </h6>
                            <span class="badge bg-light text-dark">
                                ${formatDate(submission.created_at).split(',')[0]}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            <i class="fas fa-envelope me-2 text-muted"></i>
                            <strong>Email:</strong> 
                            <span class="text-truncate d-inline-block" style="max-width: 150px;" title="${submission.email}">
                                ${submission.email}
                            </span>
                        </p>
                        <p class="card-text">
                            <i class="fas fa-globe me-2 text-muted"></i>
                            <strong>Country:</strong> ${submission.country}
                        </p>
                        <p class="card-text">
                            <i class="fas fa-venus-mars me-2 text-muted"></i>
                            <strong>Gender:</strong> 
                            <span class="badge ${submission.gender === 'male' ? 'bg-primary' : submission.gender === 'female' ? 'bg-danger' : 'bg-secondary'}">
                                ${submission.gender}
                            </span>
                        </p>
                        ${imageHTML}
                        ${interests.length > 0 ? `
                            <p class="card-text mb-1">
                                <i class="fas fa-heart me-2 text-muted"></i>
                                <strong>Interests:</strong>
                            </p>
                            <div class="mb-2">${interestsHTML}</div>
                        ` : ''}
                        ${submission.message ? `
                            <p class="card-text">
                                <i class="fas fa-comment me-2 text-muted"></i>
                                <strong>Message:</strong>
                                <small class="d-block text-truncate" style="max-width: 100%;" title="${submission.message}">
                                    ${submission.message.substring(0, 50)}${submission.message.length > 50 ? '...' : ''}
                                </small>
                            </p>
                        ` : ''}
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-primary flex-fill" onclick="viewSubmission(${submission.id})">
                                <i class="fas fa-eye me-1"></i> View
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteSubmission(${submission.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(col);
        });
        
        showElement('submissionsList', true);
    }
    
    // Update submission count
    function updateSubmissionCount() {
        const count = filteredSubmissions.length;
        document.getElementById('submissionCount').textContent = 
            `${count} submission${count !== 1 ? 's' : ''} found`;
    }
    
    // Filter submissions
    function filterSubmissions() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const countryFilter = document.getElementById('countryFilter').value;
        const genderFilter = document.getElementById('genderFilter').value;
        const sortBy = document.getElementById('sortFilter').value;
        
        filteredSubmissions = allSubmissions.filter(submission => {
            // Search filter
            const matchesSearch = searchTerm === '' || 
                submission.name.toLowerCase().includes(searchTerm) ||
                submission.email.toLowerCase().includes(searchTerm) ||
                (submission.phone && submission.phone.includes(searchTerm));
            
            // Country filter
            const matchesCountry = countryFilter === '' || submission.country === countryFilter;
            
            // Gender filter
            const matchesGender = genderFilter === '' || submission.gender === genderFilter;
            
            return matchesSearch && matchesCountry && matchesGender;
        });
        
        // Sort submissions
        filteredSubmissions.sort((a, b) => {
            switch(sortBy) {
                case 'newest':
                    return new Date(b.created_at) - new Date(a.created_at);
                case 'oldest':
                    return new Date(a.created_at) - new Date(b.created_at);
                case 'name_asc':
                    return a.name.localeCompare(b.name);
                case 'name_desc':
                    return b.name.localeCompare(a.name);
                default:
                    return 0;
            }
        });
        
        currentPage = 1;
        updateSubmissionCount();
        displaySubmissions();
        updatePagination();
    }
    
    // Pagination functions
    function updatePagination() {
        const totalPages = Math.ceil(filteredSubmissions.length / itemsPerPage);
        
        document.getElementById('prevPage').classList.toggle('disabled', currentPage === 1);
        document.getElementById('nextPage').classList.toggle('disabled', currentPage === totalPages || totalPages === 0);
        document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages || 1}`;
        
        showElement('pagination', totalPages > 1);
    }
    
    function changePage(direction) {
        const totalPages = Math.ceil(filteredSubmissions.length / itemsPerPage);
        const newPage = currentPage + direction;
        
        if (newPage >= 1 && newPage <= totalPages) {
            currentPage = newPage;
            displaySubmissions();
            updatePagination();
            
            // Scroll to top of submissions
            document.getElementById('submissionsList').scrollIntoView({ behavior: 'smooth' });
        }
    }
    
    // View submission details
    async function viewSubmission(id) {
        try {
            const response = await fetch(API_BASE_URL + '/form/submission/' + id);
            const data = await response.json();
            
            if (response.ok && data.success) {
                showSubmissionModal(data.data);
            } else {
                throw new Error(data.message || 'Failed to fetch submission details');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(`Error: ${error.message}`, 'error');
        }
    }
    
    // Show submission in modal
    function showSubmissionModal(submission) {
        const interests = submission.interests || [];
        const interestsHTML = interests.map(interest => 
            `<span class="badge badge-interest">${interest}</span>`
        ).join('');
        
        const imageHTML = submission.image_url ? `
            <div class="text-center mb-3">
                <img src="${submission.image_url}" class="image-preview" alt="Submission Image">
                <small class="text-muted">Uploaded Image</small>
            </div>
        ` : submission.image_path ? `
            <div class="alert alert-info">
                <i class="fas fa-image me-2"></i>Image was uploaded but URL is not available
            </div>
        ` : '';
        
        const modalBody = document.getElementById('viewModalBody');
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-user me-2"></i>Personal Information</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Name:</strong></td>
                            <td>${submission.name}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>${submission.email}</td>
                        </tr>
                        <tr>
                            <td><strong>Phone:</strong></td>
                            <td>${submission.phone || 'N/A'}</td>
                        </tr>
                        <tr>
                            <td><strong>Country:</strong></td>
                            <td>${submission.country}</td>
                        </tr>
                        <tr>
                            <td><strong>Gender:</strong></td>
                            <td>
                                <span class="badge ${submission.gender === 'male' ? 'bg-primary' : submission.gender === 'female' ? 'bg-danger' : 'bg-secondary'}">
                                    ${submission.gender}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-info-circle me-2"></i>Additional Information</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Submitted:</strong></td>
                            <td>${formatDate(submission.created_at)}</td>
                        </tr>
                        <tr>
                            <td><strong>Last Updated:</strong></td>
                            <td>${formatDate(submission.updated_at)}</td>
                        </tr>
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td><code>${submission.id}</code></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            ${imageHTML}
            
            ${interests.length > 0 ? `
                <div class="mb-3">
                    <h6><i class="fas fa-heart me-2"></i>Interests</h6>
                    <div class="d-flex flex-wrap">${interestsHTML}</div>
                </div>
            ` : ''}
            
            ${submission.message ? `
                <div class="mb-3">
                    <h6><i class="fas fa-comment me-2"></i>Message</h6>
                    <div class="card">
                        <div class="card-body">
                            ${submission.message}
                        </div>
                    </div>
                </div>
            ` : ''}
            
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                Terms and conditions were accepted by the user.
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('viewModal'));
        modal.show();
    }
    
    // Delete submission
    async function deleteSubmission(id) {
        if (!confirm('Are you sure you want to delete this submission? This action cannot be undone.')) {
            return;
        }
        
        try {
            const response = await fetch(API_BASE_URL + '/form/submission/' + id, {
                method: 'DELETE'
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                showToast('Submission deleted successfully', 'success');
                // Remove from local arrays
                allSubmissions = allSubmissions.filter(s => s.id !== id);
                filteredSubmissions = filteredSubmissions.filter(s => s.id !== id);
                // Refresh display
                updateSubmissionCount();
                displaySubmissions();
                updatePagination();
            } else {
                throw new Error(data.message || 'Failed to delete submission');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast(`Error: ${error.message}`, 'error');
        }
    }
    
    // View filters
    function viewAll() {
        document.getElementById('searchInput').value = '';
        document.getElementById('countryFilter').value = '';
        document.getElementById('genderFilter').value = '';
        filterSubmissions();
    }
    
    function viewWithImages() {
        filteredSubmissions = allSubmissions.filter(s => s.image_url || s.image_path);
        currentPage = 1;
        updateSubmissionCount();
        displaySubmissions();
        updatePagination();
    }
    
    function viewToday() {
        const today = new Date().toDateString();
        filteredSubmissions = allSubmissions.filter(s => 
            new Date(s.created_at).toDateString() === today
        );
        currentPage = 1;
        updateSubmissionCount();
        displaySubmissions();
        updatePagination();
    }
    
    // Show statistics
    function showStatistics() {
        if (allSubmissions.length === 0) {
            showToast('No data available for statistics', 'warning');
            return;
        }
        
        const stats = calculateStatistics();
        const statsHTML = `
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">${allSubmissions.length}</div>
                    <div class="stat-label">Total Submissions</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">${stats.withImages}</div>
                    <div class="stat-label">With Images</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">${stats.todayCount}</div>
                    <div class="stat-label">Today's Submissions</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-value">${stats.countriesCount}</div>
                    <div class="stat-label">Countries</div>
                </div>
            </div>
            <div class="col-12">
                <h6 class="mt-3">Gender Distribution</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Gender</th>
                                <th>Count</th>
                                <th>Percentage</th>
                                <th>Bar</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${Object.entries(stats.genderDistribution).map(([gender, count]) => `
                                <tr>
                                    <td>${gender.charAt(0).toUpperCase() + gender.slice(1)}</td>
                                    <td>${count}</td>
                                    <td>${((count / allSubmissions.length) * 100).toFixed(1)}%</td>
                                    <td>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: ${(count / allSubmissions.length) * 100}%">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12">
                <h6>Country Distribution</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Country</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${Object.entries(stats.countryDistribution).map(([country, count]) => `
                                <tr>
                                    <td>${country}</td>
                                    <td>${count}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
        
        document.getElementById('statisticsContent').innerHTML = statsHTML;
        const modal = new bootstrap.Modal(document.getElementById('statisticsModal'));
        modal.show();
    }
    
    // Calculate statistics
    function calculateStatistics() {
        const today = new Date().toDateString();
        const todayCount = allSubmissions.filter(s => 
            new Date(s.created_at).toDateString() === today
        ).length;
        
        const withImages = allSubmissions.filter(s => s.image_url || s.image_path).length;
        
        const genderDistribution = {};
        const countryDistribution = {};
        
        allSubmissions.forEach(submission => {
            // Count genders
            genderDistribution[submission.gender] = (genderDistribution[submission.gender] || 0) + 1;
            
            // Count countries
            countryDistribution[submission.country] = (countryDistribution[submission.country] || 0) + 1;
        });
        
        return {
            todayCount,
            withImages,
            genderDistribution,
            countryDistribution,
            countriesCount: Object.keys(countryDistribution).length
        };
    }
</script>
@endpush