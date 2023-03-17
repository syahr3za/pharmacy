<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Income Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <h3 class="text-center">Income Report</h3>
    <h4 class="text-center">
        From {{ en_date($first, false) }}
        until {{ en_date($last, false) }}
    </h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Date</th>
                <th>Sale</th>
                <th>Purchase</th>
                <th>Stock Out</th>
                <th>Income</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                @foreach ($row as $col)
                <td>{{ $col }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
