<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward Information Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            width: 300px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="card">
        <h2 style="text-align: center;">Ward Information Form</h2>

        <form action="{{route('adminAddWardSubmit')}}" method="post">
            @csrf
            <label for="name">Ward Name:</label>
            <input type="text" id="wardName" name="name" required>

            <label for="bn_name">Ward Name Bangla:</label>
            <input type="text" id="wardNameBangla" name="bn_name" required>

            <label for="ward_no">Ward No:</label>
            <input type="number" id="wardNo" name="ward_no" required>

            <button type="submit">Submit</button>
        </form>
    </div>

</body>

