<label for="district">District:</label><br>
<select name="district" id="district">
    <option value="Iwa" <?php echo (isset($district) && $district == 'Iwa') ? 'selected' : ''; ?>>Iwa</option>
    <option value="Fukuura" <?php echo (isset($district) && $district == 'Fukuura') ? 'selected' : ''; ?>>Fukuura</option>
    <option value="Yoshihama" <?php echo (isset($district) && $district == 'Yoshihama') ? 'selected' : ''; ?>>Yoshihama</option>
    <option value="Shirohori" <?php echo (isset($district) && $district == 'Shirohori') ? 'selected' : ''; ?>>Shirohori</option>
</select><br>