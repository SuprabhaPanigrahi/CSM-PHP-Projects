<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Citizen - Citizen Registration System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-title {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        .required::after {
            content: " *";
            color: red;
        }
        .dropdown-chain {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .dropdown-chain .form-group {
            flex: 1;
            min-width: 200px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="form-title"><i class="fas fa-edit me-2"></i>Edit Citizen</h2>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('citizens.update', $citizen->CitizenId) }}" method="POST" id="citizenForm">
            @csrf
            @method('PUT')
            
            <!-- Personal Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label required">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               value="{{ old('name', $citizen->CitizenName) }}" placeholder="Enter full name">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gender" class="form-label required">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="" disabled>Select Gender</option>
                            <option value="Male" {{ old('gender', $citizen->CitizenGender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $citizen->CitizenGender) == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $citizen->CitizenGender) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label required">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required 
                               value="{{ old('phone', $citizen->CitizenPhone) }}" placeholder="Enter phone number">
                        @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email', $citizen->CitizenEmail) }}" placeholder="Enter email address">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Address Information -->
            <div class="dropdown-chain">
                <div class="form-group mb-3">
                    <label for="state" class="form-label required">State</label>
                    <select class="form-select" id="state" name="state_id" required>
                        <option value="" selected disabled>Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->StateId }}" 
                                @if($citizen->village && $citizen->village->panchayat && $citizen->village->panchayat->block && $citizen->village->panchayat->block->state)
                                    {{ $citizen->village->panchayat->block->state->StateId == $state->StateId ? 'selected' : '' }}
                                @endif>
                                {{ $state->StateName }}
                            </option>
                        @endforeach
                    </select>
                    @error('state_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="block" class="form-label required">Block</label>
                    <select class="form-select" id="block" name="block_id" required disabled>
                        <option value="" selected disabled>Select Block</option>
                        @if($citizen->village && $citizen->village->panchayat && $citizen->village->panchayat->block)
                            <option value="{{ $citizen->village->panchayat->block->BlockId }}" selected>
                                {{ $citizen->village->panchayat->block->BlockName }}
                            </option>
                        @endif
                    </select>
                    @error('block_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="panchayat" class="form-label required">Panchayat</label>
                    <select class="form-select" id="panchayat" name="panchayat_id" required disabled>
                        <option value="" selected disabled>Select Panchayat</option>
                        @if($citizen->village && $citizen->village->panchayat)
                            <option value="{{ $citizen->village->panchayat->PanchayatId }}" selected>
                                {{ $citizen->village->panchayat->PanchayatName }}
                            </option>
                        @endif
                    </select>
                    @error('panchayat_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="village" class="form-label required">Village</label>
                    <select class="form-select" id="village" name="village_id" required disabled>
                        <option value="" selected disabled>Select Village</option>
                        @if($citizen->village)
                            <option value="{{ $citizen->village->VillageId }}" selected>
                                {{ $citizen->village->VillageName }}
                            </option>
                        @endif
                    </select>
                    @error('village_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Submit Buttons -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('citizens.index') }}" class="btn btn-secondary me-md-2">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Citizen
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preloaded data from Laravel (without API calls)
        const allBlocks = @json($allBlocks ?? []);
        const allPanchayats = @json($allPanchayats ?? []);
        const allVillages = @json($allVillages ?? []);

        document.addEventListener('DOMContentLoaded', function() {
            const stateSelect = document.getElementById('state');
            const blockSelect = document.getElementById('block');
            const panchayatSelect = document.getElementById('panchayat');
            const villageSelect = document.getElementById('village');

            // Get current values from the form
            const currentStateId = stateSelect.value;
            const currentBlockId = blockSelect.value;
            const currentPanchayatId = panchayatSelect.value;
            const currentVillageId = villageSelect.value;

            // State change event - filter blocks locally
            stateSelect.addEventListener('change', function() {
                const stateId = this.value;
                resetDependentDropdowns(['block', 'panchayat', 'village']);
                
                if(stateId) {
                    loadBlocks(stateId);
                }
            });

            // Block change event - filter panchayats locally
            blockSelect.addEventListener('change', function() {
                const blockId = this.value;
                resetDependentDropdowns(['panchayat', 'village']);
                
                if(blockId) {
                    loadPanchayats(blockId);
                }
            });

            // Panchayat change event - filter villages locally
            panchayatSelect.addEventListener('change', function() {
                const panchayatId = this.value;
                resetDependentDropdowns(['village']);
                
                if(panchayatId) {
                    loadVillages(panchayatId);
                }
            });

            // Initialize the form
            initializeForm();

            function initializeForm() {
                // Enable block dropdown and load data if state is selected
                if (currentStateId) {
                    loadBlocks(currentStateId, function() {
                        if (currentBlockId) {
                            loadPanchayats(currentBlockId, function() {
                                if (currentPanchayatId) {
                                    loadVillages(currentPanchayatId);
                                }
                            });
                        }
                    });
                }
            }

            // Load blocks by filtering preloaded data
            function loadBlocks(stateId, callback) {
                const blockSelect = document.getElementById('block');
                
                // Show loading
                blockSelect.innerHTML = '<option value="">Loading blocks...</option>';
                blockSelect.disabled = false;

                // Filter blocks locally from preloaded data
                const blocks = allBlocks.filter(block => block.StateId == stateId);
                
                // Small delay to show loading (optional)
                setTimeout(() => {
                    populateDropdown(blockSelect, blocks, 'BlockId', 'BlockName', currentBlockId);
                    if (callback) callback();
                }, 100);
            }

            // Load panchayats by filtering preloaded data
            function loadPanchayats(blockId, callback) {
                const panchayatSelect = document.getElementById('panchayat');
                
                panchayatSelect.innerHTML = '<option value="">Loading panchayats...</option>';
                panchayatSelect.disabled = false;

                // Filter panchayats locally from preloaded data
                const panchayats = allPanchayats.filter(panchayat => panchayat.BlockId == blockId);
                
                setTimeout(() => {
                    populateDropdown(panchayatSelect, panchayats, 'PanchayatId', 'PanchayatName', currentPanchayatId);
                    if (callback) callback();
                }, 100);
            }

            // Load villages by filtering preloaded data
            function loadVillages(panchayatId) {
                const villageSelect = document.getElementById('village');
                
                villageSelect.innerHTML = '<option value="">Loading villages...</option>';
                villageSelect.disabled = false;

                // Filter villages locally from preloaded data
                const villages = allVillages.filter(village => village.PanchayatId == panchayatId);
                
                setTimeout(() => {
                    populateDropdown(villageSelect, villages, 'VillageId', 'VillageName', currentVillageId);
                }, 100);
            }

            // Helper function to populate dropdowns
            function populateDropdown(selectElement, data, valueField, textField, selectedValue = null) {
                selectElement.innerHTML = '<option value="" selected disabled>Select ' + selectElement.name.replace('_id', '') + '</option>';
                
                if (data.length === 0) {
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "No options available";
                    option.disabled = true;
                    selectElement.appendChild(option);
                    return;
                }
                
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item[valueField];
                    option.textContent = item[textField];
                    
                    // Set selected if this is the current value
                    if (selectedValue && item[valueField] == selectedValue) {
                        option.selected = true;
                    }
                    
                    selectElement.appendChild(option);
                });
            }

            // Reset dependent dropdowns
            function resetDependentDropdowns(dropdownNames) {
                dropdownNames.forEach(name => {
                    const select = document.getElementById(name);
                    if (select) {
                        select.innerHTML = '<option value="" selected disabled>Select ' + name + '</option>';
                        select.disabled = true;
                    }
                });
            }
        });
    </script>
</body>
</html>