<?php
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION == false) {
  header('Location:login.php');
}
include 'head/header.php';
include 'config/db.php';
$num_per_page = 16;
if (isset($_GET["page"])) {
  $page = $_GET["page"];
} else {
  $page = 1;
}
$start_from = ($page - 1) * 16;
$sql = "select * from alumni limit $start_from, $num_per_page";
$result = $conn->query($sql);
?>

<head>
  <link href="assets/css/alumni.css" rel="stylesheet">

</head>
<main id="main" data-aos="fade-in">

  <!-- ======= Breadcrumbs ======= -->
  <div class="breadcrumbs">
    <div class="container">
      <h2>Alumni</h2>
      <p> The alumni management system streamlines communication and facilitates meaningful interactions to strengthen the bond between the institution and its graduates.</p>
    </div>
  </div><!-- End Breadcrumbs -->

  <div id="filter-options">
    <label for="university-select">Select University:</label>
    <select id="university-select" class="form-control form-control-lg">
      <option value="all" selected class="input-fields">All</option>
      <option value="pokhara" class="input-fields"> Pokhara University</option>
      <!-- <option value="<?= $row['university'] ?>" class="input-fields">University</option> -->
    </select>

    <label for="school-select" >Select College:</label>
    <select id="school-select"class="form-control">
      <option value="all" selected class="input-fields">All</option>
      <option value="utc" class="input-fields">United Technical College </option>
      <!-- <option value="tuc" class="input-fields">TUC</option> -->
    </select>

    <label for="faculty-select">Select Faculty:</label>
    <select id="faculty-select" class="form-control form-control-lg">
      <option value="all" selected class="input-fields">All</option>
      <option value="computer" class="input-fields">BE Computer </option>
      <option value="civil" class="input-fields">BE Civil</option>
      <option value="electrical" class="input-fields">BE Electrical and Electronics</option>
    </select>
  </div>
  

  <div style="display: flex; justify-content: center; align-items: center;">
  <input class="form-control" id="myInput" type="text" placeholder="Search Alumni .." style="width: 300px;">
</div>


  <!-- ======= Alumni Section ======= -->
  <section id="trainers" class="trainers">
    <div class="container" data-aos="fade-up">

      <div class="row" data-aos="zoom-in" data-aos-delay="100">
        <!-- <div id="filterable-div"> -->
        <!-- <div class="col-lg-4 col-md-6 d-flex align-items-stretch"> -->
        <?php
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) { 
            if(($row['faculty'])=="BE Computer" ){
              $computer="computer";
            }
            else if(($row['faculty'])=="BE Civil" ){
              $computer="civil";
            }
           else if (($row['faculty'])=="BE Electrical and Electronics" ){
              $computer="electrical" ;
            }
            ?>
            <div class="item pokhara utc <?=$computer?> col-lg-4 col-md-6">
              <div class="member">
                <img src="upload_images./<?= $row['alumni_image'] ?>" class="img-fluid " style="height:250px; width:250px" alt="<?= $row['alumni_image'] ?>">
                <div class="member-content">
                  <h4><?= $row['first_name'] ?> <?= $row['middle_name'] ?> <?= $row['last_name'] ?></h4>
                  <span>  <?= $row['faculty'] ?> , <?= $row['batch'] ?></span>
                  <p>
                    
                  </p>
                  <a href="alumni-details.php?alumni_id=<?=$row['alumni_id']?>">
                    <h4 style="color: #DC3233;" > View Details</h4>
                  </a>
                </div>
              </div>
            </div>
        <?php }
        } else {
          echo "No records found.";
        }
        ?>




        <!-- </div> -->
        <!-- </div> -->
        <!-- <div class="item tu tuc computer">tuc 1</div>
            <div class="item tu tuc civil">tuc2 </div>
            <div class="item pokhara utc elec">utc 3</div>
            <div class="item tu tuc elec">tuc3</div> -->

        <!-- Add more faculties under each school as needed -->

        <!-- </div>filterable-div -->
      </div>
    </div>
    <div class="text-center">
      <ul class="justify-content-center list-inline">
        <?php
        $sql = "SELECT * FROM alumni";
        $result = $conn->query($sql);
        $total_records = mysqli_num_rows($result);
        $total_pages = ceil($total_records / $num_per_page);
        for ($i = 1; $i <= $total_pages; $i++) {
        ?>
          <li class="page-item list-inline-item">
            <a class="page-link" href='alumni.php?page=<?= $i ?>' style="background-color: 	 #7879FF; color: white; padding: 5px 10px; border-radius: 5px;"><?= $i ?></a>
          </li>
        <?php } ?>
      </ul>
    </div>


  </section><!-- End Trainers Section -->

</main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include('head/footer.php'); ?>


<script>
  // Get the necessary elements
  const universitySelect = document.getElementById('university-select');
  const schoolSelect = document.getElementById('school-select');
  const facultySelect = document.getElementById('faculty-select');
  const items = document.getElementsByClassName('item');

  // Attach event listener to university select
  universitySelect.addEventListener('change', filterItems);

  // Attach event listener to school select
  schoolSelect.addEventListener('change', filterItems);

  // Attach event listener to faculty select
  facultySelect.addEventListener('change', filterItems);

  // Function to filter items based on selected options
  function filterItems() {
    const selectedUniversity = universitySelect.value;
    const selectedSchool = schoolSelect.value;
    const selectedFaculty = facultySelect.value;

    // Loop through items and show/hide based on selected options
    for (let i = 0; i < items.length; i++) {
      const item = items[i];
      const university = item.classList.contains(selectedUniversity) || selectedUniversity === 'all';
      const school = item.classList.contains(selectedSchool) || selectedSchool === 'all';
      const faculty = item.classList.contains(selectedFaculty) || selectedFaculty === 'all';

      if (university && school && faculty) {
        item.classList.add('show');
      } else {
        item.classList.remove('show');
      }
    }
  }

  // Display all items initially
  for (let i = 0; i < items.length; i++) {
    const item = items[i];
    item.classList.add('show');
  }
</script>
<script>
  // Get the search input field
  const searchInput = document.getElementById('myInput');

  // Get all alumni items
  const alumniItems = document.querySelectorAll('.item');

  // Attach an event listener to the search input
  searchInput.addEventListener('input', filterAlumni);

  function filterAlumni() {
    const searchTerm = searchInput.value.toLowerCase();

    alumniItems.forEach(item => {
      const alumniName = item.querySelector('h4').textContent.toLowerCase();

      if (alumniName.includes(searchTerm)) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  }
</script>

<!-- Vendor JS Files -->
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>