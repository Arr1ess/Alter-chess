<?php
if (isset($_GET['success'])) {
	echo "<div class=\"alert alert-success\" role=\"alert\">" . $_GET['success'] . "</div>";
} elseif (isset($_GET['error'])) {
	echo "<div class=\"alert alert-danger\" role=\"alert\">" . $_GET['error'] . "</div>";
}
?>