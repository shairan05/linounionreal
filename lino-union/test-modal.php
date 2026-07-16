<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <h2>Modal Test</h2>
    
    <!-- Test 1: data-bs-toggle -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#testModal1">
        Open Modal (data-bs-toggle)
    </button>
    
    <!-- Test 2: Pure JS -->
    <button type="button" class="btn btn-secondary" onclick="openModal()">
        Open Modal (pure JS)
    </button>
    
    <div class="modal fade" id="testModal1" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5>Test Modal 1</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">Opened via data-bs-toggle</div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="testModal2" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5>Test Modal 2</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">Opened via pure JS</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function openModal() {
        var el = document.getElementById('testModal2');
        var m = new bootstrap.Modal(el);
        m.show();
    }
    console.log('Bootstrap loaded:', typeof bootstrap !== 'undefined');
    console.log('Modal test ready');
    </script>
</body>
</html>
