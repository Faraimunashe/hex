<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
    </style>
    <title>Lecturer Timetable</title>
  </head>
  <body>
    <div class="title">Lecturer Timetable</div>
    <table>
      <thead>
        <tr>
          <th>Module Code</th>
          <th>Module Name</th>
          <th>Exam Date</th>
          <th>Start Time</th>
          <th>End Time</th>
          <th>Venue</th>
          <th>Invigilators</th>
        </tr>
      </thead>
      <tbody>
        @foreach($exams as $exam)
          <tr>
            <td>{{ $exam->module->code ?? '' }}</td>
            <td>{{ $exam->module->name ?? '' }}</td>
            <td>{{ $exam->exam_date }}</td>
            <td>{{ $exam->start_time }}</td>
            <td>{{ $exam->end_time }}</td>
            <td>{{ $exam->venue->name ?? 'TBA' }}</td>
            <td>
              @foreach($exam->invigilations as $i)
                <span>{{ $i->user->name ?? '' }}</span>@if(!$loop->last), @endif
              @endforeach
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </body>
</html>

