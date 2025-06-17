<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birth Records Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
        }
        
        .status-pending {
            color: var(--warning-color);
            font-weight: bold;
        }
        
        .status-approved {
            color: var(--success-color);
            font-weight: bold;
        }
        
        .status-rejected {
            color: var(--danger-color);
            font-weight: bold;
        }
        
        .btn-view {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-accept {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-reject {
            background-color: var(--danger-color);
            color: white;
        }
        
        .search-container {
            margin-bottom: 20px;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .pagination .page-link {
            color: var(--primary-color);
        }
        
        .badge-filter {
            cursor: pointer;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        .hospital-logo {
            height: 40px;
            width: auto;
        }
        
        .record-detail-item {
            margin-bottom: 15px;
        }
        
        .record-detail-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 3px;
        }
        
        .record-detail-value {
            padding-left: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><i class="fas fa-baby me-2"></i> Birth Records Management</h1>
                </div>
                <div class="col-md-6 text-end">
                    <img src="https://via.placeholder.com/150x40?text=Hospital+Logo" alt="Hospital Logo" class="hospital-logo">
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list me-2"></i> Birth Records List</span>
                    <div>
                        <a href="birthrecord.html">
                            <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                                <i class="fas fa-plus me-1"></i> Add Record
                            </button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="search-container">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search records...">
                                <button class="btn btn-primary" id="searchBtn">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="filter-container">
                            <span class="me-2">Filter by status:</span>
                            <span class="badge bg-primary badge-filter" data-status="all">All</span>
                            <span class="badge bg-warning badge-filter" data-status="pending">Pending</span>
                            <span class="badge bg-success badge-filter" data-status="approved">Approved</span>
                            <span class="badge bg-danger badge-filter" data-status="rejected">Rejected</span>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Record ID</th>
                                <th>Full Name</th>
                                <th>Date of Birth</th>
                                <th>Father's Name</th>
                                <th>Mother's Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="recordsTable">
                            <!-- Records will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center" id="pagination">
                        <!-- Pagination will be populated by JavaScript -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- View Record Modal -->
    <div class="modal fade" id="viewRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Birth Record Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="recordDetails">
                    <!-- Details will be populated by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmMessage">
                    <!-- Message will be populated by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmAction">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sample data - in a real app, this would come from an API
        const birthRecords = [
            {
                id: 'BR-2023-001',
                fullName: 'John Doe',
                dob: '2023-06-15',
                gender: 'Male',
                weight: '3.2 kg',
                fatherName: 'Michael Doe',
                fatherId: 'ID-123456',
                motherName: 'Jane Doe',
                motherId: 'ID-654321',
                placeOfBirth: 'City General Hospital',
                attendant: 'Dr. Sarah Johnson',
                status: 'pending',
                contact: '+1234567890',
                address: '123 Main St, Cityville',
                notes: 'Normal delivery, healthy baby'
            },
            {
                id: 'BR-2023-002',
                fullName: 'Emma Smith',
                dob: '2023-06-18',
                gender: 'Female',
                weight: '2.9 kg',
                fatherName: 'David Smith',
                fatherId: 'ID-234567',
                motherName: 'Emily Smith',
                motherId: 'ID-765432',
                placeOfBirth: 'City General Hospital',
                attendant: 'Dr. Robert Chen',
                status: 'approved',
                contact: '+2345678901',
                address: '456 Oak Ave, Townsville',
                notes: 'Caesarean section, mother and baby doing well'
            },
            {
                id: 'BR-2023-003',
                fullName: 'Liam Johnson',
                dob: '2023-06-22',
                gender: 'Male',
                weight: '3.5 kg',
                fatherName: 'Paul Johnson',
                fatherId: 'ID-345678',
                motherName: 'Lisa Johnson',
                motherId: 'ID-876543',
                placeOfBirth: 'City General Hospital',
                attendant: 'Nurse Maria Garcia',
                status: 'rejected',
                contact: '+3456789012',
                address: '789 Pine Rd, Villageton',
                notes: 'Missing father signature on documents'
            },
            {
                id: 'BR-2023-004',
                fullName: 'Olivia Wilson',
                dob: '2023-06-25',
                gender: 'Female',
                weight: '3.1 kg',
                fatherName: 'James Wilson',
                fatherId: 'ID-456789',
                motherName: 'Sophia Wilson',
                motherId: 'ID-987654',
                placeOfBirth: 'City General Hospital',
                attendant: 'Dr. Alan Park',
                status: 'pending',
                contact: '+4567890123',
                address: '321 Elm Blvd, Hamlet City',
                notes: 'Twins - second birth record to follow'
            },
            {
                id: 'BR-2023-005',
                fullName: 'Noah Brown',
                dob: '2023-06-28',
                gender: 'Male',
                weight: '3.4 kg',
                fatherName: 'William Brown',
                fatherId: 'ID-567890',
                motherName: 'Ava Brown',
                motherId: 'ID-098765',
                placeOfBirth: 'City General Hospital',
                attendant: 'Dr. Sarah Johnson',
                status: 'approved',
                contact: '+5678901234',
                address: '654 Cedar Ln, Borough Town',
                notes: 'Normal delivery, no complications'
            }
        ];

        // DOM elements
        const recordsTable = document.getElementById('recordsTable');
        const pagination = document.getElementById('pagination');
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const filterBadges = document.querySelectorAll('.badge-filter');
        const viewRecordModal = new bootstrap.Modal(document.getElementById('viewRecordModal'));
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        const confirmMessage = document.getElementById('confirmMessage');
        const confirmAction = document.getElementById('confirmAction');
        
        // Pagination variables
        const recordsPerPage = 3;
        let currentPage = 1;
        let filteredRecords = [...birthRecords];
        
        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            renderTable();
            renderPagination();
            
            // Event listeners
            searchBtn.addEventListener('click', handleSearch);
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') handleSearch();
            });
            
            filterBadges.forEach(badge => {
                badge.addEventListener('click', function() {
                    filterRecords(this.dataset.status);
                });
            });
            
            confirmAction.addEventListener('click', handleConfirmAction);
        });
        
        // Render the table with records
        function renderTable() {
            recordsTable.innerHTML = '';
            
            const startIndex = (currentPage - 1) * recordsPerPage;
            const endIndex = startIndex + recordsPerPage;
            const paginatedRecords = filteredRecords.slice(startIndex, endIndex);
            
            if (paginatedRecords.length === 0) {
                recordsTable.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4">No records found</td>
                    </tr>
                `;
                return;
            }
            
            paginatedRecords.forEach(record => {
                const statusClass = `status-${record.status}`;
                const statusText = record.status.charAt(0).toUpperCase() + record.status.slice(1);
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.id}</td>
                    <td>${record.fullName}</td>
                    <td>${formatDate(record.dob)}</td>
                    <td>${record.fatherName}</td>
                    <td>${record.motherName}</td>
                    <td><span class="${statusClass}">${statusText}</span></td>
                    <td>
                        <button class="btn btn-sm btn-view me-1" onclick="viewRecord('${record.id}')">
                            <i class="fas fa-eye"></i> View
                        </button>
                        ${record.status === 'pending' ? `
                        <button class="btn btn-sm btn-accept me-1" onclick="showConfirmModal('accept', '${record.id}')">
                            <i class="fas fa-check"></i> Accept
                        </button>
                        <button class="btn btn-sm btn-reject" onclick="showConfirmModal('reject', '${record.id}')">
                            <i class="fas fa-times"></i> Reject
                        </button>
                        ` : ''}
                    </td>
                `;
                recordsTable.appendChild(row);
            });
        }
        
        // Render pagination controls
        function renderPagination() {
            pagination.innerHTML = '';
            const pageCount = Math.ceil(filteredRecords.length / recordsPerPage);
            
            if (pageCount <= 1) return;
            
            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#">Previous</a>`;
            prevLi.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                    renderPagination();
                }
            });
            pagination.appendChild(prevLi);
            
            // Page numbers
            for (let i = 1; i <= pageCount; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === currentPage ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                li.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = i;
                    renderTable();
                    renderPagination();
                });
                pagination.appendChild(li);
            }
            
            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === pageCount ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#">Next</a>`;
            nextLi.addEventListener('click', function(e) {
                e.preventDefault();
                if (currentPage < pageCount) {
                    currentPage++;
                    renderTable();
                    renderPagination();
                }
            });
            pagination.appendChild(nextLi);
        }
        
        // Handle search functionality
        function handleSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            
            if (searchTerm === '') {
                filteredRecords = [...birthRecords];
            } else {
                filteredRecords = birthRecords.filter(record => 
                    record.fullName.toLowerCase().includes(searchTerm) ||
                    record.id.toLowerCase().includes(searchTerm) ||
                    record.fatherName.toLowerCase().includes(searchTerm) ||
                    record.motherName.toLowerCase().includes(searchTerm) ||
                    record.dob.includes(searchTerm)
                );
            }
            
            currentPage = 1;
            renderTable();
            renderPagination();
        }
        
        // Filter records by status
        function filterRecords(status) {
            if (status === 'all') {
                filteredRecords = [...birthRecords];
            } else {
                filteredRecords = birthRecords.filter(record => record.status === status);
            }
            
            currentPage = 1;
            renderTable();
            renderPagination();
            
            // Update active filter badge
            filterBadges.forEach(badge => {
                if (badge.dataset.status === status) {
                    badge.classList.add('active');
                } else {
                    badge.classList.remove('active');
                }
            });
        }
        
        // View record details
        function viewRecord(recordId) {
            const record = birthRecords.find(r => r.id === recordId);
            if (!record) return;
            
            const statusClass = `status-${record.status}`;
            const statusText = record.status.charAt(0).toUpperCase() + record.status.slice(1);
            
            document.getElementById('recordDetails').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-4">Newborn Information</h5>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Full Name</div>
                            <div class="record-detail-value"><?php echo $record["full_name"] ?></div>
                        </div>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Date of Birth</div>
                            <div class="record-detail-value">${formatDate(record.dob)}</div>
                        </div>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Gender</div>
                            <div class="record-detail-value">${record.gender}</div>
                        </div>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Birth Weight (kg)</div>
                            <div class="record-detail-value">${record.weight}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-4">Parents Information</h5>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Father's Full Name</div>
                            <div class="record-detail-value">${record.fatherName}</div>
                        </div>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Father's ID</div>
                            <div class="record-detail-value">${record.fatherId}</div>
                        </div>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Mother's Full Name</div>
                            <div class="record-detail-value">${record.motherName}</div>
                        </div>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Mother's ID</div>
                            <div class="record-detail-value">${record.motherId}</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5 class="mb-4">Contact Information</h5>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Residential Address</div>
                            <div class="record-detail-value">${record.address}</div>
                        </div>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Contact Number</div>
                            <div class="record-detail-value">${record.contact}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-4">Birth Details</h5>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Place of Birth</div>
                            <div class="record-detail-value">${record.placeOfBirth}</div>
                        </div>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Name of Attendant (Doctor/Nurse)</div>
                            <div class="record-detail-value">${record.attendant}</div>
                        </div>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Additional Notes</div>
                            <div class="record-detail-value">${record.notes}</div>
                        </div>
                        <div class="record-detail-item">
                            <div class="record-detail-label">Status</div>
                            <div class="record-detail-value"><span class="${statusClass}">${statusText}</span></div>
                        </div>
                    </div>
                </div>
            `;
            
            viewRecordModal.show();
        }
        
        // Show confirmation modal for accept/reject actions
        function showConfirmModal(action, recordId) {
            const record = birthRecords.find(r => r.id === recordId);
            if (!record) return;
            
            const actionText = action === 'accept' ? 'approve' : 'reject';
            confirmMessage.innerHTML = `
                <p>Are you sure you want to ${actionText} this birth record?</p>
                <p><strong>Record ID:</strong> ${record.id}</p>
                <p><strong>Child Name:</strong> ${record.fullName}</p>
            `;
            
            confirmAction.dataset.action = action;
            confirmAction.dataset.recordId = recordId;
            confirmModal.show();
        }
        
        // Handle confirm action (accept/reject)
        function handleConfirmAction() {
            const action = this.dataset.action;
            const recordId = this.dataset.recordId;
            
            // In a real app, this would be an API call
            const recordIndex = birthRecords.findIndex(r => r.id === recordId);
            if (recordIndex !== -1) {
                birthRecords[recordIndex].status = action === 'accept' ? 'approved' : 'rejected';
            }
            
            confirmModal.hide();
            renderTable();
            renderPagination();
        }
        
        // Helper function to format date
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }
        
        // Make functions available globally
        window.viewRecord = viewRecord;
        window.showConfirmModal = showConfirmModal;
    </script>
</body>
</html>