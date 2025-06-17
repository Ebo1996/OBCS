<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="card flex-fill">
        <div class="card-header bg-success text-white">Search by Birth Certificate</div>
        <div class="card-body">
            <form id="certificateSearchForm">
                <div class="mb-3">
                    <label for="certificateNumber" class="form-label">Certificate Number</label>
                    <input type="text" name="certificate_id" class="form-control" id="certificateNumber" required>
                </div>
                <button type="submit" class="btn btn-success">Search Certificate</button>
            </form>
            <div id="certificateResult" class="mt-4"></div>
        </div>
    </div>

</body>
<script>
    document.getElementById('certificateSearchForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const certId = document.getElementById('certificateNumber').value;
        const resultDiv = document.getElementById('certificateResult');
        resultDiv.innerHTML = 'Searching...';

        fetch('search_certificate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'certificate_id=' + encodeURIComponent(certId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `
                <div class="certificate-modern">
                    <div class="certificate-header-modern">
                        <h5>Federal Democratic Republic of Ethiopia</h5>
                        <h6>Ministry of Health</h6>
                        <h4>${data.hospital.name}</h4>
                        <h6>Birth Certificate</h6>
                    </div>

                    <div class="certificate-content-modern">
                        <div>
                            <img src="https://via.placeholder.com/150" alt="Certificate Photo" class="certificate-photo-modern">
                        </div>
                        <div class="certificate-details-modern">
                            <table class="certificate-table-modern">
                                <tr><th>B.R.ID</th><td>${data.birth_record.record_id}</td></tr>
                                <tr><th>Child Name</th><td>${data.birth_record.child_name}</td></tr>
                                <tr><th>Father's Name</th><td>${data.birth_record.father_name}</td></tr>
                                <tr><th>Mother's Name</th><td>${data.birth_record.mother_name}</td></tr>
                                <tr><th>Date of Birth</th><td>${data.birth_record.dob}</td></tr>
                                <tr><th>Place of Birth</th><td>${data.birth_record.pob}</td></tr>
                                <tr><th>Issued Date</th><td>${data.birth_record.created_at}</td></tr>
                                <tr><th>Hospital Email</th><td>${data.hospital.email}</td></tr>
                            </table>
                        </div>
                    </div>

                    <div class="certificate-footer-modern">
                        <div class="signature-box-modern">
                            <p><strong>Registrar Name:</strong> ${data.birth_record.nameOfDoctor}</p>
                            <img src="../images/certificate.jpg" alt="Signature" class="img-fluid">
                        </div>
                        <div class="signature-box-modern">
                            <p><strong>Official Seal / Stamp:</strong></p>
                            <img src="../images/certificate.jpg" alt="Stamp" class="img-fluid">
                        </div>
                    </div>
                </div>`;
                } else {
                    resultDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            })
            .catch(err => {
                resultDiv.innerHTML = `<div class="alert alert-danger">Error fetching data</div>`;
                console.error(err);
            });
    });
</script>

</html>