<!DOCTYPE html>
<html>
<head>
    <title>Email Recipients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .emails-list {
            list-style-type: none;
            padding: 0;
        }
        .emails-list li {
            margin: 5px 0;
        }
        .print-btn {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Email Recipients</h1>
            @if(session('success'))
                <p>{{ session('success') }}</p>
            @endif
        </div>

        @if(!empty($emails))
        <div id="recordsInfo" style="margin-bottom: 10px;">
            <strong>Showing 1 to {{ min(10, count($emails)) }} of {{ count($emails) }} records</strong>
        </div>
        
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse; text-align: left;" id="emailTable">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($emails as $key => $email)
                    <tr class="{{ $key >= 10 ? 'hidden-row' : '' }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $email['name'] }}</td>
                        <td>{{ $email['email'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    
        @if(count($emails) > 10)
            <button id="showMoreBtn" style="margin-top:10px; padding:8px 16px; cursor:pointer;">Show More</button>
        @endif
    
    @else
        <p>No emails found.</p>
    @endif
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let visibleCount = 10;
            const rows = document.querySelectorAll("#emailTable tbody tr");
            const showMoreBtn = document.getElementById("showMoreBtn");
            const recordsInfo = document.getElementById("recordsInfo");
    
            function showNextBatch() {
                let nextCount = visibleCount + 10;
                for (let i = visibleCount; i < nextCount && i < rows.length; i++) {
                    rows[i].style.display = 'table-row';
                }
                visibleCount = nextCount;
    
                // Update the range of records being shown
                recordsInfo.innerHTML = `<strong>Showing ${visibleCount - 9} to ${Math.min(visibleCount, rows.length)} of ${rows.length} records</strong>`;
    
                if (visibleCount >= rows.length) {
                    showMoreBtn.style.display = 'none'; // hide button when done
                }
            }
    
            if(showMoreBtn) {
                showMoreBtn.addEventListener("click", showNextBatch);
            }
        });
    </script>
    
    <style>
        .hidden-row {
            display: none;
        }
    </style>
    
    


        <!-- Print Button -->
        <button class="print-btn" onclick="window.print()">Print Email Recipients</button>
    </div>

    <!-- Optional: Add print-specific styles to hide unwanted content during printing -->
    <style>
        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</body>
</html>
