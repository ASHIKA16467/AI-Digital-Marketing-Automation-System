<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Contact Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hidden { display: none; }
    </style>
</head>
<body class="bg-light">
<div class="container my-5">
    <h2 class="mb-4 text-center">📧 Add Contacts for Email Marketing</h2>

    <form id="contactForm" class="card p-4 shadow">
        <!-- Group Section -->
        <div class="mb-3">
            <label for="groupSelect" class="form-label">Select Contact Group</label>
            <select id="groupSelect" name="group" class="form-select" required>
                <option value="">-- Choose a Group --</option>
                <option value="New">➕ Create New Group</option>
                <option value="Group 1">Group 1</option>
                <option value="Group 2">Group 2</option>
            </select>
        </div>

        <div class="mb-3 hidden" id="newGroupInput">
            <label for="newGroupName" class="form-label">New Group Name</label>
            <input type="text" class="form-control" name="newGroup" id="newGroupName" placeholder="Enter new group name">
        </div>

        <!-- Mode Switch -->
        <div class="mb-3">
            <label class="form-label">Add Contacts</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mode" id="manualMode" value="manual" checked>
                    <label class="form-check-label" for="manualMode">Manual Entry</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="mode" id="excelMode" value="excel">
                    <label class="form-check-label" for="excelMode">Upload Excel</label>
                </div>
            </div>
        </div>

        <!-- Manual Entry Fields -->
        <div id="manualFields">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. John Doe">
            </div>
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="e.g. john@example.com">
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control" placeholder="e.g. 9876543210">
            </div>
            <div class="mb-3">
                <label class="form-label">Remarks (Optional)</label>
                <textarea name="remarks" class="form-control" placeholder="Notes or tag (optional)"></textarea>
            </div>
        </div>

        <!-- Excel Upload -->
        <div id="excelUpload" class="mb-3 hidden">
            <label class="form-label">Upload Excel File (.xlsx, .csv)</label>
            <input type="file" name="excel_file" accept=".xlsx,.csv" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">✅ Add Contact(s)</button>
        <div id="successMsg" class="alert alert-success mt-3 d-none">✅ Contacts added successfully!</div>
    </form>
</div>

<script>
    document.getElementById("groupSelect").addEventListener("change", function () {
        const newGroupInput = document.getElementById("newGroupInput");
        newGroupInput.classList.toggle("hidden", this.value !== "New");
    });

    document.querySelectorAll('input[name="mode"]').forEach(radio => {
        radio.addEventListener("change", function () {
            const manualFields = document.getElementById("manualFields");
            const excelUpload = document.getElementById("excelUpload");
            if (this.value === "manual") {
                manualFields.classList.remove("hidden");
                excelUpload.classList.add("hidden");
            } else {
                manualFields.classList.add("hidden");
                excelUpload.classList.remove("hidden");
            }
        });
    });

    document.getElementById("contactForm").addEventListener("submit", function (e) {
        e.preventDefault();
        document.getElementById("successMsg").classList.remove("d-none");
        setTimeout(() => {
            document.getElementById("successMsg").classList.add("d-none");
            this.reset();
            document.getElementById("manualFields").classList.remove("hidden");
            document.getElementById("excelUpload").classList.add("hidden");
            document.getElementById("newGroupInput").classList.add("hidden");
        }, 3000);
    });
</script>

</body>
</html>
