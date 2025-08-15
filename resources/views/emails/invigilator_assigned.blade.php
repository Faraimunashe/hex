<!DOCTYPE html>
<html>
<body>
    <h2>Invigilation Assignment</h2>
    <p>You have been assigned as an invigilator.</p>
    <ul>
        <li>Module: {{ $exam->module->code }} - {{ $exam->module->name }}</li>
        <li>Date: {{ $exam->exam_date }}</li>
        <li>Time: {{ $exam->start_time }} - {{ $exam->end_time }}</li>
        <li>Venue: {{ $exam->venue->name ?? 'TBA' }}</li>
    </ul>
    <p>Please be on time.</p>
</body>
</html>

