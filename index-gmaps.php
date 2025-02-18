<?php
	// Get database connection
	$conn = mysqli_connect('localhost', 'root', '', 'geocoding');
	$getData = mysqli_query($conn, "SELECT * FROM data");

	if(isset($_POST["addData"])){
		$latlong = trim(htmlspecialchars($_POST['latlong']));
		
		mysqli_query($conn, "INSERT INTO data (presence_addresses) VALUES ('$latlong')");
		if(mysqli_affected_rows($conn)){
			header("Location: index.php");
		} else {
			header("Location: index.php");
		}
	}

	// Sample data $latlong = -6.4680506382369725,106.88995359371344
    function reverseGeocode($latlong) {
        // Maps Reverse Geocoding API URL
        $apiKey = "YOUR-GOOGLE-API";
        
        // Buat URL untuk melakukan reverse geocoding
        $geocodeUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latlong&key=$apiKey";
    
        // Mengambil data dari URL
        $response = file_get_contents($geocodeUrl);
    
        // Cek apakah respon berhasil diambil
        if ($response === FALSE) {
            return "Gagal mengambil data dari Google Maps API.";
        }
    
        // Mengubah response menjadi array
        $data = json_decode($response, true);
    
        // Periksa apakah respon tidak null dan status OK
        if ($data !== null && isset($data['status']) && $data['status'] === 'OK') {
            // Ambil alamat hasil reverse geocoding
            $address = $data['results'][0]['formatted_address'];
            return $address;
        } else {
            // Jika terjadi kesalahan dalam melakukan reverse geocoding
            return "Error dalam melakukan reverse geocoding.";
        }
    }
    
	

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" />

    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="#">Gecoding JPC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Testing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Testing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Testing</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row">
            <div class="col">
                <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    Add Data
                </button>
                <div class="card table-responsive">
                    <div class="card-body">
                        <table id="myTable" class="display">
                            <thead>
                                <tr>
                                    <th>Presence Addresses</th>
                                    <th>Reverse Geocoding</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($getData as $data) : ?>
                                <tr>
                                    <td><?= $data["presence_addresses"] ?></td>
                                    <td><?= reverseGeocode($data["presence_addresses"]) ?></td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Data</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="latlong" class="form-label">Lattitude & Longitude</label>
                        <input type="text" name="latlong" id="latlong" class="form-control"
                            placeholder="Example : 3.9546115,98.3715732" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="addData">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
    $(document).ready(function() {
        $("#myTable").DataTable();
    });
    </script>
</body>

</html>
