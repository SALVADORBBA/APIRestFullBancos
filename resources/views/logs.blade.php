<!DOCTYPE html>
<html>

<head>
    <title>Logs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="mt-5">Logs</h1>
        <div class="row mt-3">
            <div class="col-md-12">
                @foreach ($logs as $log)
                @foreach (explode('[', $log) as $splitLog)
                @if (!empty(trim($splitLog)))
                <div class="card mb-3">
                    <div class="card-body">
                        [{{ $splitLog }}
                    </div>
                </div>
                @endif
                @endforeach
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>