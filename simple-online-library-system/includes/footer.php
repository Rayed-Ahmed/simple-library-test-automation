<?php
// /includes/footer.php
?>
    </main>
    <footer>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Simple Online Library System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
<?php
// Close the database connection
if (isset($conn)) {
    $conn->close();
}
?> 