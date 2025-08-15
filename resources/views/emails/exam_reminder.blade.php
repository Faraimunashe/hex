<!DOCTYPE html>
<html>
<body>
    <h2>{{ $recipientType === 'invigilator' ? 'Invigilation Reminder' : 'Exam Reminder' }}</h2>
    <p>
        {{ $recipientType === 'invigilator' ? 'You are scheduled to invigilate the following exam:' : 'You are scheduled to sit the following exam:' }}
    </p>
    <ul>
        <li>Module: {{ $exam->module->code }} - {{ $exam->module->name }}</li>
        <li>Date: {{ $exam->exam_date }}</li>
        <li>Time: {{ $exam->start_time }} - {{ $exam->end_time }}</li>
        <li>Venue: {{ $exam->venue->name ?? 'TBA' }}</li>
    </ul>
    <p>Good luck!</p>
</body>
</html>

