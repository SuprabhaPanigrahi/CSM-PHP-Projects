@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Create New Project</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('project.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Project Code *</label>
                            <input type="text" class="form-control" name="ProjectCode" required>
                            @error('ProjectCode')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Project Name *</label>
                            <input type="text" class="form-control" name="ProjectName" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Project Type *</label>
                            <select class="form-control" name="ProjectType" required>
                                <option value="">Select Type</option>
                                <option value="Billable">Billable</option>
                                <option value="Non-Billable">Non-Billable</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Priority *</label>
                            <select class="form-control" name="Priority" required>
                                <option value="">Select Priority</option>
                                <option value="Normal">Normal</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Location Type *</label>
                            <select class="form-control" name="LocationType" required id="LocationType">
                                <option value="">Select Location</option>
                                <option value="Onsite">Onsite</option>
                                <option value="Offshore">Offshore</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location Country</label>
                            <select class="form-control" name="LocationCountryId" id="LocationCountryId">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->CountryId }}">{{ $country->CountryName }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Required only for Onsite projects</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Technology *</label>
                            <select class="form-control" name="TechnologyId" required>
                                <option value="">Select Technology</option>
                                @foreach($technologies as $technology)
                                    <option value="{{ $technology->TechnologyId }}">{{ $technology->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start Date *</label>
                            <input type="date" class="form-control" name="StartDate" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="EndDate">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('allocations.list') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Show/hide country selection based on location type
    $('#LocationType').change(function() {
        if ($(this).val() === 'Onsite') {
            $('#LocationCountryId').prop('required', true);
        } else {
            $('#LocationCountryId').prop('required', false);
        }
    });
});
</script>
@endsection