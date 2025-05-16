<?php 
require_once 'session_start.php';
require_once 'connect.php';

// 카테고리별 최신 도서 1권씩
$sqlBest = "
    SELECT *
    FROM (
        SELECT *,
               ROW_NUMBER() OVER (PARTITION BY category ORDER BY created_at ASC) AS rn
        FROM books
        WHERE id LIKE '%1'
    ) AS ranked
    WHERE rn = 1
";
$bestResult = mysqli_query($conn, $sqlBest);

// 최신 도서 5권
$sqlNew = "SELECT * FROM books ORDER BY created_at DESC LIMIT 5";
$newBooks = mysqli_query($conn, $sqlNew);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>온라인 서점</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/banner.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<main>
  <div class="container">
    <!-- 메인 배너 -->
    <section class="main-banner">
      <div class="banner-slider">
        <div class="banner-container">
            <div class="banner-slide active">
                <a href="/category/book_detail.php?id=049">
                    <img src="images/banner1.jpg?<?= time(); ?>" alt="메인 배너 1" class="banner-image">
                </a>
            </div>
            <div class="banner-slide">
                <a href="/category/book_detail.php?id=008">
                    <img src="images/banner2.jpg?<?= time(); ?>" alt="메인 배너 2" class="banner-image">
                </a>
            </div>
            <div class="banner-slide">
                <a href="/category/book_detail.php?id=100">
                    <img src="images/banner3.jpg?<?= time(); ?>" alt="메인 배너 3" class="banner-image">
                </a>
            </div>
        </div>
        <div class="banner-controls">
          <button class="prev-btn"><i class="fas fa-chevron-left"></i></button>
          <button class="next-btn"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
    </section>

    <div class="banner-tabs">
      <button class="banner-tab active">헤세의 철학을 확장하다</button>
      <button class="banner-tab">한강『빛과 실』</button>
      <button class="banner-tab">세계사 탐험대</button>
    </div>

    <!-- 카테고리별 베스트셀러 -->
    <section class="best-sellers">
      <div class="section-header"><h2>카테고리별 베스트셀러</h2></div>
      <div class="book-grid">
        <?php while($book = mysqli_fetch_assoc($bestResult)): ?>
          <a href="category/book_detail.php?id=<?= $book['id'] ?>" class="book-card">
            <img src="<?= $book['image_path'] ?>" class="book-image">
            <div class="book-info">
              <h4><?= $book['title'] ?></h4>
              <p class="author"><?= $book['author'] ?></p>
              <p class="price"><?= number_format($book['price']) ?>원</p>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
    </section>

    <!-- 신간 도서 -->
    <section class="new-releases">
      <div class="section-header"><h2>신간 도서</h2></div>
      <div class="book-grid">
        <?php while($book = mysqli_fetch_assoc($newBooks)): ?>
          <a href="category/book_detail.php?id=<?= $book['id'] ?>" class="book-card">
            <div class="new-badge">NEW</div>
            <img src="<?= $book['image_path'] ?>" class="book-image">
            <div class="book-info">
              <h4><?= $book['title'] ?></h4>
              <p class="author"><?= $book['author'] ?></p>
              <p class="price"><?= number_format($book['price']) ?>원</p>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
    </section>
  </div>
</main>

<?php include 'footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const slides = document.querySelectorAll('.banner-slide');
  const tabs = document.querySelectorAll('.banner-tab');
  const prevBtn = document.querySelector('.prev-btn');
  const nextBtn = document.querySelector('.next-btn');
  let currentSlide = 0;
  let slideInterval;

  function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    tabs.forEach(tab => tab.classList.remove('active'));
    slides[index].classList.add('active');
    tabs[index].classList.add('active');
    currentSlide = index;
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
  }

  function startSlideShow() {
    slideInterval = setInterval(nextSlide, 5000);
  }

  function stopSlideShow() {
    clearInterval(slideInterval);
  }

  prevBtn.addEventListener('click', () => {
    stopSlideShow();
    prevSlide();
    startSlideShow();
  });

  nextBtn.addEventListener('click', () => {
    stopSlideShow();
    nextSlide();
    startSlideShow();
  });

  tabs.forEach((tab, index) => {
    tab.addEventListener('click', () => {
      stopSlideShow();
      showSlide(index);
      startSlideShow();
    });
  });

  const banner = document.querySelector('.banner-slider');
  banner.addEventListener('mouseenter', stopSlideShow);
  banner.addEventListener('mouseleave', startSlideShow);

  startSlideShow();
});
</script>
</body>
</html>
