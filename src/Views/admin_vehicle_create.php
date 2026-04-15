<h1>Add Vehicle</h1>

<form method="post" action="/admin/vehicles/store">
    <input name="title" placeholder="Title" required><br><br>
    <input name="brand" placeholder="Brand" required><br><br>
    <input name="type" placeholder="Type (SUV, 4x4...)" required><br><br>
    <input type="number" step="0.01" name="price" placeholder="Price" required><br><br>
    <input type="number" name="mileage" placeholder="Mileage"><br><br>
    <input type="number" name="year" placeholder="Year"><br><br>
    <textarea name="description" placeholder="Description"></textarea><br><br>
    <button type="submit">Save</button>
</form>

