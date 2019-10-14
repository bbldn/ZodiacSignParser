<html>
<head>
    <title>Hello</title>
</head>

<body>

<table border="1" class="main-table">
    <thead>
    <tr>
        @foreach($header as $item)
            <td>{{ $item }}</td>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            @foreach($row as $item)
                <td>{{ $item }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
