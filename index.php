<?php require_once 'session_start.php'; ?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <section class="main-banner">
                <div class="banner-slider">
                    <div class="banner-container">
                        <div class="banner-slide active">
                            <img src="images/banner1.jpg?<?php echo time(); ?>" alt="메인 배너 1" class="banner-image">
                        </div>
                        <div class="banner-slide">
                            <img src="images/banner2.jpg?<?php echo time(); ?>" alt="메인 배너 2" class="banner-image">
                        </div>
                        <div class="banner-slide">
                            <img src="images/banner3.jpg?<?php echo time(); ?>" alt="메인 배너 3" class="banner-image">
                        </div>
                    </div>
                    <div class="banner-controls">
                        <button class="prev-btn"><i class="fas fa-chevron-left"></i></button>
                        <button class="next-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </section>
            <div class="banner-tabs">
                <button class="banner-tab active">5월의 굿즈 : 디즈니 캐릭터 텀블러</button>
                <button class="banner-tab">한강『빛과 실』</button>
                <button class="banner-tab">어린이날, 우리가 주인공!</button>
            </div>

            <section class="best-sellers">
                <div class="section-header">
                    <h2>베스트셀러</h2>
                    <a href="#" class="more-link">더보기 <i class="fas fa-chevron-right"></i></a>
                </div>
                <div class="book-grid">
                    <div class="book-card">
                        <div class="book-rank">1</div>
                        <img src="images/book1.jpg" alt="도서 이미지" class="book-image">
                        <div class="book-info">
                            <h4>도서 제목 1</h4>
                            <p class="author">저자명</p>
                            <p class="price">15,000원</p>
                            <div class="rating-info">
                                <span class="rating">★ 4.5</span>
                                <span class="reviews">(120)</span>
                            </div>
                        </div>
                        <button class="cart-btn">장바구니 담기</button>
                    </div>
                    <div class="book-card">
                        <div class="book-rank">2</div>
                        <img src="images/book2.jpg" alt="도서 이미지" class="book-image">
                        <div class="book-info">
                            <h4>도서 제목 2</h4>
                            <p class="author">저자명</p>
                            <p class="price">20,000원</p>
                            <div class="rating-info">
                                <span class="rating">★ 4.3</span>
                                <span class="reviews">(98)</span>
                            </div>
                        </div>
                        <button class="cart-btn">장바구니 담기</button>
                    </div>
                    <div class="book-card">
                        <div class="book-rank">3</div>
                        <img src="images/book3.jpg" alt="도서 이미지" class="book-image">
                        <div class="book-info">
                            <h4>도서 제목 3</h4>
                            <p class="author">저자명</p>
                            <p class="price">18,000원</p>
                            <div class="rating-info">
                                <span class="rating">★ 4.7</span>
                                <span class="reviews">(156)</span>
                            </div>
                        </div>
                        <button class="cart-btn">장바구니 담기</button>
                    </div>
                </div>
            </section>

            <section class="new-releases">
                <div class="section-header">
                    <h2>신간 도서</h2>
                    <a href="#" class="more-link">더보기 <i class="fas fa-chevron-right"></i></a>
                </div>
                <div class="book-grid">
                    <div class="book-card">
                        <div class="new-badge">NEW</div>
                        <img src="images/book4.jpg" alt="도서 이미지" class="book-image">
                        <div class="book-info">
                            <h4>도서 제목 4</h4>
                            <p class="author">저자명</p>
                            <p class="price">22,000원</p>
                            <div class="rating-info">
                                <span class="rating">★ 4.2</span>
                                <span class="reviews">(45)</span>
                            </div>
                        </div>
                        <button class="cart-btn">장바구니 담기</button>
                    </div>
                    <div class="book-card">
                        <div class="new-badge">NEW</div>
                        <img src="images/book5.jpg" alt="도서 이미지" class="book-image">
                        <div class="book-info">
                            <h4>도서 제목 5</h4>
                            <p class="author">저자명</p>
                            <p class="price">19,000원</p>
                            <div class="rating-info">
                                <span class="rating">★ 4.4</span>
                                <span class="reviews">(67)</span>
                            </div>
                        </div>
                        <button class="cart-btn">장바구니 담기</button>
                    </div>
                    <div class="book-card">
                        <div class="new-badge">NEW</div>
                        <img src="images/book6.jpg" alt="도서 이미지" class="book-image">
                        <div class="book-info">
                            <h4>도서 제목 6</h4>
                            <p class="author">저자명</p>
                            <p class="price">25,000원</p>
                            <div class="rating-info">
                                <span class="rating">★ 4.6</span>
                                <span class="reviews">(89)</span>
                            </div>
                        </div>
                        <button class="cart-btn">장바구니 담기</button>
                    </div>
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

            // 마우스가 배너 위에 있을 때 자동 슬라이드 중지
            const banner = document.querySelector('.banner-slider');
            banner.addEventListener('mouseenter', stopSlideShow);
            banner.addEventListener('mouseleave', startSlideShow);

            // 초기 슬라이드 쇼 시작
            startSlideShow();
        });
    </script>
</body>
</html> 