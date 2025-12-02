$(document).ready(function() {
    const today = new Date().toISOString().split('T')[0];
    $('#FromDate').attr('min', today);
    $('#ToDate').attr('min', today);
    
    // Show today's date
    $('#FromDate').val(today);
    
    // Department dropdown change
    $('#DepartmentId').change(function() {
        const deptId = $(this).val();
        
        if (!deptId) {
            $('#EmployeeId').empty().append('<option value="">Select Employee</option>').prop('disabled', true);
            $('#LeaveTypeId').empty().append('<option value="">Select Leave Type</option>').prop('disabled', true);
            return;
        }
        
        $('#EmployeeId').prop('disabled', true).html('<option value="">Loading employees...</option>');
        $('#LeaveTypeId').prop('disabled', true).html('<option value="">Select Leave Type</option>');
        

        $.ajax({
            url: `/leave/employees/${deptId}`,
            type: 'GET',
            success: function(data) {
                if (data.length === 0) {
                    $('#EmployeeId').html('<option value="">No employees found</option>');
                } else {
                    let options = '<option value="">Select Employee</option>';
                    data.forEach(function(emp) {
                        options += `<option value="${emp.EmployeeId}">${emp.FirstName} ${emp.LastName} (${emp.EmployeeCode})</option>`;
                    });
                    $('#EmployeeId').html(options).prop('disabled', false);
                }
            },
            error: function() {
                $('#EmployeeId').html('<option value="">Error loading employees</option>');
                alert('Error loading employees');
            }
        });
    });
    
    $('#EmployeeId').change(function() {
        const empId = $(this).val();
        
        if (!empId) {
            $('#LeaveTypeId').empty().append('<option value="">Select Leave Type</option>').prop('disabled', true);
            return;
        }
        
        $('#LeaveTypeId').prop('disabled', true).html('<option value="">Loading leave types...</option>');
        
        // Fetch leave types
        $.ajax({
            url: `/leave/leave-types/${empId}`,
            type: 'GET',
            success: function(data) {
                if (data.length === 0) {
                    $('#LeaveTypeId').html('<option value="">No available leave types</option>');
                } else {
                    let options = '<option value="">Select Leave Type</option>';
                    data.forEach(function(type) {
                        options += `<option value="${type.LeaveTypeId}">${type.LeaveTypeName}</option>`;
                    });
                    $('#LeaveTypeId').html(options).prop('disabled', false);
                }
            },
            error: function() {
                $('#LeaveTypeId').html('<option value="">Error loading leave types</option>');
                alert('Error loading leave types');
            }
        });
    });
    
    $('#FromDate, #ToDate').change(function() {
        const fromDate = $('#FromDate').val();
        const toDate = $('#ToDate').val();
        
        if (fromDate && toDate) {
            const from = new Date(fromDate);
            const to = new Date(toDate);
            const today = new Date();
            today.setHours(0,0,0,0);
            
            // Validate dates
            if (from < today) {
                alert('From date cannot be in the past');
                $('#FromDate').val(today);
                $('#totalDaysContainer').hide();
                return;
            }
            
            if (from > to) {
                alert('To date must be after from date');
                $('#ToDate').val('');
                $('#totalDaysContainer').hide();
                return;
            }
            
            // Calculate working days
            $.ajax({
                url: '/leave/calculate-days',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    fromDate: fromDate,
                    toDate: toDate
                },
                success: function(response) {
                    $('#totalDays').text(response.totalDays);
                    $('#totalDaysContainer').show();
                },
                error: function() {
                    alert('Error calculating days');
                }
            });
        }
    });
    
    // Client-side form validation
    $('#leaveForm').submit(function(e) {
        let valid = true;
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Check required fields
        $('#DepartmentId, #EmployeeId, #LeaveTypeId, #FromDate, #ToDate, #Reason').each(function() {
            if ($(this).val() === '') {
                valid = false;
                $(this).addClass('is-invalid');
                $(this).after('<div class="invalid-feedback">This field is required</div>');
            }
        });
        
        // Check date validation
        const fromDate = $('#FromDate').val();
        const toDate = $('#ToDate').val();
        
        if (fromDate && toDate) {
            const from = new Date(fromDate);
            const to = new Date(toDate);
            const today = new Date();
            today.setHours(0,0,0,0);
            
            if (from < today) {
                valid = false;
                $('#FromDate').addClass('is-invalid');
                $('#FromDate').after('<div class="invalid-feedback">From date cannot be in the past</div>');
            }
            
            if (from > to) {
                valid = false;
                $('#ToDate').addClass('is-invalid');
                $('#ToDate').after('<div class="invalid-feedback">To date must be after from date</div>');
            }
        }
        
        // Check reason length
        const reason = $('#Reason').val();
        if (reason && reason.length > 500) {
            valid = false;
            $('#Reason').addClass('is-invalid');
            $('#Reason').after('<div class="invalid-feedback">Reason must be less than 500 characters</div>');
        }
        
        if (!valid) {
            e.preventDefault();
            alert('Please fix the errors before submitting');
        }
    });
});