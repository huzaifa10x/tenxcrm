<div class="d-flex align-items-center justify-content-between mb-10">
    <button class="btn btn-sm btn-success" id="punchInBtn">Punch In</button>
    <button class="btn btn-sm btn-danger" id="punchOutBtn">Punch Out</button>
</div>

<script>
function getLocationAndPunch(type) {
    if (type === 'out') {
        if (!confirm(
                "Are you sure you want to punch out? Once you punch out, you cannot punch in again today."
            )) {
            return;
        }
    }
    fetch(type === 'in' ? "{{ route('attendance.punchIn') }}" :
            "{{ route('attendance.punchOut') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
            }).then(response => response.json())
        .then(data => {
            alert(data.success || data.error)
            if (data.success || data.error) {
                document.getElementById('punchInBtn').disabled = true;
            }
        });
}
// function getLocationAndPunch(type) {
//     if (navigator.geolocation) {
//         navigator.geolocation.getCurrentPosition(position => {
//             let data = {
//                 latitude: position.coords.latitude,
//                 longitude: position.coords.longitude
//             };
//             if (type === 'out') {
//                 if (!confirm(
//                         "Are you sure you want to punch out? Once you punch out, you cannot punch in again today."
//                     )) {
//                     return;
//                 }
//             }
//             fetch(type === 'in' ? "{{ route('attendance.punchIn') }}" :
//                     "{{ route('attendance.punchOut') }}", {
//                         method: "POST",
//                         headers: {
//                             "X-CSRF-TOKEN": "{{ csrf_token() }}",
//                             "Content-Type": "application/json"
//                         },
//                         body: JSON.stringify(data)
//                     }).then(response => response.json())
//                 .then(data => alert(data.success || data.error));
//         });
//     } else {
//         alert("Location access is required!");
//     }
// }

document.getElementById('punchInBtn').addEventListener('click', () => getLocationAndPunch('in'));
document.getElementById('punchOutBtn').addEventListener('click', () => getLocationAndPunch('out'));
</script>